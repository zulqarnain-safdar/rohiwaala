<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CowInseminationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'order_item_id',
        'cow_in_heat',
        'cow_photo',
        'straw_barcode_code',
        'straw_photo',
        'amount_received',
        'payment_method',
        'notes',
        'pregnancy_status',
        'conception_notes',
        'pregnancy_notes',
        'delivery_notes',
        'calf_image',
        'conception_date',
        'pregnancy_date',
        'delivery_date',
    ];

    protected $casts = [
        'amount_received' => 'decimal:2',
        'conception_date' => 'datetime',
        'pregnancy_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    /**
     * Get the job that owns the cow insemination record.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the order item that owns the cow insemination record.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}