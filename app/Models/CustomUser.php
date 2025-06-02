<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_type',
        'tax_id', 
        'email',
        'phone',
        'password',
        'birth_date',
        'foundation_date',
        'status',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'availability' => 'string',
        'birth_date' => 'date',
        'foundation_date' => 'date'
    ];

    /**
     * Relação muitos-para-muitos com Address.
     */
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_custom_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Relação um-para-muitos com ServiceRequest
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Relação de um-para-muitos com Review.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}