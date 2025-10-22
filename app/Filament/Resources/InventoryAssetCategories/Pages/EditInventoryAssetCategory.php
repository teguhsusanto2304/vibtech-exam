<?php

namespace App\Filament\Resources\InventoryAssetCategories\Pages;

use App\Filament\Resources\InventoryAssetCategories\InventoryAssetCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryAssetCategory extends EditRecord
{
    protected static string $resource = InventoryAssetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
