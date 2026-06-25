<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Resources\Users\Schemas\UserForm;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Events\Registered;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                
                // Extra Fields for Attendance System
                \Filament\Forms\Components\Select::make('company_id')
                    ->label('Perusahaan')
                    ->options(\App\Models\Company::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih perusahaan tempat Anda bekerja.'),

                \Filament\Forms\Components\FileUpload::make('photo')
                    ->label('Foto Wajah')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('user-photos')
                    ->required()
                    ->helperText('Mohon unggah foto wajah Anda dengan jelas.'),

                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->placeholder('08xxx')
                    ->required(),
                    
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->default(fn () => UserForm::generateNextEmployeeId('employee'))
                    ->readOnly()
                    ->helperText('ID digenerate otomatis oleh sistem.')
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Selalu set role sebagai employee untuk pendaftaran mandiri
        $data['role'] = 'employee';
        
        // Compress photo to 10kb
        if (isset($data['photo'])) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($data['photo']);
            if (file_exists($path)) {
                $imageData = file_get_contents($path);
                $compressed = $this->compressImageTo10Kb($imageData);
                file_put_contents($path, $compressed);
            }
        }
        
        return $this->getUserModel()::create($data);
    }

    protected function compressImageTo10Kb($imageData)
    {
        $image = @imagecreatefromstring($imageData);
        if (!$image) {
            return $imageData;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $maxWidth = 320;
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

        while (strlen($compressedData) > 10240 && $quality > 10) {
            $quality -= 10;
            ob_start();
            imagejpeg($image, null, $quality);
            $compressedData = ob_get_clean();
        }

        $width = imagesx($image);
        $height = imagesy($image);
        while (strlen($compressedData) > 10240 && $width > 50) {
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

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (): Model {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);
        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    public function rateLimit($maxAttempts, $decaySeconds = 60, $method = null, $component = null)
    {
        // Disable rate limiting for registration
        return;
    }
}
