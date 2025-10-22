<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventoryAssetCategory extends Model
{
    use HasFactory;

    // 1. Table Name (Optional if using conventional naming, but recommended for clarity)
    protected $table = 'inventory_asset_categories';

    // 2. UUID Configuration (Crucial for your schema)
    // Tell Eloquent the primary key is a string (UUID)
    protected $keyType = 'string';
    // Prevent Eloquent from attempting to auto-increment the key
    public $incrementing = false;
    // Confirm the primary key column name
    protected $primaryKey = 'id';

    // 3. Mass Assignment
    protected $fillable = [
        'name',
        'data_status',
    ];

    // 4. Casts
    protected $casts = [
        'data_status' => 'integer',
    ];

    // 5. Automatic UUID Generation (Ensure UUID is set before saving)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // 6. Status Constants (Optional, but good practice)
    public const ACTIVE_STATUS = 1;
    public const INACTIVE_STATUS = 0;
    public const DELETE_STATUS = 3;

    // 7. Scope for Active Records (Optional)
    public function scopeActive($query)
    {
        return $query->where('data_status', self::ACTIVE_STATUS);
    }

    public function getStatusAttribute()
    {
        return match ((int) $this->data_status) {
            self::ACTIVE_STATUS   => 'Active',
            self::INACTIVE_STATUS => 'Inactive',
            self::DELETE_STATUS   => 'Deleted', // Or 'Archived', 'Removed', etc.
            default               => 'Unknown Status',
        };
    }
}