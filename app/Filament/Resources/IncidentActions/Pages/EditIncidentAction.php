<?php

namespace App\Filament\Resources\IncidentActions\Pages;

use App\Filament\Resources\IncidentActions\IncidentActionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncidentAction extends EditRecord
{
    protected static string $resource = IncidentActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
