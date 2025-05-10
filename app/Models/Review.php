<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'custom_user_id',
        'rating',
        'comment',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function customUser()
    {
        return $this->belongsTo(CustomUser::class);
    }
}
