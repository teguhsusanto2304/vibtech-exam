<?php

namespace App\Filament\Resources\InventoryAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InventoryAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Image/Photo Column
                ImageColumn::make('path_image')
                ->disk('public'),
                
                // 2. Relationship Column (Category Name)
                TextColumn::make('category.name') // Assumes relationship name is 'category'
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                // 3. Item Details
                TextColumn::make('item_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('item_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                    
                TextColumn::make('item_brand')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),

                // 4. Conditional Badge for Item Condition (Assuming 1=Good, 2=Fair, 3=Poor)
                TextColumn::make('item_condition')
                    ->label('Condition')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Good',
                        2 => 'Fair',
                        3 => 'Poor',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        3 => 'danger',
                        default => 'secondary',
                    }),
                
                // 5. Date Column
                TextColumn::make('received_date')
                    ->label('Received')
                    ->date()
                    ->sortable(),
                    
                // 6. Data Status Badge (Assuming 1=Active, 0=Inactive)
                TextColumn::make('data_status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Active',
                        0 => 'Inactive',
                        default => 'N/A',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        0 => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                // Filter by Asset Category
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'), // Assumes relationship name is 'category'

                // Filter by Item Condition
                SelectFilter::make('item_condition')
                    ->label('Condition')
                    ->options([
                        1 => 'Good',
                        2 => 'Fair',
                        3 => 'Poor',
                    ]),
                    
                // Filter for Assets Received within the Last 30 Days
                Filter::make('recent_receipts')
                    ->query(fn (Builder $query) => $query->where('received_date', '>=', now()->subDays(30)))
                    ->label('Received Last 30 Days'),
            ])
            ->actions([
                EditAction::make(),
                // Add DeleteAction here for single record deletion
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}