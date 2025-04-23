<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'cep',
        'logradouro',
        'bairro',
        'numero',
        'cidade',
        'estado',
        'complemento',
    ];

    /**
     * Relação muitos-para-muitos com CustomUser.
     */
    public function customUsers()
    {
        return $this->belongsToMany(CustomUser::class, 'address_custom_user');
    }

    /**
     * Relação muitos-para-muitos com Provider.
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'address_provider');
    }
}
