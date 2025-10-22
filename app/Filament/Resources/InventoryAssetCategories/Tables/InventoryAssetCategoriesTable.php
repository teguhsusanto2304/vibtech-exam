<?php

namespace App\Filament\Resources\InventoryAssetCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class InventoryAssetCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge() // 1. Convert the column text into a badge
                        ->color(fn (string $state): string => match ($state) { // 2. Define conditional colors
                            'Active' => 'success', // Filament maps 'success' to Tailwind/Bootstrap success color (often green)
                            'Inactive' => 'danger',   // Filament maps 'danger' to Tailwind/Bootstrap danger color (often red)
                            default => 'secondary',
                        })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
