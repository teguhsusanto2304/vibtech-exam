<?php

namespace App\Filament\Resources\IncidentActions\Pages;

use App\Filament\Resources\IncidentActions\IncidentActionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncidentAction extends CreateRecord
{
    protected static string $resource = IncidentActionResource::class;
}
