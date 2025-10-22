<?php

namespace App\Filament\Resources\IncidentReports;

use App\Filament\Resources\IncidentReports\Pages\CreateIncidentReport;
use App\Filament\Resources\IncidentReports\Pages\EditIncidentReport;
use App\Filament\Resources\IncidentReports\Pages\ListIncidentReports;
use App\Filament\Resources\IncidentReports\Schemas\IncidentReportForm;
use App\Filament\Resources\IncidentReports\Tables\IncidentReportsTable;
use App\Models\IncidentReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentReportResource extends Resource
{
    protected static ?string $model = IncidentReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Incident Report';

    public static function form(Schema $schema): Schema
    {
        return IncidentReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentReportsTable::configure($table);
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
            'index' => ListIncidentReports::route('/'),
            'create' => CreateIncidentReport::route('/create'),
            'edit' => EditIncidentReport::route('/{record}/edit'),
        ];
    }
}
