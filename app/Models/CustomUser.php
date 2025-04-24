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
        'availability'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'availability' => 'array',
        'birth_date' => 'date',
        'foundation_date' => 'date'
    ];

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_custom_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }
}