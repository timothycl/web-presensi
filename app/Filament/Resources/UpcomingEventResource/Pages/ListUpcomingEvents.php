<?php

namespace App\Filament\Resources\UpcomingEventResource\Pages;

use App\Filament\Resources\UpcomingEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpcomingEvents extends ListRecords
{
    protected static string $resource = UpcomingEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
