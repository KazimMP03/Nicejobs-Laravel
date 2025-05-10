<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    /**
     * Página inicial de exploração de prestadores.
     * Lista todas as categorias disponíveis para filtro.
     */
    public function index()
    {
        $categories = ServiceCategory::all();

        return view('explore.index', compact('categories'));
    }

    /**
     * Lista os prestadores que atuam na categoria selecionada.
     * Mesmo que o provider não tenha serviços, será listado se estiver vinculado à categoria.
     */
    public function byCategory($id)
    {
        $category = ServiceCategory::findOrFail($id);

        // Carrega os providers vinculados a essa categoria com portfólio e (futuro) avaliação
        $providers = $category->providers()
            ->with(['portfolios'])
            // ->withAvg('reviews', 'rating') // ← ativar quando Review for implementado
            ->get();

        return view('explore.providers', compact('category', 'providers'));
    }

    /**
     * Exibe o perfil público de um provider com seus dados, portfólios e avaliações.
     */
    public function showProvider($id)
    {
        $provider = Provider::with([
            'categories',
            'portfolios',
            // 'reviews.customUser', // ← ativar quando Review estiver implementado
        ])->findOrFail($id);

        return view('providers.show', compact('provider'));
    }
}
