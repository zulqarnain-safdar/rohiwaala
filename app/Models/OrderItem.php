<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'bull_semen_id',
        'quantity',
        'unit_price',
        'total_price',
        'cow_type',
        'cow_age',
        'heat_started_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'heat_started_at' => 'datetime',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the bull semen that owns the order item.
     */
    public function bullSemen()
    {
        return $this->belongsTo(BullSemen::class);
    }

    /**
     * Get the cow insemination records for the order item.
     */
    public function cowInseminationRecords()
    {
        return $this->hasMany(CowInseminationRecord::class);
    }
}