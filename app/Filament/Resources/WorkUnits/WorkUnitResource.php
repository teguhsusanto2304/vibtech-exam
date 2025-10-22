<?php

namespace App\Filament\Resources\WorkUnits;

use App\Filament\Resources\WorkUnits\Pages\CreateWorkUnit;
use App\Filament\Resources\WorkUnits\Pages\EditWorkUnit;
use App\Filament\Resources\WorkUnits\Pages\ListWorkUnits;
use App\Filament\Resources\WorkUnits\Schemas\WorkUnitForm;
use App\Filament\Resources\WorkUnits\Tables\WorkUnitsTable;
use App\Models\WorkUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;

class WorkUnitResource extends Resource
{
    protected static ?string $model = WorkUnit::class;

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static ?string $modelLabel = 'Department/Unit';
    protected static ?string $pluralModelLabel = 'Departments and Units';
     

    public static function form(Schema $schema): Schema
    {
        return WorkUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkUnitsTable::configure($table);
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
            'index' => ListWorkUnits::route('/'),
            'create' => CreateWorkUnit::route('/create'),
            'edit' => EditWorkUnit::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // This checks if the currently authenticated user has the 'create permissions' ability.
        return auth()->user()->can('create work_units');
    }

    // You should also define the other authorization checks here:
    
    public static function canViewAny(): bool
    {
        return auth()->user()->can('view work_units');
    }
    
    // Check for single record deletion
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete work_units', $record);
    }

}
