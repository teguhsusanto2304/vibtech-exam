<?php

namespace App\Filament\Resources\AssetAllocations\Pages;

use App\Filament\Resources\AssetAllocations\AssetAllocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAssetAllocations extends ListRecords
{
    protected static string $resource = AssetAllocationResource::class;

    protected function getHeaderActions(): array
    {
        //return [
        //    CreateAction::make(),
        //];
        return [
            CreateAction::make()
                // Control the visibility of the action using the same authorization check
                ->visible(fn (): bool => Auth::user()?->can('create asset_allocations') ?? false),
        ];
    }
}
