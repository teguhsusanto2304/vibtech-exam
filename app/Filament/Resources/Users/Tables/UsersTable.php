<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('workUnit.unit_name') // 👈 Assumes workUnit() relation and 'unit_name' column
                    ->label('Work Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userRole.name') // 👈 Assumes userRole() relation and 'name' column on the Role model
                    ->label('Role')
                    ->searchable()
                    ->sortable(), 
                //
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
