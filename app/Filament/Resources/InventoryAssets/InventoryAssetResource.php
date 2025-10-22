<?php

namespace App\Filament\Resources\InventoryAssets;

use App\Filament\Resources\InventoryAssets\Pages\CreateInventoryAsset;
use App\Filament\Resources\InventoryAssets\Pages\EditInventoryAsset;
use App\Filament\Resources\InventoryAssets\Pages\ListInventoryAssets;
use App\Filament\Resources\InventoryAssets\Schemas\InventoryAssetForm;
use App\Filament\Resources\InventoryAssets\Tables\InventoryAssetsTable;
use App\Models\InventoryAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;

class InventoryAssetResource extends Resource
{
    protected static ?string $model = InventoryAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Management Asset';
    protected static ?string $modelLabel = 'Managed Asset';        // 👈 Controls singular titles (e.g., Edit Managed Asset)
    protected static ?string $pluralModelLabel = 'Assets Overview';
    protected static string | UnitEnum | null $navigationGroup = 'Asset Management';

    public static function form(Schema $schema): Schema
    {
        return InventoryAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryAssetsTable::configure($table);
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
            'index' => ListInventoryAssets::route('/'),
            'create' => CreateInventoryAsset::route('/create'),
            'edit' => EditInventoryAsset::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // This checks if the currently authenticated user has the 'create permissions' ability.
        return auth()->user()->can('create inventory_assets');
    }

    // You should also define the other authorization checks here:
    
    public static function canViewAny(): bool
    {
        return auth()->user()->can('view inventory_assets');
    }
    
    // Check for single record deletion
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete inventory_assets', $record);
    }

}
