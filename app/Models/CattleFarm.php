<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CattleFarm extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'name',
        'father_name',
        'picture',
        'latitude',
        'longitude',
        'number_of_cows',
        'number_of_calves',
        'number_of_bulls',
        'number_of_goats',
    ];

    /**
     * Get the farmer that owns the cattle farm.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Get the orders for the cattle farm.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}