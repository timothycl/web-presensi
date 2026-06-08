<?php

namespace App\Filament\Resources\UpcomingEventResource\Pages;

use App\Filament\Resources\UpcomingEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUpcomingEvent extends CreateRecord
{
    protected static string $resource = UpcomingEventResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
