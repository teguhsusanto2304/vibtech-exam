<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Needed for UUID generation
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAsset extends Model
{
    use HasFactory;

    // --- 1. UUID Configuration ---
    // The primary key is a UUID string, not an auto-incrementing integer.
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    // --- 2. Constants for Data Status ---
    public const ACTIVE_STATUS = 1;
    public const INACTIVE_STATUS = 0;

    // --- 3. Mass Assignment Protection ---
    protected $fillable = [
        'path_image',
        'category_id',
        'item_code',
        'item_name',
        'item_specification',
        'item_brand',
        'item_condition',
        'received_date',
        'data_status',
    ];

    // --- 4. Type Casting ---
    protected $casts = [
        'category_id' => 'string',       // Foreign key UUID
        'item_condition' => 'integer',   // tinyInteger
        'data_status' => 'integer',      // tinyInteger
        'received_date' => 'date',       // date type
    ];
    
    // --- 5. Boot Method for UUID Generation ---
    protected static function boot()
    {
        parent::boot();

        // Automatically assign a UUID before creation if one is not set
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    /**
     * An Inventory Asset belongs to one Category (Many-to-One).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        // Links category_id to the 'inventory_asset_categories' table
        return $this->belongsTo(InventoryAssetCategory::class, 'category_id');
    }
    
    // ------------------------------------------------------------------
    // ACCESSORS (For human-readable data)
    // ------------------------------------------------------------------

    /**
     * Get the human-readable status for item_condition.
     *
     * @return string
     */
    public function getItemConditionStatusAttribute(): string
    {
        return match ((int) $this->item_condition) {
            1 => 'Good',
            2 => 'Fair',
            3 => 'Poor',
            default => 'N/A',
        };
    }

    /**
     * Get the human-readable status for data_status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return match ((int) $this->data_status) {
            self::ACTIVE_STATUS   => 'Active',
            self::INACTIVE_STATUS => 'Inactive',
            default => 'N/A',
        };
    }
    
    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    /**
     * Scope a query to only include active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('data_status', self::ACTIVE_STATUS);
    }

    public function currentAllocation()
{
    // The asset has one current allocation record (or null)
    return $this->hasOne(AssetAllocation::class, 'inventory_asset_id', 'id')
                    
                    // CRITICAL: Only include records where the asset has NOT been returned.
                    ->whereNull('return_date')
                    
                    // Best practice: If multiple concurrent allocations somehow exist (due to error), 
                    // we take the latest one.
                    ->latest('allocation_date'); 
    }

    public function incidentReports(): HasMany
    {
        return $this->hasMany(IncidentReport::class, 'inventory_asset_id');
    }
}