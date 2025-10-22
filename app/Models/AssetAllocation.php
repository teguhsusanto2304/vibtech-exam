<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AssetAllocation extends Model
{
    use HasFactory;

    // --- 1. UUID Configuration ---
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    // --- 2. Mass Assignment Protection ---
    protected $fillable = [
        'inventory_asset_id',
        'allocated_to_user_id',
        'allocated_to_work_unit_id',
        'location_detail',
        'allocation_date',
        'return_date',
        'notes',
        'data_status'
    ];

    // --- 3. Type Casting ---
    protected $casts = [
        'inventory_asset_id' => 'string',
        'allocated_to_user_id' => 'string',
        'allocated_to_work_unit_id' => 'string',
        'allocation_date' => 'date',
        'return_date' => 'date',
    ];

    // --- 4. Boot Method for UUID Generation ---
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
     * An Asset Allocation belongs to one Inventory Asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo(InventoryAsset::class, 'inventory_asset_id');
    }
    
    // You would typically define relationships here for User and Department as well, 
    // assuming those models exist and use UUIDs.
    
    public function user()
    {
        return $this->belongsTo(User::class, 'allocated_to_user_id');
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class, 'allocated_to_work_unit_id');
    }

    public function inventoryAsset()
{
    return $this->belongsTo(\App\Models\InventoryAsset::class);
}
    
}
