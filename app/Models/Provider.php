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
        'work_radius',
        'availability',
    ];

    // Adicione isso para ocultar o password nos arrays/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'availability' => 'string',
        'status' => 'boolean',
        'birth_date' => 'date',
        'foundation_date' => 'date',
    ];

    /**
     * Metódo auxilar para verificar a viabilidade de um prestador nos finais de semana.
     */
    public function isAvailableOnWeekends()
    {
        return $this->availability === 'weekends' || $this->availability === 'both';
    }

    /**
     * Relação muitos-para-muitos com Address.
     */
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_provider')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Relação um-para-muitos com Portfolio.
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Relação de muitos-para-muitos com ServiceCategory.
     */
    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'category_provider')->withTimestamps();
    }

    /**
     * Relação de um-para-muitos com Review.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relação de um-para-muitos com ServiceRequest.
     * O Provider recebe várias solicitações.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }


    /**
     * Método para buscar a avaliação média do Provider.
     */
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

}
