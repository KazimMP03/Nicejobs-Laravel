<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Armazena uma avaliação bilateral (Provider ou CustomUser) vinculada a uma ServiceRequest.
     */
    public function store(Request $request, $serviceRequestId)
    {
        // Busca a ServiceRequest
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);

        // Identificar o usuário autenticado (Provider ou CustomUser)
        $user = auth('web')->user() ?? auth('custom')->user();
        $isProvider = $user instanceof \App\Models\Provider;
        $isCustomUser = $user instanceof \App\Models\CustomUser;

        // Validar se o usuário tem permissão para avaliar
        if (!$isProvider && !$isCustomUser) {
            abort(403, 'Acesso não autorizado.');
        }

        // Verificar se a solicitação permite avaliação
        // Ex: Apenas quando está aceita ou finalizada
        if (!$serviceRequest->isAccepted() && !$serviceRequest->isCompleted()) {
            return redirect()->back()->with('error', 'Este serviço ainda não pode ser avaliado.');
        }

        // Impedir avaliações duplicadas para este service request pelo mesmo reviewer
        $alreadyReviewed = Review::where('service_request_id', $serviceRequest->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewer_type', $isProvider ? 'provider' : 'custom_user')
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('warning', 'Você já avaliou esta solicitação.');
        }

        // Validação dos dados enviados no formulário
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Determinar o "alvo" da avaliação (Provider ou CustomUser)
        // Se for CustomUser avaliando → alvo é Provider
        // Se for Provider avaliando → alvo é CustomUser
        $review = new Review([
            'service_request_id' => $serviceRequest->id,
            'rating'             => $data['rating'],
            'comment'            => $data['comment'] ?? null,
            'reviewer_id'        => $user->id,
            'reviewer_type'      => $isProvider ? 'provider' : 'custom_user',
            'provider_id'        => $isCustomUser ? $serviceRequest->provider_id : null,
            'custom_user_id'     => $isProvider ? $serviceRequest->custom_user_id : null,
        ]);

        // Salvar a avaliação no banco de dados
        $review->save();

        // Redirecionar com mensagem de sucesso
        return redirect()->back()->with('success', 'Avaliação registrada com sucesso!');
    }
}
