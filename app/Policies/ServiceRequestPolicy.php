<?php

namespace App\Policies;

use App\Models\Provider;
use App\Models\ServiceRequest;
use Illuminate\Auth\Access\Response;

class ServiceRequestPolicy
{
    /**
     * Determine whether the provider can view quaisquer pedidos.
     */
    public function viewAny(Provider $provider): bool
    {
        // Ex.: todo provider autenticado pode ver sua lista de requests
        return true;
    }

    /**
     * Determine whether the provider can view um pedido específico.
     */
    public function view(Provider $provider, ServiceRequest $serviceRequest): bool
    {
        // Só quem é dono daquele request (provider_id) pode ver
        return $provider->id === $serviceRequest->provider_id;
    }

    /**
     * Determine whether o provider pode criar um request.
     * (Geralmente quem cria é o CustomUser, então bloqueamos aqui.)
     */
    public function create(Provider $provider): bool
    {
        return false;
    }

    /**
     * Determine whether o provider pode atualizar (aceitar/rejeitar) o request.
     */
    public function update(Provider $provider, ServiceRequest $serviceRequest): bool
    {
        // Só o dono do request (provider) pode mudar o status
        return $provider->id === $serviceRequest->provider_id;
    }

    /**
     * Determine whether o provider pode deletar o request.
     */
    public function delete(Provider $provider, ServiceRequest $serviceRequest): bool
    {
        // Por exemplo, só o dono pode apagar um request
        return $provider->id === $serviceRequest->provider_id;
    }

    /**
     * Determine whether o provider pode restaurar o request.
     */
    public function restore(Provider $provider, ServiceRequest $serviceRequest): bool
    {
        return false;
    }

    /**
     * Determine whether o provider pode apagar permanentemente (forceDelete).
     */
    public function forceDelete(Provider $provider, ServiceRequest $serviceRequest): bool
    {
        return false;
    }
}

