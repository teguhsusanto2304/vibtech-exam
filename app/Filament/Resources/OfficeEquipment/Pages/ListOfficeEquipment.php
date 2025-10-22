<?php

namespace App\Filament\Resources\OfficeEquipment\Pages;

use App\Filament\Resources\OfficeEquipment\OfficeEquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficeEquipment extends ListRecords
{
    protected static string $resource = OfficeEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
