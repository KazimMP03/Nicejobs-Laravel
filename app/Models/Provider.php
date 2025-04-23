<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_type',
        'tax_id',
        'email',
        'phone',
        'password',
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

    // Adicione isso para ocultar o password nos arrays/JSON
    protected $hidden = [
        'password',
        'remember_token',
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
