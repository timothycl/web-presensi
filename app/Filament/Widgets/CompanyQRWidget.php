<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Widgets\Widget;

class CompanyQRWidget extends Widget
{
    protected string $view = 'filament.widgets.company-q-r-widget';

    protected static ?int $sort = -2;

    public function getCompany()
    {
        return Company::getCompany();
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
}
