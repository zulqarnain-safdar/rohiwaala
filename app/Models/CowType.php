<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CowType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scope for active cow types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
