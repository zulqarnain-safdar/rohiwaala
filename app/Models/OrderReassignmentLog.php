<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReassignmentLog extends Model
{
    protected $fillable = [
        'order_id',
        'job_id',
        'from_technician_id',
        'to_technician_id',
        'reassigned_by_technician_id',
        'reason',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the order that was reassigned
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the job that was reassigned
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the technician who originally had the job
     */
    public function fromTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'from_technician_id');
    }

    /**
     * Get the technician who received the job
     */
    public function toTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'to_technician_id');
    }

    /**
     * Get the technician who performed the reassignment
     */
    public function reassignedByTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'reassigned_by_technician_id');
    }
}
