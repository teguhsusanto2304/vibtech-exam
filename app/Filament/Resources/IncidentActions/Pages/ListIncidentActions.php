<?php

namespace App\Filament\Resources\IncidentActions\Pages;

use App\Filament\Resources\IncidentActions\IncidentActionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentActions extends ListRecords
{
    protected static string $resource = IncidentActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
