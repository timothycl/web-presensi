<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * @deprecated QR code feature removed — replaced by face verification.
 */
class PresenceQR extends Page
{
    protected string $view = 'filament.pages.presence-q-r';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }
}
