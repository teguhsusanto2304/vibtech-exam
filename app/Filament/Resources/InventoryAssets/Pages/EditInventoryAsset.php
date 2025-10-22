<?php

namespace App\Filament\Resources\InventoryAssets\Pages;

use App\Filament\Resources\InventoryAssets\InventoryAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryAsset extends EditRecord
{
    protected static string $resource = InventoryAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
