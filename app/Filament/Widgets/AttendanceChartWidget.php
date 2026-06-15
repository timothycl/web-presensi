<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;
    protected ?string $heading = 'Statistik Kehadiran 30 Hari Terakhir';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $lateData = [];
        $onTimeData = [];
        $user = auth()->user();
        $companyId = $this->filters['company_id'] ?? null;

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $dayAttendance = Attendance::whereDate('attendance_date', $date)
                ->when(! $user->isSuperAdmin(), fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('company_id', $user->company_id)))
                ->when($user->isSuperAdmin() && $companyId, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('company_id', $companyId)));

            $onTimeData[] = (clone $dayAttendance)->where('status', 'on_time')->count();
            $lateData[]   = (clone $dayAttendance)->where('status', 'late')->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tepat Waktu',
                    'data' => $onTimeData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.3)',
                    'borderColor'=> '#10B981',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $lateData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.3)',
                    'borderColor' => '#EF4444',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
