<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Exibe o formulário de avaliação.
     */
    public function create(ServiceRequest $serviceRequest)
    {
        // Verifica se o usuário está autenticado como Provider ou CustomUser
        $user = auth('web')->user() ?? auth('custom')->user();
        $isProvider = $user instanceof \App\Models\Provider;
        $isCustomUser = $user instanceof \App\Models\CustomUser;

        if (!$isProvider && !$isCustomUser) {
            abort(403, 'Acesso não autorizado.');
        }

        // Valida se o usuário tem relação com a serviceRequest
        if (
            ($isProvider && $serviceRequest->provider_id !== $user->id) ||
            ($isCustomUser && $serviceRequest->custom_user_id !== $user->id)
        ) {
            abort(403, 'Acesso não autorizado.');
        }

        // Verifica se a solicitação está apta para avaliação (após ser concluída)
        if (!$serviceRequest->isCompleted()) {
            return redirect()->back()->with('error', 'Este serviço ainda não pode ser avaliado.');
        }

        // Impede avaliação duplicada
        $alreadyReviewed = Review::where('service_request_id', $serviceRequest->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewer_type', $isProvider ? 'provider' : 'custom_user')
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('warning', 'Você já avaliou esta solicitação.');
        }

        // Exibe a view de avaliação
        return view('reviews.create', compact('serviceRequest'));
    }

    /**
     * Armazena uma avaliação.
     */
    public function store(Request $request, $serviceRequestId)
    {
        // Busca a ServiceRequest
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);

        // Identifica o usuário
        $user = auth('web')->user() ?? auth('custom')->user();
        $isProvider = $user instanceof \App\Models\Provider;
        $isCustomUser = $user instanceof \App\Models\CustomUser;

        if (!$isProvider && !$isCustomUser) {
            abort(403, 'Acesso não autorizado.');
        }

        // Verifica se a solicitação permite avaliação
        if (!$serviceRequest->isCompleted()) {
            return redirect()->back()->with('error', 'Este serviço ainda não pode ser avaliado.');
        }

        // Impede avaliações duplicadas
        $alreadyReviewed = Review::where('service_request_id', $serviceRequest->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewer_type', $isProvider ? 'provider' : 'custom_user')
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('warning', 'Você já avaliou esta solicitação.');
        }

        // Validação dos dados
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Cria a review
        Review::create([
            'service_request_id' => $serviceRequest->id,
            'rating'             => $data['rating'],
            'comment'            => $data['comment'] ?? null,
            'reviewer_id'        => $user->id,
            'reviewer_type'      => $isProvider ? 'provider' : 'custom_user',
            'provider_id'        => $isCustomUser ? $serviceRequest->provider_id : null,
            'custom_user_id'     => $isProvider ? $serviceRequest->custom_user_id : null,
        ]);

        return redirect()->route('home')->with('success', 'Avaliação registrada com sucesso!');
    }
}
