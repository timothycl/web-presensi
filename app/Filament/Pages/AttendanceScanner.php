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
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected string $view = 'filament.pages.attendance-scanner';

    protected static string|null $navigationLabel = 'Scan Presensi';

    protected static string|null $title = 'Scan Presensi Barcode';

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

    public function processScan($code, $lat, $lon, $selfiePhoto = null)
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

        $company = Company::getCompany();

        if (!$company) {
            Notification::make()
                ->title('Konfigurasi perusahaan tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // 1. Verify Location
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

        // 2. Determine Action dynamically based on attendance status
        if ($code !== $company->check_in_code) {
            Notification::make()
                ->title('Kode Barcode tidak valid.')
                ->danger()
                ->send();
            return;
        }

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
                'check_in_time' => $now->format('H:i:s'),
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
        $this->dispatch('scan-success');
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
            'check_out_time' => $now->format('H:i:s'),
            'check_out_latitude' => $this->latitude,
            'check_out_longitude' => $this->longitude,
            'check_out_photo' => $photoPath,
        ]);

        Notification::make()
            ->title('Check-Out Berhasil!')
            ->success()
            ->send();
            
        $this->loadAttendance();
        $this->dispatch('scan-success');
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
