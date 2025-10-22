<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentAction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'incident_actions';

    protected $fillable = [
        'incident_report_id',
        'action_taken_by_id',
        'action_type',
        'details',
        'action_date',
    ];

    protected $casts = [
        'action_date' => 'datetime',
        'action_type' => 'integer',
    ];

    /**
     * Get the incident report this action belongs to.
     */
    public function incidentReport(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'incident_report_id');
    }

    /**
     * Get the user who took this action.
     */
    public function actionTaker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_taken_by_id');
    }
}