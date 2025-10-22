<?php

namespace App\Filament\Resources\AssetAllocations\Schemas;

use App\Models\InventoryAsset;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use App\Models\AssetAllocation;
use Forms\Get;
use Illuminate\Database\Eloquent\Builder;

class AssetAllocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asset Details')
                    ->columns(2)
                    ->schema([
                        Select::make('inventory_asset_id')
                            ->label('Inventory Asset')
                            ->relationship(name: 'asset', titleAttribute: 'item_name', modifyQueryUsing: fn (Builder $query) => $query->where('data_status', InventoryAsset::ACTIVE_STATUS)->doesntHave('currentAllocation'))
                            ->getOptionLabelFromRecordUsing(fn (InventoryAsset $record) => "{$record->item_name} ({$record->item_code})")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->disabledOn('edit')
                            ->helperText('Only active and unallocated assets are shown.'),

                        DatePicker::make('allocation_date')
                            ->label('Allocation Date')
                            ->default(now())
                            ->required(),

                        DatePicker::make('return_date')
                            ->label('Return Date')
                            ->placeholder('Leave blank if still allocated')
                            ->nullable()
                            ->hiddenOn('create')
                            
                            ->helperText('Setting a return date closes the allocation record.'),
                    ]),

                Section::make('Recipient Details')
                    ->columns(2)
                    ->schema([
                        Select::make('allocated_to_user_id')
                            ->label('Allocated To (User)')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('allocated_to_work_unit_id')
                            ->label('Allocated To (Work Unit)')
                            ->relationship(name: 'workUnit', titleAttribute: 'unit_name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        
                        TextInput::make('location_detail')
                            ->label('Specific Location')
                            ->maxLength(255)
                            ->placeholder('e.g., Room 305, Server Rack B'),
                        
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull()
                            ->rows(3),
                        
                        // Hidden field for data_status, assuming logic for this is handled outside form submission
                        Hidden::make('data_status')
                            ->default(1),
                    ])->columns(2),
            ]);
    }
}
