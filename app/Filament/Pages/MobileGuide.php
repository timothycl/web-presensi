<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MobileGuide extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static string|null $navigationLabel = 'Panduan Akses Mobile';

    protected static string|null $title = '';

    protected static string|\UnitEnum|null $navigationGroup = 'Bantuan';

    protected string $view = 'filament.pages.mobile-guide';

    public static function canAccess(): bool
    {
        return true; // Everyone should be able to see the guide
    }
}
