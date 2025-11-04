<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'date_of_birth',
        'profile_picture',
        'status',
    ];

   

    /**
     * Get the user that owns the farmer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cattle farms for the farmer.
     */
    public function cattleFarms()
    {
        return $this->hasMany(CattleFarm::class);
    }

    /**
     * Get the orders for the farmer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the payments for the farmer.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the reviews written by the farmer.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}