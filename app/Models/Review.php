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

    // === Método auxiliar para descobrir o nome do avaliador === //
    public function getReviewerNameAttribute()
    {
        if ($this->reviewer_type === 'provider') {
            return \App\Models\Provider::find($this->reviewer_id)?->user_name ?? 'Usuário';
        }

        if ($this->reviewer_type === 'custom_user') {
            return \App\Models\CustomUser::find($this->reviewer_id)?->user_name ?? 'Usuário';
        }

        return 'Usuário';
    }

    // === Método auxiliar para verificar avaliações === //

    /**
     * Verifica se existe uma avaliação entre um reviewer (quem avaliou) e um alvo (Provider ou CustomUser)
     */
    public static function wasReviewedBy($reviewerId, $reviewerType, $providerId = null, $customUserId = null)
    {
        return self::where('reviewer_id', $reviewerId)
            ->where('reviewer_type', $reviewerType)
            ->where('provider_id', $providerId)
            ->where('custom_user_id', $customUserId)
            ->exists();
    }
}
