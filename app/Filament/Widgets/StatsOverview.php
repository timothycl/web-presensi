<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $user = auth()->user();

        $totalEmployees = User::where('role', 'employee')
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('company_id', $user->company_id))
            ->count();

        $attendanceToday = Attendance::whereDate('attendance_date', $today)
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('company_id', $user->company_id)));


        $presentToday = $attendanceToday->count();

        $onTimeToday = (clone $attendanceToday)->where('status', 'on_time')->count();

        $lateToday = (clone $attendanceToday)->where('status', 'late')->count();

        $notCheckedIn = $totalEmployees - $presentToday;


        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description('Karyawan terdaftar')
                ->descriptionIcon('heroicon-o-users'),

            Stat::make('Hadir Hari ini', $presentToday)
                ->description("{$onTimeToday} Tepat Waktu, {$lateToday} Terlambat")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Belum Absen', $notCheckedIn)
            ->description('Hari ini')
            ->descriptionIcon('heroicon-o-x-circle')
            ->color('danger'),
        ];
    }
}
