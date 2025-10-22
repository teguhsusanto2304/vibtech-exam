<?php

namespace App\Filament\Resources\InventoryAssets\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;

class InventoryAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asset Details')
                    ->schema([
                        // 1. Foreign Key: Category ID
                        Select::make('category_id')
                            ->label('Asset Category')
                            ->relationship('category', 'name') // Assumes 'category' relation exists on the model
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([ // Optional: Allow quick creation of a category
                                TextInput::make('name')->required(),
                            ]),

                        // 2. Item Code (Unique Identifier)
                        TextInput::make('item_code')
                            ->label('Item Code')
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->nullable(),
                        
                        // 3. Item Name
                        TextInput::make('item_name')
                            ->label('Item Name')
                            ->required()
                            ->maxLength(150),

                        // 4. Item Brand
                        TextInput::make('item_brand')
                            ->label('Brand')
                            ->required()
                            ->maxLength(150),
                    ]),
                    Section::make('Media and Status')
                    ->columns(1)
                    ->schema([
                        // 8. Image Path (FileUpload)
                        FileUpload::make('path_image')
                            ->label('Asset Photo')
                            ->disk('public') // Use your desired storage disk
                            ->directory('inventory-assets')
                            ->image()
                            ->nullable()
                            ->visibility('public'),
                            
                        // 9. Data Status (TinyInteger)
                        Radio::make('data_status')
                            ->label('Active Status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(1)
                            ->required()
                            ->inline(),
                    ]),
            ]);
    }
}
