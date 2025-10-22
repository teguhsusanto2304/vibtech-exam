<?php

namespace App\Filament\Resources\IncidentReports\Schemas;

use App\Models\AssetAllocation;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Models\User;
use App\Models\InventoryAsset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;

class IncidentReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asset Details')
                ->columns(1)
                    ->schema([Select::make('inventory_asset_id') // 🚨 1. BIND TO THE ALLOCATION ID
    ->label('Allocated Asset')
    // 🚨 2. QUERY THE AssetAllocation MODEL DIRECTLY
    ->options(function () {
        return AssetAllocation::query()
            // Filter to only active allocations for the logged-in user
            ->where('allocated_to_user_id', Auth::id())
            ->whereNull('return_date')
            ->get()
            ->mapWithKeys(function (AssetAllocation $allocation) {
                // Combine Asset Name and Allocation Date for a descriptive label
                $assetName = optional($allocation->asset)->item_name ?? 'N/A';
                $allocatedDate = optional($allocation->allocation_date)->format('d-m-Y') ?? 'Unknown Date';
                
                $label = "{$assetName} (Allocated: {$allocatedDate})";

                return [$allocation->inventory_asset_id => $label];
            })
            ->toArray();
    })
    ->getSearchResultsUsing(function (string $search, callable $getOptions) {
        // Implement searching here, usually by filtering the AssetAllocation query.
        // For simplicity, this is often the hardest part with custom options. 
        // A direct options query (as shown in the ->options() above) is usually easier.
        return AssetAllocation::query()
            ->where('allocated_to_user_id', Auth::id())
            ->whereNull('return_date')
            // Filter by asset name/code within the relationship
            ->whereHas('asset', fn (Builder $q) => $q->where('item_code', 'like', "%{$search}%")
                                                     ->orWhere('item_name', 'like', "%{$search}%"))
            ->get()
            ->mapWithKeys(function (AssetAllocation $allocation) {
                $assetName = optional($allocation->asset)->item_name ?? 'N/A';
                $allocatedDate = optional($allocation->allocation_date)->format('Y-m-d') ?? 'Unknown Date';
                $label = "{$assetName} (Allocated: {$allocatedDate})";
                return [$allocation->inventory_asset_id => $label];
            })
            ->toArray();
    })
    ->required()
    ->columnSpanFull()
    ->disabled(fn (string $operation): bool => $operation !== 'create'),

            
            
            // --- Incident Details ---
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            
            DateTimePicker::make('incident_date')
                ->required()
                ->default(now())
                ->maxDate(now())
                ->readOnly(fn (string $operation): bool => $operation !== 'create'), // Cannot report incident in the future

            Select::make('severity')
                ->options([
                    1 => 'Low',
                    2 => 'Medium',
                    3 => 'High',
                    4 => 'Critical',
                ])
                ->default(1)
                ->required()
                ->hidden(true),            
            // --- Resolution Fields (Hidden on Create, optional on Edit) ---
            DateTimePicker::make('resolved_at')
                ->label('Resolution Date')
                ->nullable()
                ->maxDate(now())
                ->hidden(true),
                
            RichEditor::make('resolution_details')
                ->label('Resolution Details')
                ->columnSpanFull()
                ->hidden(true),

            Select::make('data_status')
                ->label('Status')
                ->options([
                    1 => 'Reported (Open)',
                    2 => 'Investigating',
                    3 => 'Resolved (Closed)',
                ])
                ->default(1)
                ->required()
                ->hidden(true),
                ]),
                Section::make('Media')
                ->columns(1)
                    ->schema([
                        FileUpload::make('path_image')
                            ->label('Asset Photo')
                            ->disk('public') // Use your desired storage disk
                            ->directory('incident-reports')
                            ->image()
                            ->nullable()
                            ->visibility('public')
                    ]),
                RichEditor::make('description')
                ->required()
                ->columnSpanFull(),

            ]);
    }
}
