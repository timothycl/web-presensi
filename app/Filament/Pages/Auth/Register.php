<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Resources\Users\Schemas\UserForm;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

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
        
        return $this->getUserModel()::create($data);
    }

    public function rateLimit($maxAttempts, $decaySeconds = 60, $method = null, $component = null)
    {
        // Disable rate limiting for registration
        return;
    }
}
