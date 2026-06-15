<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AttendanceChartWidget;
use App\Filament\Widgets\LatestAttendanceWidget;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AttendanceStats extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'attendance-stats';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|null $navigationLabel = 'Statistik Presensi';

    protected static string|null $title = 'Statistik Presensi';

    protected static string|\UnitEnum|null $navigationGroup = 'Presensi';

    public function getHeading(): string
    {
        return '';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('company_id')
                    ->label('Filter Perusahaan')
                    ->options(\App\Models\Company::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->extraAttributes([
                        'style' => 'z-index: 99999 !important; position: relative;'
                    ]),
            ])
            ->columns(3);
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            AttendanceChartWidget::class,
            LatestAttendanceWidget::class,
        ];
    }
}
