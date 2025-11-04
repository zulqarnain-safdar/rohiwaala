<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BullSemen extends Model
{
    use HasFactory;

    protected $table = 'bull_semen';

    protected $fillable = [
        'breed_id',
        'technician_id',
        'name',
        'description',
        'image',
        'price',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['bull_name'];

    /**
     * Get the bull name attribute.
     */
    public function getBullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the breed that owns the semen.
     */
    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    /**
     * Get the technicians that manage the semen.
     */
    public function technicians()
    {
        return $this->belongsToMany(Technician::class, 'bull_semen_technician');
    }

    /**
     * Get the order items for the semen.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}