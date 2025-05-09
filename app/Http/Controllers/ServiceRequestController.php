<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Lista todas as solicitações feitas pelo usuário logado.
     * Mostra solicitações recebidas se for um Provider,
     * ou enviadas se for um CustomUser.
     */
    public function index()
    {
        $user = auth()->user();

        $requests = $user instanceof \App\Models\CustomUser
            ? ServiceRequest::where('custom_user_id', $user->id)->with('service')->get()
            : ServiceRequest::where('provider_id', $user->id)->with('service')->get();

        return view('service_requests.index', compact('requests'));
    }

    /**
     * Exibe o formulário para solicitar um serviço.
     */
    public function create(Service $service)
    {
        return view('service_requests.create', compact('service'));
    }

    /**
     * Armazena uma nova solicitação de serviço no sistema.
     */
    public function store(Request $request, Service $service)
    {
        // Validação da mensagem opcional
        $data = $request->validate([
            'message' => 'nullable|string',
        ]);

        // Cria a solicitação com status "pending"
        ServiceRequest::create([
            'custom_user_id' => auth()->id(),
            'service_id'     => $service->id,
            'provider_id'    => $service->provider_id,
            'status'         => ServiceRequest::STATUS_PENDING,
            'message'        => $data['message'] ?? null,
        ]);

        return redirect()->route('service-requests.index')
                         ->with('success', 'Serviço solicitado com sucesso!');
    }

    /**
     * Exibe os detalhes de uma solicitação específica.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);

        return view('service_requests.show', compact('serviceRequest'));
    }

    /**
     * Atualiza o status de uma solicitação (aceitar, rejeitar, cancelar).
     * A negociação de preço pode ser feita via chat após a aceitação.
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        // Valida o status permitido
        $data = $request->validate([
            'status'  => 'required|in:' . implode(',', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_REJECTED,
                'cancelled' // status manual, não constante
            ]),
            'message' => 'nullable|string',
        ]);

        // Atualiza a solicitação
        $serviceRequest->update($data);

        return redirect()->route('service-requests.index')
                         ->with('success', 'Status da solicitação atualizado!');
    }

    /**
     * Remove uma solicitação de serviço do sistema.
     * Geralmente usado apenas por quem criou a solicitação.
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);

        $serviceRequest->delete();

        return back()->with('success', 'Solicitação removida.');
    }
}
