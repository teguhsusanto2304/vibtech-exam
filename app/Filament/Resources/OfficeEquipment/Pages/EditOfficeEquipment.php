<?php

namespace App\Filament\Resources\OfficeEquipment\Pages;

use App\Filament\Resources\OfficeEquipment\OfficeEquipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficeEquipment extends EditRecord
{
    protected static string $resource = OfficeEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
