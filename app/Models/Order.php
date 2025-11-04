<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'cattle_farm_id',
        'order_number',
        'status',
        'total_amount',
        'visit_fee',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'visit_fee' => 'decimal:2',
    ];

    /**
     * Get the farmer that owns the order.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Get the cattle farm that owns the order.
     */
    public function cattleFarm()
    {
        return $this->belongsTo(CattleFarm::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the jobs for the order.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the payments for the order.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}