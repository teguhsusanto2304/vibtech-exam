<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class IncidentReport extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'incident_reports';

    protected $fillable = [
        'inventory_asset_id',
        'reported_by_id',
        'incident_date',
        'title',
        'description',
        'severity',
        'data_status',
        'resolved_at',
        'resolution_details',
        'path_image'
    ];

    protected $casts = [
        'reported_by_id' => 'string',
        'incident_date' => 'datetime',
        'resolved_at' => 'datetime',
        'severity' => 'integer',
        'data_status' => 'integer',
    ];

    /**
     * Get the asset involved in the incident.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(InventoryAsset::class, 'inventory_asset_id');
    }

    /**
     * Get the user who reported the incident.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_id');
    }

    /**
     * Get the actions taken for this incident.
     */
    public function actions(): HasMany
    {
        return $this->hasMany(IncidentAction::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($incident) {
            $incident->reported_by_id = Auth::id();
        });
    }
}