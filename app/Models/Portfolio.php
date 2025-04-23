<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'media_path',
    ];

    /**
     * Relação de pertencimento a Provider.
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
