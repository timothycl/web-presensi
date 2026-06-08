<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AttendanceChartWidget;
use App\Filament\Widgets\LatestAttendanceWidget;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;

class AttendanceStats extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|null $navigationLabel = 'Statistik Presensi';

    protected static string|null $title = 'Statistik Presensi';

    protected static string|\UnitEnum|null $navigationGroup = 'Presensi';

    public function getHeading(): string
    {
        return '';
    }

    protected string $view = 'filament.pages.attendance-stats';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AttendanceChartWidget::class,
            LatestAttendanceWidget::class,
        ];
    }
}
