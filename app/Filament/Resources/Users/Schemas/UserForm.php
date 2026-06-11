<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\FileUpload::make('photo')
                    ->label('Foto Wajah')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('user-photos'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state)),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('employee_id')
                    ->label('ID User / Employee')
                    ->default(fn () => self::generateNextEmployeeId('employee'))
                    ->readOnly()
                    ->helperText('ID ini digenerate otomatis berdasarkan Role yang dipilih.')
                    ->required()
                    ->unique(
                        table: 'users',
                        column: 'employee_id',
                        ignoreRecord: true),
                TextInput::make('remember_token'),
                Select::make('role')
                    ->options(function () {
                        $options = [
                            'admin' => 'Admin',
                            'employee' => 'Employee',
                        ];
                        
                        // Hanya superadmin yang bisa memberikan role superadmin ke orang lain
                        if (auth()->user()?->isSuperAdmin()) {
                            $options['superadmin'] = 'Super Admin';
                        }
                        
                        return $options;
                    })
                    ->default('employee')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('employee_id', self::generateNextEmployeeId($state));
                    }),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Perusahaan')
                    ->placeholder('Pilih Perusahaan')
                    ->required(fn () => !auth()->user()->isSuperAdmin())
                    ->visible(fn () => auth()->user()->isSuperAdmin()),
                Select::make('approval_status')
                    ->label('Status Persetujuan')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function generateNextEmployeeId(string $role): string
    {
        $prefix = match ($role) {
            'admin' => 'ADM',
            'superadmin' => 'BOSS',
            default => 'TCL',
        };

        $lastUser = \App\Models\User::withTrashed()->where('employee_id', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(employee_id, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if (!$lastUser) {
            return $prefix . '01';
        }

        $lastId = $lastUser->employee_id;
        $number = (int) substr($lastId, strlen($prefix));
        $nextNumber = $number + 1;

        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
