<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Relação um-para-muitos com Service.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Relação de muitos-para-muitos com Providers
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'category_provider')->withTimestamps();
    }

}