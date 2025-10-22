<?php

namespace App\Filament\Resources\IncidentActions;

use App\Filament\Resources\IncidentActions\Pages\CreateIncidentAction;
use App\Filament\Resources\IncidentActions\Pages\EditIncidentAction;
use App\Filament\Resources\IncidentActions\Pages\ListIncidentActions;
use App\Filament\Resources\IncidentActions\Schemas\IncidentActionForm;
use App\Filament\Resources\IncidentActions\Tables\IncidentActionsTable;
use App\Models\IncidentAction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentActionResource extends Resource
{
    protected static ?string $model = IncidentAction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'incident Action';

    public static function form(Schema $schema): Schema
    {
        return IncidentActionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentActionsTable::configure($table);
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
            'index' => ListIncidentActions::route('/'),
            'create' => CreateIncidentAction::route('/create'),
            'edit' => EditIncidentAction::route('/{record}/edit'),
        ];
    }
}
