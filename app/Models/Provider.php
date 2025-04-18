<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_type',
        'tax_id',
        'email',
        'phone',
        'profile_photo',
        'birth_date',
        'foundation_date',
        'status',
        'provider_description',
        'service_category',
        'service_description',
        'work_radius',
        'availability',
    ];

    /**
     * Relação muitos-para-muitos com Address.
     */
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_provider');
    }

    /**
     * Relação um-para-muitos com Portfolio.
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }
}
