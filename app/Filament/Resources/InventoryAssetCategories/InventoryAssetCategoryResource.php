<?php

namespace App\Filament\Resources\InventoryAssetCategories;

use App\Filament\Resources\InventoryAssetCategories\Pages\CreateInventoryAssetCategory;
use App\Filament\Resources\InventoryAssetCategories\Pages\EditInventoryAssetCategory;
use App\Filament\Resources\InventoryAssetCategories\Pages\ListInventoryAssetCategories;
use App\Filament\Resources\InventoryAssetCategories\Schemas\InventoryAssetCategoryForm;
use App\Filament\Resources\InventoryAssetCategories\Tables\InventoryAssetCategoriesTable;
use App\Models\InventoryAssetCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;

class InventoryAssetCategoryResource extends Resource
{
    protected static ?string $model = InventoryAssetCategory::class;

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $pluralModelLabel = 'Assets Categories';
    protected static ?string $modelLabel = 'Assets Category';

    public static function form(Schema $schema): Schema
    {
        return InventoryAssetCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryAssetCategoriesTable::configure($table);
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
            'index' => ListInventoryAssetCategories::route('/'),
            'create' => CreateInventoryAssetCategory::route('/create'),
            'edit' => EditInventoryAssetCategory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // This checks if the currently authenticated user has the 'create permissions' ability.
        return auth()->user()->can('create inventory_asset_categories');
    }

    // You should also define the other authorization checks here:
    
    public static function canViewAny(): bool
    {
        return auth()->user()->can('view inventory_asset_categories');
    }
    
    // Check for single record deletion
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete inventory_asset_categories', $record);
    }
}
