<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * @deprecated QR code widget removed — replaced by face verification.
 */
class CompanyQRWidget extends Widget
{
    protected string $view = 'filament.widgets.company-q-r-widget';

    public static function canView(): bool
    {
        return false;
    }
}
