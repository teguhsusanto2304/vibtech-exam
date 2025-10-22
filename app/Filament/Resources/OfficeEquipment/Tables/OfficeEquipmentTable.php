<?php

namespace App\Filament\Resources\OfficeEquipment\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class OfficeEquipmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                // ✅ Image of the asset
                ImageColumn::make('inventoryAsset.path_image')
                    ->label('Image')
                    ->circular() // or ->square()
                    ->size(60)
                    ->defaultImageUrl(asset('images/default.png')),

                // ✅ Asset name
                TextColumn::make('inventoryAsset.item_name')
                    ->label('Asset Name')
                    ->sortable()
                    ->searchable(),

                // ✅ Serial number
                TextColumn::make('inventoryAsset.item_code')
                    ->label('Serial Number')
                    ->searchable(),

                // ✅ Allocation details
                TextColumn::make('location_detail')
                    ->label('Location')
                    ->sortable(),

                TextColumn::make('allocation_date')
                    ->label('Allocated On')
                    ->date(),

                TextColumn::make('return_date')
                    ->label('Return Date')
                    ->date()
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('Y-m-d') : '-'),

                // ✅ Current user (if linked)
                TextColumn::make('allocatedToUser.name')
                    ->label('Allocated To')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('Y-m-d') : '-'),
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
