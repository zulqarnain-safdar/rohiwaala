<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'technician_jobs';

    protected $fillable = [
        'order_id',
        'technician_id',
        'status',
        'progress_status',
        'estimated_visit_at',
        'actual_visit_at',
        'visit_latitude',
        'visit_longitude',
        'technician_notes',
        'farmer_notes',
        'rating',
        'amount_paid',
        'payment_method',
        'semen_straw_code',
        'semen_straw_image',
        'cow_photo',
        'cow_status',
        'conception_notes',
        'pregnancy_notes',
        'delivery_notes',
        'calf_image',
        'notified_100_days',
        'notified_200_days',
        'notified_250_days',
        'notified_270_days',
        'notified_delivery_due',
        'last_notification_sent',
    ];

    protected $casts = [
        'estimated_visit_at' => 'datetime',
        'actual_visit_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'notified_100_days' => 'boolean',
        'notified_200_days' => 'boolean',
        'notified_250_days' => 'boolean',
        'notified_270_days' => 'boolean',
        'notified_delivery_due' => 'boolean',
        'last_notification_sent' => 'datetime',
    ];

    /**
     * Get the order that owns the job.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the technician that owns the job.
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * Get the reviews for the job.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the messages for the job.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the cow insemination records for the job.
     */
    public function cowInseminationRecords()
    {
        return $this->hasMany(CowInseminationRecord::class);
    }
}