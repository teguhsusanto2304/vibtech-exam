<?php

namespace App\Filament\Resources\InventoryAssetCategories\Pages;

use App\Filament\Resources\InventoryAssetCategories\InventoryAssetCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryAssetCategories extends ListRecords
{
    protected static string $resource = InventoryAssetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
