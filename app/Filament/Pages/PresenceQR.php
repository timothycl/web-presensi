<?php

namespace App\Filament\Pages;

use App\Models\Company;
use Filament\Pages\Page;

class PresenceQR extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected string $view = 'filament.pages.presence-q-r';

    protected static string|null $navigationLabel = 'Presensi QR code';

    protected static string|null $title = 'Presensi QR code';

    protected static string|\UnitEnum|null $navigationGroup = 'Presensi';

    public function getHeading(): string
    {
        return '';
    }

    public function getCompanies()
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin()) {
            return Company::where('id', $user->company_id)->get();
        }
        return Company::all();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }
}
