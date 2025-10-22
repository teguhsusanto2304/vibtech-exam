<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'work_unit_id', // 👈 New column
        'role_id',      // 👈 New column
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'work_unit_id' => 'string', // 👈 Cast UUID to string
            'role_id' => 'string',
        ];
    }

    /**
     * A User belongs to one Work Unit (Many-to-One).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workUnit()
    {
        // Assumes WorkUnit model exists and its primary key is 'id' (UUID)
        return $this->belongsTo(WorkUnit::class, 'work_unit_id');
    }

    /**
     * A User belongs to one Role (Many-to-One, based on schema).
     *
     * NOTE: If you are using Spatie, the standard is a many-to-many relationship
     * defined by the HasRoles trait. However, based *strictly* on your migration,
     * this method reflects the `role_id` column.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userRole()
    {
        // Assumes Role model exists and its primary key is 'id' (UUID)
        return $this->belongsTo(Role::class, 'role_id');
    }

    // ------------------------------------------------------------------
    // ACCESSORS (Optional but helpful)
    // ------------------------------------------------------------------

    /**
     * Get the name of the user's Work Unit.
     *
     * @return string|null
     */
    public function getWorkUnitNameAttribute()
    {
        // Uses the workUnit relationship defined above
        return $this->workUnit->unit_name ?? null;
    }

    /**
     * Get the name of the user's Work Unit.
     *
     * @return string|null
     */
    public function getRoleNameAttribute()
    {
        // Uses the workUnit relationship defined above
        return $this->role->name ?? null;
    }

    /**
     * Get the incident reports filed by the user.
     */
    public function reportedIncidents(): HasMany
    {
        return $this->hasMany(IncidentReport::class, 'reported_by_id');
    }

    /**
     * Get the actions taken by the user on incident reports.
     */
    public function actionsTaken(): HasMany
    {
        return $this->hasMany(IncidentAction::class, 'action_taken_by_id');
    }
}
