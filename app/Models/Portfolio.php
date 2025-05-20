<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['provider_id',
                           'title',
                           'description',
                           'media_paths'];

    protected $casts = [
        'media_paths' => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
