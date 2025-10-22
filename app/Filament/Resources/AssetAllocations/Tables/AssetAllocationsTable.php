<?php

namespace App\Filament\Resources\AssetAllocations\Tables;

use App\Models\AssetAllocation;
use App\Models\InventoryAsset;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\AssetAllocations\AssetAllocationResource;
use App\Filament\Resources\IncidentReports\IncidentReportResource;

class AssetAllocationsTable
{
    public static function configure(Table $table): Table
    {
        $table->modifyQueryUsing(function (Builder $query) {
            // Get the ID of the currently authenticated user
            $userId = Auth::id(); 
            
            // Filter the allocations where the 'user_id' column 
            // matches the logged-in user's ID
            // OR if the user is allocated by work unit, you would need
            // to find the work units associated with the user.
            
            // ⚠️ Simple filter assuming a direct user_id match on the allocation record
            //$query->where('user_id', $userId);
            
            // 💡 Advanced filter (if you want the user to see records 
            // where they are the recipient OR they are in the assigned Work Unit)
            /*
            $workUnitIds = Auth::user()->workUnits()->pluck('id')->toArray(); // Adjust based on your User model relations
            
            $query->where('user_id', $userId)
                  ->orWhereIn('work_unit_id', $workUnitIds);
            */
            
            // If the filter is conditional (e.g., only for users without a specific role)
            if (!Auth::user()?->can('create asset_allocations')) {
                 $query->where('allocated_to_user_id', $userId);
            }

            return $query;
        });

        return $table
            ->recordUrl(function (\Illuminate\Database\Eloquent\Model $record): ?string {
        // Check if the logged-in user has the required permission
        if (Auth::user()?->can('edit asset_allocations')) {
            
            // Reference the Resource class directly to get the edit URL
            return AssetAllocationResource::getUrl('edit', ['record' => $record]);
        }

        // If the user does NOT have permission, return null (no clickable URL).
        return null;
    })
            ->columns([
                TextColumn::make('asset.item_name')
                    ->label('Asset Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('asset.item_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient')
                    ->label('Allocated To')
                    // Displays User name or Work Unit name
                    ->getStateUsing(function (AssetAllocation $record): string {
                        if ($record->user) {
                            return 'User: ' . $record->user->name;
                        }
                        if ($record->workUnit) {
                            return 'Unit: ' . $record->workUnit->name;
                        }
                        return 'N/A';
                    })
                    ->searchable(['user.name', 'workUnit.name'])
                    ->visible(fn (): bool => Auth::user()?->can('edit asset_allocations') ?? false),

                TextColumn::make('location_detail')
                    ->label('Specific Location')
                    ->searchable()
                    ->visible(fn (): bool => Auth::user()?->can('edit asset_allocations') ?? false),

                TextColumn::make('allocation_date')
                    ->label('Allocated Date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('return_date')
                    ->label('Returned Date')
                    ->date()
                    ->placeholder('Active') // Shows 'Active' when return_date is NULL
                    ->sortable()
                    ->toggleable(),
                
                IconColumn::make('is_active')
                    ->label('Status')
                    // Checks if return_date is null to determine 'Active'
                    ->getStateUsing(fn (AssetAllocation $record): bool => is_null($record->return_date))
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->sortable(query: fn (Builder $query, string $direction) => 
                        $query->orderByRaw("return_date IS NULL {$direction}")
                    ),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active (Not Returned)',
                        'returned' => 'Returned (Closed)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] === 'active') {
                            return $query->whereNull('return_date');
                        }
                        if ($data['value'] === 'returned') {
                            return $query->whereNotNull('return_date');
                        }
                        return $query;
                    })
            ])
            ->actions([
                // Disable Edit for closed records to maintain history integrity
                EditAction::make()
                    ->visible(fn (): bool => Auth::user()?->can('edit asset_allocations') ?? false)
                    ->disabled(fn (AssetAllocation $record) => filled($record->return_date)),
                    Action::make('create_maintenance')
        ->label('incident Report')
        ->icon('heroicon-o-wrench-screwdriver') // A relevant icon
        ->color('info')
        ->url(function (\App\Models\AssetAllocation $record): string {
            // Get the URL for the Asset Maintenance Resource's create page
            // We pass the asset's ID as a URL parameter (e.g., 'asset_id')
            
            // You might need to adjust the Resource class name and the parameter key ('asset_id')
            return IncidentReportResource::getUrl('create', [
                'asset_id' => $record->asset_id // Assuming the asset's ID is the key
            ]);
        }),
                
                // Custom action to mark an asset as returned
                Action::make('return_asset')
                    ->label('Return Asset')
                    ->icon('heroicon-o-arrow-uturn-left') 
                    ->color('warning')
                    // Only show this action if the asset is currently active
                    ->visible(function (AssetAllocation $record): bool {
                        $isAdmin = Auth::user()?->can('edit asset_allocations') ?? false;
                        $isActive = is_null($record->return_date);
                        return $isAdmin && $isActive;
                    })
                    ->form([
                        DatePicker::make('return_date')
                            ->label('Actual Return Date')
                            ->default(now())
                            ->required()
                            ->minDate(fn (AssetAllocation $record) => $record->allocation_date),
                        Textarea::make('return_notes')
                            ->label('Return Notes')
                            ->rows(2)
                            ->nullable(),
                    ])
                    ->action(function (AssetAllocation $record, array $data): void {
                        DB::transaction(function () use ($record, $data) {
                            $record->update([
                                'return_date' => $data['return_date'],
                                // Append new return notes to existing notes
                                'notes' => $record->notes . "\n\n[RETURN NOTES on {$data['return_date']}]: " . $data['return_notes'],
                                'data_status' => InventoryAsset::INACTIVE_STATUS, // Mark as closed
                            ]);
                        });

                        Notification::make()
                            ->title('Asset Returned')
                            ->body("Allocation record for **{$record->asset->item_name}** has been closed.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->visible(fn (): bool => Auth::user()?->can('delete asset_allocations') ?? false),
                ]),
            ])
            // Set default sort order to show the most recent allocations first
            ->defaultSort('allocation_date', 'desc');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('create asset_allocations');
    }
}
