<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Company;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceScanner extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected string $view = 'filament.pages.attendance-scanner';

    protected static string|null $navigationLabel = 'Presensi Wajah';

    protected static string|null $title = 'Presensi Verifikasi Wajah';

    protected static string|\UnitEnum|null $navigationGroup = 'Presensi';
    
    public static function shouldRegisterNavigation(): bool
    {
        return ! auth()->user()->isAdmin();
    }


    public $latitude;
    public $longitude;
    public $attendance;

    public function mount()
    {
        $this->loadAttendance();
    }

    public function loadAttendance()
    {
        $this->attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('attendance_date', Carbon::today())
            ->first();
    }

    /**
     * Get the reference face photo URL for the current user.
     * Returns null if no photo is set.
     */
    public function getUserFacePhotoUrl(): ?string
    {
        $user = Auth::user();
        if (!$user || !$user->photo) {
            return null;
        }
        // Supports both full URL and storage path
        if (str_starts_with($user->photo, 'http')) {
            return $user->photo;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($user->photo);
    }

    /**
     * Register a face by saving the captured photo as the user's profile photo.
     *
     * @param string $base64Photo Base64 encoded photo from webcam
     */
    public function registerFace(string $base64Photo): void
    {
        $user = Auth::user();

        if ($user->role !== 'employee') {
            Notification::make()
                ->title('Hanya karyawan yang dapat mendaftarkan wajah.')
                ->danger()
                ->send();
            return;
        }

        // Decode base64 image
        $imageData = $base64Photo;
        if (str_contains($imageData, ',')) {
            $imageData = explode(',', $imageData)[1];
        }

        $decoded = base64_decode($imageData);
        if (!$decoded) {
            Notification::make()
                ->title('Gagal memproses foto.')
                ->body('Format foto tidak valid. Coba ulangi.')
                ->danger()
                ->send();
            return;
        }

        // Delete old profile photo if exists and it's a local file
        if ($user->photo && !str_starts_with($user->photo, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        // Save new face photo as profile photo
        $filename = 'profile-photos/face_' . $user->id . '_' . time() . '.jpg';
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $decoded);

        // Update user profile photo
        $user->update(['photo' => $filename]);

        Notification::make()
            ->title('Wajah Berhasil Didaftarkan!')
            ->body('Foto wajah Anda telah disimpan sebagai foto profil. Silakan lakukan verifikasi wajah kembali.')
            ->success()
            ->send();

        $this->dispatch('face-registered');
    }


    /**
     * Process attendance via face verification (no barcode needed).
     *
     * @param float $lat User latitude
     * @param float $lon User longitude
     * @param string|null $selfiePhoto Base64 encoded selfie image
     */
    public function processAttendance($lat, $lon, $selfiePhoto = null)
    {
        $this->latitude = $lat;
        $this->longitude = $lon;

        $user = Auth::user();

        // 0. Verify Role
        if ($user->role !== 'employee') {
            Notification::make()
                ->title('Hanya karyawan yang dapat melakukan presensi.')
                ->danger()
                ->send();
            return;
        }

        // 1. Verify user has reference photo
        if (!$user->photo) {
            Notification::make()
                ->title('Foto profil belum ada.')
                ->body('Silakan upload foto profil terlebih dahulu agar verifikasi wajah dapat dilakukan.')
                ->danger()
                ->send();
            return;
        }

        $company = Company::getCompany();

        if (!$company) {
            Notification::make()
                ->title('Konfigurasi perusahaan tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // 2. Verify Location
        if (!$this->isWithinRadius($company)) {
            Notification::make()
                ->title('Anda berada di luar radius kantor.')
                ->body('Pastikan GPS Anda aktif dan Anda berada di lokasi kantor.')
                ->danger()
                ->send();
            return;
        }

        $today = Carbon::today();
        $now = Carbon::now();

        // 3. Determine action based on attendance status
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            $this->handleCheckIn($user, $today, $now, $company, $selfiePhoto);
        } elseif (!$attendance->check_out_time) {
            $this->handleCheckOut($user, $today, $now, $selfiePhoto);
        } else {
            Notification::make()
                ->title('Anda sudah menyelesaikan presensi hari ini.')
                ->warning()
                ->send();
        }
    }

    protected function handleCheckIn($user, $today, $now, $company, $selfiePhoto)
    {
        if (!$selfiePhoto) {
            Notification::make()
                ->title('Foto selfie wajib diambil.')
                ->danger()
                ->send();
            return;
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance && $attendance->check_in_time) {
            Notification::make()
                ->title('Anda sudah melakukan Check-In hari ini.')
                ->warning()
                ->send();
            return;
        }

        // Save Selfie Photo
        $photoPath = $this->saveSelfie($selfiePhoto, 'check_in', $user->id);

        if (!$photoPath) {
            Notification::make()
                ->title('Gagal menyimpan foto selfie.')
                ->danger()
                ->send();
            return;
        }

        // Calculate status (on_time or late)
        $workStartTime = $company->work_start_time->format('H:i:s');
        $checkInTime = $now->format('H:i:s');
        $status = $checkInTime > $workStartTime ? 'late' : 'on_time';

        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'attendance_date' => $today],
            [
                'check_in_time' => $now->toDateTimeString(),
                'check_in_latitude' => $this->latitude,
                'check_in_longitude' => $this->longitude,
                'check_in_photo' => $photoPath,
                'status' => $status,
            ]
        );

        Notification::make()
            ->title('Check-In Berhasil!')
            ->success()
            ->send();
            
        $this->loadAttendance();
        $this->dispatch('attendance-success');
    }

    protected function handleCheckOut($user, $today, $now, $selfiePhoto)
    {
        if (!$selfiePhoto) {
            Notification::make()
                ->title('Foto selfie wajib diambil.')
                ->danger()
                ->send();
            return;
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            Notification::make()
                ->title('Anda belum melakukan Check-In hari ini.')
                ->danger()
                ->send();
            return;
        }

        if ($attendance->check_out_time) {
            Notification::make()
                ->title('Anda sudah melakukan Check-Out hari ini.')
                ->warning()
                ->send();
            return;
        }

        // Save Selfie Photo
        $photoPath = $this->saveSelfie($selfiePhoto, 'check_out', $user->id);

        if (!$photoPath) {
            Notification::make()
                ->title('Gagal menyimpan foto selfie.')
                ->danger()
                ->send();
            return;
        }

        $attendance->update([
            'check_out_time' => $now->toDateTimeString(),
            'check_out_latitude' => $this->latitude,
            'check_out_longitude' => $this->longitude,
            'check_out_photo' => $photoPath,
        ]);

        Notification::make()
            ->title('Check-Out Berhasil!')
            ->success()
            ->send();
            
        $this->loadAttendance();
        $this->dispatch('attendance-success');
    }

    protected function saveSelfie($base64String, $prefix, $userId)
    {
        if (!$base64String) {
            return null;
        }

        // Clean base64 string
        if (str_contains($base64String, ',')) {
            $base64String = explode(',', $base64String)[1];
        }

        $decoded = base64_decode($base64String);
        if (!$decoded) {
            return null;
        }

        $filename = 'attendance/photos/' . $prefix . '_' . $userId . '_' . time() . '.jpg';
        
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    protected function isWithinRadius($company)
    {
        if (!$this->latitude || !$this->longitude) return false;

        $distance = $this->calculateDistance(
            $this->latitude,
            $this->longitude,
            $company->latitude,
            $company->longitude
        );

        return $distance <= $company->radius;
    }

    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
