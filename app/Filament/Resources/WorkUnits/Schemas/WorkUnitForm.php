<?php

namespace App\Filament\Resources\WorkUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\WorkUnit;

class WorkUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Parent Unit')
                    ->relationship('parent', 'unit_name') // Recommended way to handle relationships
                    // Alternatively, use options() for custom filtering:
                    ->options(function () {
                        // Query the WorkUnit model
                        return WorkUnit::query()
                            // Filter: Only include units that DO NOT have children
                            ->whereDoesntHave('children')
                            // Map the results to an array of [id => unit_name]
                            ->pluck('unit_name', 'id')
                            ->toArray();
                    })
                    ->searchable() // Allows users to type and filter options
                    ->placeholder('Select a Parent Unit (Optional)')
                    ->nullable(),
                TextInput::make('unit_name')
                    ->required(),
               Radio::make('data_status')
                ->label('Status') // Human-readable label
                ->options([
                    1 => 'Active',    // Value 1 will be saved as an integer
                    0 => 'Inactive',  // Value 0 will be saved as an integer
                ])
                ->default(1)      // Sets 'Active' as the default selection
                ->inline()        // Optional: display options horizontally
                ->required(),
            ]);
    }
}
