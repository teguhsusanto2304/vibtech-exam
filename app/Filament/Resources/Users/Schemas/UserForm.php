<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true) // Ensure email uniqueness, ignore the current record on edit
                    ->maxLength(255),

                // Password field is only required on creation
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state)) // Hash password on save
                    ->dehydrated(fn (?string $state): bool => filled($state)) // Only send to DB if filled
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                
                // --- Foreign Key Fields (Select Components) ---

                // 1. Work Unit Assignment (Many-to-One)
                Select::make('work_unit_id')
                    ->label('Work Unit')
                    // Uses the Eloquent 'workUnit' relationship defined in the User model
                    ->relationship('workUnit', 'unit_name') 
                    ->searchable()
                    ->preload() // Load options upfront for faster access
                    ->nullable() // Allow users without a unit (e.g., top-level admin)
                    ->createOptionForm([ // Optional: Allow creating a new unit from the user form
                        TextInput::make('unit_name')->required(),
                    ]),

                // 2. Role Assignment (Many-to-One, based on your migration)
                Select::make('role_id')
                    ->label('Assigned Role')
                    // Uses the Eloquent 'userRole' relationship defined in the User model
                    ->relationship('userRole', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
