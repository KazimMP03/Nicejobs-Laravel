<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'provider_id',
        'custom_user_id',
        'reviewer_id',
        'reviewer_type',
        'rating',
        'comment',
    ];

    // Quem foi avaliado (provider)
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    // Quem foi avaliado (custom user)
    public function customUser()
    {
        return $this->belongsTo(CustomUser::class);
    }

    // A qual service request esta review pertence
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    // Quem fez a avaliação (polimórfico manual)
    public function reviewer()
    {
        return $this->morphTo(__FUNCTION__, 'reviewer_type', 'reviewer_id');
    }
}
