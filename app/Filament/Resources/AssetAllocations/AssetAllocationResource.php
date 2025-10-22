<?php

namespace App\Filament\Resources\AssetAllocations;

use App\Filament\Resources\AssetAllocations\Pages\CreateAssetAllocation;
use App\Filament\Resources\AssetAllocations\Pages\EditAssetAllocation;
use App\Filament\Resources\AssetAllocations\Pages\ListAssetAllocations;
use App\Filament\Resources\AssetAllocations\Schemas\AssetAllocationForm;
use App\Filament\Resources\AssetAllocations\Tables\AssetAllocationsTable;
use App\Models\AssetAllocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AssetAllocationResource extends Resource
{
    protected static ?string $model = AssetAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Asset Allocation';
    protected static string | UnitEnum | null $navigationGroup = 'Asset Management';

    public static function form(Schema $schema): Schema
    {
        return AssetAllocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetAllocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssetAllocations::route('/'),
            'create' => CreateAssetAllocation::route('/create'),
            'edit' => EditAssetAllocation::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('create asset_allocations');
    }

}
