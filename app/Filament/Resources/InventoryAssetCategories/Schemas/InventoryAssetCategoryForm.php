<?php

namespace App\Filament\Resources\InventoryAssetCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;

class InventoryAssetCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
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
