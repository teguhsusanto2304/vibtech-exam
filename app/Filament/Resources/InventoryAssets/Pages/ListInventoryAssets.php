<?php

namespace App\Filament\Resources\InventoryAssets\Pages;

use App\Filament\Resources\InventoryAssets\InventoryAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryAssets extends ListRecords
{
    protected static string $resource = InventoryAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
