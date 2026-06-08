<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Carbon\Carbon;
use Filament\Tables;


class LatestAttendanceWidget extends TableWidget
{
     protected static ?string $heading = 'Kehadiran Hari Ini';
     protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
     protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->query(
                 Attendance::query()
                 ->with('user')
                 ->whereDate('attendance_date', Carbon::today())
                 ->when(! $user->isSuperAdmin(), fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('company_id', $user->company_id)))
                 ->latest('check_in_time')
                 )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.employee_id')
                     ->label('Id karyawan')
                     ->searchable(),

                Tables\Columns\TextColumn::make('check_in_time')
                      ->label('Check-in')
                      ->dateTime('H:i')
                      ->sortable(),
                Tables\Columns\TextColumn::make('check_out_time')
                       ->label('Check-out')
                       ->dateTime('H:i')
                       ->placeholder('-')
                       ->sortable(),
                Tables\Columns\TextColumn::make('status')
                       ->label('Status')
                       ->colors(['success' =>'on_time',
                       'warning'=>'late',
                       ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'on_time'=> 'Tepat Waktu',
                    'late'=> 'Terlambat',
                    default => $state,
                }),



            ])
            ->defaultSort('check_in_time', 'desc');
    }
}
