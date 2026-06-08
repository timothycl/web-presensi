<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MobileAccessWidget extends Widget
{
    protected string $view = 'filament.widgets.mobile-access-widget';

    protected static ?int $sort = 10;

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
}
