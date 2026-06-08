<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UpcomingEventsWidget extends Widget
{
    protected string $view = 'filament.widgets.upcoming-events-widget';

    protected static ?int $sort = -1;

    protected int | string | array $columnSpan = 1;

    public function getViewData(): array
    {
        // Automatically delete events that have already passed
        \App\Models\UpcomingEvent::where('event_date', '<', now()->startOfDay())->delete();

        return [
            'events' => \App\Models\UpcomingEvent::query()
                ->where('event_date', '>=', now()->startOfDay())
                ->orderBy('event_date', 'asc')
                ->get(),
        ];
    }
}
