<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaceRegistration extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected string $view = 'filament.pages.face-registration';

    protected static string|null $navigationLabel = 'Pendaftaran Wajah';

    protected static string|null $title = 'Pendaftaran Wajah';

    protected static string|\UnitEnum|null $navigationGroup = 'Presensi';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return ! auth()->user()->isAdmin();
    }

    /**
     * Get the reference face photo URLs for the current user.
     * Returns array with 'front', 'right', 'left' keys.
     */
    public function getFacePhotos(): array
    {
        $user = Auth::user();
        $photos = [];

        $getUrl = function ($path) {
            if (!$path) return null;
            if (str_starts_with($path, 'http')) return $path;
            if (Storage::disk('face-photos')->exists($path)) {
                return Storage::disk('face-photos')->url($path);
            }
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
            return Storage::disk('face-photos')->url($path);
        };

        $photos['front'] = $getUrl($user->face_photo_front);
        $photos['right'] = $getUrl($user->face_photo_right);
        $photos['left'] = $getUrl($user->face_photo_left);

        return $photos;
    }

    /**
     * Check if user already has face photos registered.
     */
    public function hasFaceRegistered(): bool
    {
        $user = Auth::user();
        return $user->face_photo_front && $user->face_photo_right && $user->face_photo_left;
    }

    /**
     * Register face by saving 3 captured photos (front, right, left) as face reference photos.
     *
     * @param string $frontPhoto Base64 encoded front face photo
     * @param string $rightPhoto Base64 encoded right face photo
     * @param string $leftPhoto Base64 encoded left face photo
     */
    public function registerFace(string $frontPhoto, string $rightPhoto, string $leftPhoto): void
    {
        $user = Auth::user();

        if ($user->role !== 'employee') {
            Notification::make()
                ->title('Hanya karyawan yang dapat mendaftarkan wajah.')
                ->danger()
                ->send();
            return;
        }

        $photos = [
            'front' => $frontPhoto,
            'right' => $rightPhoto,
            'left'  => $leftPhoto,
        ];

        $savedPaths = [];

        foreach ($photos as $pose => $base64Photo) {
            // Decode base64 image
            $imageData = $base64Photo;
            if (str_contains($imageData, ',')) {
                $imageData = explode(',', $imageData)[1];
            }

            $decoded = base64_decode($imageData);
            if (!$decoded) {
                Notification::make()
                    ->title('Gagal memproses foto.')
                    ->body("Format foto {$pose} tidak valid. Coba ulangi.")
                    ->danger()
                    ->send();
                return;
            }

            $decoded = $this->compressImage($decoded);

            $savedPaths[$pose] = $decoded;
        }

        // Delete old face photos if they exist
        $oldPhotos = [
            $user->face_photo_front,
            $user->face_photo_right,
            $user->face_photo_left,
        ];
        foreach ($oldPhotos as $oldPhoto) {
            if ($oldPhoto && !str_starts_with($oldPhoto, 'http')) {
                Storage::disk('face-photos')->delete($oldPhoto);
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        // Also delete old profile photo
        if ($user->photo && !str_starts_with($user->photo, 'http')) {
            Storage::disk('face-photos')->delete($user->photo);
            Storage::disk('public')->delete($user->photo);
        }

        // Save new face photos to dedicated face-photos disk
        $timestamp = time();
        $filenames = [];
        foreach ($savedPaths as $pose => $decoded) {
            $filename = 'face_' . $user->id . '_' . $pose . '_' . $timestamp . '.jpg';
            Storage::disk('face-photos')->put($filename, $decoded);
            $filenames[$pose] = $filename;
        }

        // Update user with all face photos + set front as profile photo
        $user->update([
            'photo' => $filenames['front'],
            'face_photo_front' => $filenames['front'],
            'face_photo_right' => $filenames['right'],
            'face_photo_left'  => $filenames['left'],
        ]);

        Notification::make()
            ->title('Wajah Berhasil Didaftarkan!')
            ->body('3 foto wajah (depan, kanan, kiri) telah disimpan. Anda sekarang dapat melakukan presensi wajah.')
            ->success()
            ->send();

        $this->dispatch('face-registered');
    }

    protected function compressImage($imageData)
    {
        $image = @imagecreatefromstring($imageData);
        if (!$image) {
            return $imageData;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Start with a reasonable max size to help compression
        $maxWidth = 640;
        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newWidth = $maxWidth;
            $newHeight = $height * $ratio;

            $newImage = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, (int)$newWidth, (int)$newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        $quality = 80;
        ob_start();
        imagejpeg($image, null, $quality);
        $compressedData = ob_get_clean();

        // Target size in bytes (150KB = 153600 bytes)
        while (strlen($compressedData) > 153600 && $quality > 20) {
            $quality -= 10;
            ob_start();
            imagejpeg($image, null, $quality);
            $compressedData = ob_get_clean();
        }

        // If still > 150kb, scale down further
        $width = imagesx($image);
        $height = imagesy($image);
        while (strlen($compressedData) > 153600 && $width > 100) {
            $width = (int) ($width * 0.8);
            $height = (int) ($height * 0.8);

            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
            
            ob_start();
            imagejpeg($newImage, null, $quality);
            $compressedData = ob_get_clean();
            
            imagedestroy($image);
            $image = $newImage;
        }

        imagedestroy($image);

        return $compressedData;
    }
}
