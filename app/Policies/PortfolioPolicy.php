<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\Provider;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o Provider pode atualizar o portfólio.
     */
    public function update(Provider $provider, Portfolio $portfolio)
    {
        return $portfolio->provider_id === $provider->id;
    }

    /**
     * Determina se o Provider pode deletar o portfólio.
     */
    public function delete(Provider $provider, Portfolio $portfolio)
    {
        return $portfolio->provider_id === $provider->id;
    }

    /**
     * (Opcional) Determina se o Provider pode visualizar o portfólio.
     */
    public function view(Provider $provider, Portfolio $portfolio)
    {
        return $portfolio->provider_id === $provider->id;
    }
}
