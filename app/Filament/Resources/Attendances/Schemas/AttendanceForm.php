<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: function ($query) {
                        if (!auth()->user()->isSuperAdmin()) {
                            $query->where('company_id', auth()->user()->company_id);
                        }
                    })
                    ->required(),
                DatePicker::make('attendance_date')
                    ->required()
                    ->default(now()),
                DateTimePicker::make('check_in_time')
                    ->required()
                    ->default(now()),
                TextInput::make('check_in_latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('check_in_longitude')
                    ->required()
                    ->numeric(),
                FileUpload::make('check_in_photo')
                    ->image()
                    ->directory('attendance/photos')
                    ->required(),
                DateTimePicker::make('check_out_time'),
                TextInput::make('check_out_latitude')
                    ->numeric(),
                TextInput::make('check_out_longitude')
                    ->numeric(),
                FileUpload::make('check_out_photo')
                    ->image()
                    ->directory('attendance/photos'),
                Select::make('status')
                    ->options(['on_time' => 'On time', 'late' => 'Late'])
                    ->default('on_time')
                    ->required(),
            ]);
    }
}
