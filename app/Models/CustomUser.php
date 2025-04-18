<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomUser extends Model
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
    ];

    /**
     * Relação muitos-para-muitos com Address.
     */
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_custom_user');
    }
}
