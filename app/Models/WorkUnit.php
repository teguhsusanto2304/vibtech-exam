<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Required for UUID generation

class WorkUnit extends Model
{
    use HasFactory;

    public const ACTIVE_STATUS = 1;
    public const INACTIVE_STATUS = 0;
    public const DELETE_STATUS = 2;

    // 1. Primary Key Type and Name
    // Inform Eloquent that the primary key is a string (UUID)
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id'; // Explicitly setting the key name if not 'id' by default


    // 2. Fillable Attributes
    protected $fillable = [
        'parent_id',
        'unit_name',
        'data_status',
    ];

    // 3. Casts (Optional but Recommended)
    protected $casts = [
        'data_status' => 'integer', // Ensure status is handled as an integer
        // If your database supports UUID natively (e.g., PostgreSQL), casting might be beneficial
    ];

    // 4. UUID Generation (Booting Trait/Method)
    // This automatically sets the 'id' before creating a new model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // ------------------------------------------------------------------
    // 5. RELATIONSHIPS (The Hierarchical Structure)
    // ------------------------------------------------------------------

    /**
     * Get the parent Work Unit.
     */
    public function parent()
    {
        return $this->belongsTo(WorkUnit::class, 'parent_id', 'id');
    }

    /**
     * Get the child Work Units.
     */
    public function children()
    {
        return $this->hasMany(WorkUnit::class, 'parent_id', 'id');
    }

    // ------------------------------------------------------------------
    // 6. SCOPES (Optional: For querying active units)
    // ------------------------------------------------------------------

    /**
     * Scope a query to only include active work units.
     */
    public function scopeActive($query)
    {
        // Assuming '1' means active, based on your migration default(1)
        return $query->where('data_status', 1);
    }

    public function getParentNameAttribute()
    {
        // Check if the parent relationship is loaded, then return its unit_name
        return $this->parent->unit_name ?? null;
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