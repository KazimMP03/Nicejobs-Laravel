<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Armazena uma nova avaliação de um Provider feita por um CustomUser.
     */
    public function store(Request $request, $providerId)
    {
        // Garante que apenas CustomUsers autenticados possam avaliar
        $user = Auth::guard('custom')->user();
        if (!$user) {
            return redirect()->back()->with('error', 'Apenas clientes podem enviar avaliações.');
        }

        // Verifica se o Provider existe
        $provider = Provider::findOrFail($providerId);

        // Impede múltiplas avaliações do mesmo usuário
        if ($provider->reviews()->where('custom_user_id', $user->id)->exists()) {
            return redirect()->back()->with('warning', 'Você já avaliou este prestador.');
        }

        // Valida os dados do formulário
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Cria a avaliação
        Review::create([
            'provider_id'    => $provider->id,
            'custom_user_id' => $user->id,
            'rating'         => $validated['rating'],
            'comment'        => $validated['comment'] ?? null,
        ]);

        return redirect()->route('providers.show', $provider->id)
                         ->with('success', 'Avaliação enviada com sucesso!');
    }
}
