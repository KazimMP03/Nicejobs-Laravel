<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Provider;
use App\Models\CustomUser;
use App\Models\Chat;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Lista as ServiceRequests do usuário logado (Provider ou CustomUser).
     */
    public function index()
    {
        $user = auth()->user();

        if ($user instanceof Provider) {
            $requests = ServiceRequest::where('provider_id', $user->id)
                ->with(['customUser', 'address'])
                ->get();

            return view('service_requests.provider.index', compact('requests'));
        }

        if ($user instanceof CustomUser) {
            $requests = ServiceRequest::where('custom_user_id', $user->id)
                ->with(['provider', 'address'])
                ->get();

            return view('service_requests.custom_user.index', compact('requests'));
        }

        abort(403, 'Acesso não autorizado.');
    }

    /**
     * Exibe o formulário para criar uma nova ServiceRequest.
     */
    public function create(Provider $provider)
    {
        $user = auth()->user();

        $addresses = $user->addresses()->withPivot('is_default')->get();

        return view('service_requests.create', compact('provider', 'addresses'));
    }

    /**
     * Armazena uma nova ServiceRequest.
     */
    public function store(Request $request, Provider $provider)
    {
        $user = auth()->user();

        $data = $request->validate([
            'description'     => 'required|string',
            'initial_budget'  => 'required|numeric|min:0',
            'address_id'      => 'nullable|exists:addresses,id',
        ]);

        // Se não selecionou endereço → usa o padrão
        if (empty($data['address_id'])) {
            $defaultAddress = $user->addresses()->wherePivot('is_default', true)->first();

            if (!$defaultAddress) {
                return back()->withErrors(['address_id' => 'É necessário ter um endereço cadastrado.']);
            }

            $data['address_id'] = $defaultAddress->id;
        }

        ServiceRequest::create([
            'custom_user_id' => $user->id,
            'provider_id'    => $provider->id,
            'address_id'     => $data['address_id'],
            'description'    => $data['description'],
            'initial_budget' => $data['initial_budget'],
            'status'         => ServiceRequest::STATUS_REQUESTED,
        ]);

        return redirect()->route('custom-user.service-requests.index')
                         ->with('success', 'Solicitação enviada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma ServiceRequest (para Provider ou CustomUser).
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if ($user->id !== $serviceRequest->custom_user_id && $user->id !== $serviceRequest->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Renderiza a view correta conforme o tipo de usuário
        if ($user instanceof Provider) {
            return view('service_requests.provider.show', compact('serviceRequest'));
        }

        if ($user instanceof CustomUser) {
            return view('service_requests.custom_user.show', compact('serviceRequest'));
        }

        abort(403, 'Acesso não autorizado.');
    }

    /**
     * Provider atualiza status da ServiceRequest (chat_opened, rejected, accepted, completed).
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        if (!$provider instanceof Provider || $serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'status'       => 'required|in:chat_opened,rejected,accepted,completed',
            'final_price'  => 'nullable|numeric|min:0',
        ]);

        if ($data['status'] === ServiceRequest::STATUS_ACCEPTED && empty($data['final_price'])) {
            return back()->withErrors(['final_price' => 'É necessário definir o valor final ao aceitar.']);
        }

        if ($data['status'] === ServiceRequest::STATUS_ACCEPTED) {
            $serviceRequest->final_price = $data['final_price'];
        }

        $serviceRequest->status = $data['status'];
        $serviceRequest->save();

        // Cria o Chat se status for 'chat_opened'
        if ($data['status'] === ServiceRequest::STATUS_CHAT_OPENED) {
            Chat::firstOrCreate([
                'service_request_id' => $serviceRequest->id
            ]);
        }

        return redirect()->route('service-requests.index')
                         ->with('success', 'Status atualizado com sucesso.');
    }

    /**
     * CustomUser pode cancelar sua própria solicitação.
     */
    public function cancel(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user instanceof CustomUser || $serviceRequest->custom_user_id !== $user->id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!in_array($serviceRequest->status, [ServiceRequest::STATUS_REQUESTED, ServiceRequest::STATUS_CHAT_OPENED])) {
            return back()->with('error', 'Esta solicitação não pode mais ser cancelada.');
        }

        $serviceRequest->status = ServiceRequest::STATUS_CANCELLED;
        $serviceRequest->save();

        return redirect()->route('custom-user.service-requests.index')
                         ->with('success', 'Solicitação cancelada.');
    }
}
