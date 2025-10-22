<?php

namespace App\Filament\Resources\IncidentReports\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\IncidentReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncidentReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset.item_code') 
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('asset.item_name') 
                    ->label('Asset Name')
                    ->searchable()
                    ->sortable(),

                // Reporter Name (from User model)
                TextColumn::make('reporter.name') 
                    ->label('Reported By')
                    ->searchable()
                    ->sortable(),

                // --- Direct Data Columns ---

                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('incident_date')
                    ->date('d M Y H:i:s')
                    ->sortable(),

                // Severity Column (Mapping integer to text and color)
                TextColumn::make('severity')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        4 => 'Critical',
                        default => 'N/A',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        3 => 'danger',
                        4 => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                
                // Status Column (Mapping integer to text)
                TextColumn::make('data_status')
                    ->label('Status')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Reported (Open)',
                        2 => 'Investigating',
                        3 => 'Resolved (Closed)',
                        default => 'Unknown',
                    })
                    ->badge()
                    ->sortable(),

                // Resolution Column
                TextColumn::make('resolved_at')
                    ->label('Resolved On')
                    ->date()
                    ->placeholder('Still Open')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (IncidentReport $record): bool => Auth::id() === (int) $record->reported_by_id), // <-- RESTRICTION ADDED HERE


                // Custom Action to Resolve the Incident
                Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (IncidentReport $record): bool => $record->data_status !== 3 && Auth::user()->can('create incident_actions'))
                    ->form([
                        Textarea::make('resolution_details')
                            ->label('Resolution Details')
                            ->placeholder('Describe the steps taken to resolve this incident.')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (IncidentReport $record, array $data) {
                        $record->update([
                            'data_status' => 3, // Set to Resolved
                            'resolved_at' => Carbon::now(), // Set the resolution date
                            // Assuming you have a 'resolution_details' field in your IncidentReport model
                            'resolution_details' => $data['resolution_details'], 
                        ]);

                        Notification::make()
                            ->title('Incident Resolved')
                            ->body("Incident #{$record->id} has been marked as resolved.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
