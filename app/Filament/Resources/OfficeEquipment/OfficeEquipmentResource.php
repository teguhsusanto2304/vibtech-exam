<?php

namespace App\Filament\Resources\OfficeEquipment;

use App\Filament\Resources\OfficeEquipment\Pages\CreateOfficeEquipment;
use App\Filament\Resources\OfficeEquipment\Pages\EditOfficeEquipment;
use App\Filament\Resources\OfficeEquipment\Pages\ListOfficeEquipment;
use App\Filament\Resources\OfficeEquipment\Schemas\OfficeEquipmentForm;
use App\Filament\Resources\OfficeEquipment\Tables\OfficeEquipmentTable;
use App\Models\AssetAllocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;


class OfficeEquipmentResource extends Resource
{
    protected static ?string $model = AssetAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Office Equipment';

    

    // ✅ (Optional) This controls the title used on the resource pages
    protected static ?string $modelLabel = 'Office Equipment Allocation';
    protected static ?string $pluralModelLabel = 'Office Equipments';
    protected static string | UnitEnum | null $navigationGroup = 'Asset Management';

    public static function form(Schema $schema): Schema
    {
        return OfficeEquipmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficeEquipmentTable::configure($table);
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
            'index' => ListOfficeEquipment::route('/'),
            //'create' => CreateOfficeEquipment::route('/create'),
            //'edit' => EditOfficeEquipment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        // Example: only show records allocated to the current user
        // unless they are admin (assuming `is_admin` column or role)
        //if (!$user->hasRole('admin')) {
            $query->where('allocated_to_user_id', $user->id);
        //}

        return $query;
    }
}
