<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required(),
                TextInput::make('address')
                ->required(),
                TextInput::make('latitude')
                    ->id('company-latitude')
                    ->required()
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90)
                    ->suffixAction(
                        \Filament\Actions\Action::make('getLocation')
                            ->icon('heroicon-m-map-pin')
                            ->color('success')
                            ->extraAttributes([
                                'onclick' => "
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition(function(position) {
                                            document.getElementById('company-latitude').value = position.coords.latitude;
                                            document.getElementById('company-latitude').dispatchEvent(new Event('input'));
                                            document.getElementById('company-longitude').value = position.coords.longitude;
                                            document.getElementById('company-longitude').dispatchEvent(new Event('input'));
                                            new FilamentNotification()
                                                .title('Lokasi berhasil diambil!')
                                                .success()
                                                .send();
                                        }, function(error) {
                                            new FilamentNotification()
                                                .title('Gagal mengambil lokasi')
                                                .body(error.message)
                                                .danger()
                                                .send();
                                        });
                                    } else {
                                        alert('Geolocation tidak didukung oleh browser ini.');
                                    }
                                    return false;
                                "
                            ])
                    ),
                TextInput::make('longitude')
                    ->id('company-longitude')
                    ->required()
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180),
                TextInput::make('radius')
                ->required()
                ->numeric()
                ->default(100),
                TimePicker::make('work_start_time')
                ->required(),
                TimePicker::make('work_end_time')
                ->required(),
                TextInput::make('check_in_code')
                    ->helperText('Klik ikon putar untuk membuat kode QR baru.')
                    ->suffixAction(
                        fn ($state, $set) => \Filament\Actions\Action::make('generateIn')
                            ->icon('heroicon-m-arrow-path')
                            ->action(fn () => $set('check_in_code', 'IN-' . strtoupper(str()->random(10))))
                    ),
            ]);
    }
}
