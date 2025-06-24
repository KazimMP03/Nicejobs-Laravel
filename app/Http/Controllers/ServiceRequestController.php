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
     * Lista as solicitações do usuário autenticado, com filtro e ordenação por status.
     */
    public function index(Request $request)
    {
        $user         = auth()->user();
        $statusFilter = $request->input('status', 'all');

        // 1) Obter arrays estáticos do Model
        $orderList = ServiceRequest::statusOrder();    // ex: ['requested','chat_opened',...]
        $labelMap  = ServiceRequest::statusLabels();   // ex: ['requested'=>'Solicitado', ...]

        // 2) Montar expressão CASE WHEN para ordenação por status (PostgreSQL)
        $cases = [];
        foreach ($orderList as $idx => $st) {
            // Como st vem de constantes fixas, sem risco de injeção externa
            $cases[] = "WHEN status = '{$st}' THEN {$idx}";
        }
        $elseIndex      = count($orderList);
        $caseExpression = "(CASE " . implode(' ', $cases) . " ELSE {$elseIndex} END)";

        // 3) Iniciar query base filtrando por provider ou custom user
        if ($user instanceof Provider) {
            $query = ServiceRequest::where('provider_id', $user->id);
        } elseif ($user instanceof CustomUser) {
            $query = ServiceRequest::where('custom_user_id', $user->id);
        } else {
            abort(403, 'Acesso não autorizado.');
        }

        // 4) Aplicar filtro de status conforme $statusFilter
        if ($statusFilter === 'active') {
            // Ajuste conforme necessidade de negócio: quais status são considerados “ativos”?
            // Exemplo incluindo solicitações ainda em negociação:
            $query->whereIn('status', [
                ServiceRequest::STATUS_REQUESTED,      // opcional: inclua se quiser ver solicitações sem chat inicial
                ServiceRequest::STATUS_CHAT_OPENED,
                ServiceRequest::STATUS_PENDING_ACCEPT,
                ServiceRequest::STATUS_ACCEPTED,
            ]);
        } elseif ($statusFilter === 'archived') {
            $query->whereIn('status', [
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_CANCELLED,
                ServiceRequest::STATUS_REJECTED,
            ]);
        } elseif (in_array($statusFilter, $orderList, true)) {
            // Status específico selecionado
            $query->where('status', $statusFilter);
        }
        // Se 'all' ou valor inválido, não aplica filtro de status: lista tudo do usuário.

        // 5) Ordenar por status conforme CASE, depois por created_at desc (ou outro critério)
        $query->orderByRaw($caseExpression)
              ->orderBy('created_at', 'desc');

        // 6) Carregar relações necessárias para evitar N+1
        if ($user instanceof Provider) {
            $requests = $query->with(['customUser', 'address'])->get();
        } else {
            $requests = $query->with(['provider', 'address'])->get();
        }

        // 7) Passar variáveis para a view: requests, status atual, e listas para select
        //    As views devem usar $statusOrder e $statusLabels para montar o dropdown de filtros.
        if ($user instanceof Provider) {
            return view('service_requests.provider.index', [
                'requests'     => $requests,
                'status'       => $statusFilter,
                'statusOrder'  => $orderList,
                'statusLabels' => $labelMap,
            ]);
        } else {
            return view('service_requests.custom_user.index', [
                'requests'     => $requests,
                'status'       => $statusFilter,
                'statusOrder'  => $orderList,
                'statusLabels' => $labelMap,
            ]);
        }
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
            'description'    => 'required|string',
            'initial_budget' => 'required|numeric|min:0',
            'address_id'     => 'nullable|exists:addresses,id',
        ]);

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
     * Exibe os detalhes da ServiceRequest.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if ($user->id !== $serviceRequest->custom_user_id && $user->id !== $serviceRequest->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($user instanceof Provider) {
            return view('service_requests.provider.show', compact('serviceRequest'));
        }

        if ($user instanceof CustomUser) {
            return view('service_requests.custom_user.show', compact('serviceRequest'));
        }

        abort(403, 'Acesso não autorizado.');
    }

    /**
     * Provider propõe o valor final e a data do serviço.
     */
    public function proposePrice(Request $request, ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        if (!$provider instanceof Provider || $serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$serviceRequest->canPropose()) {
            return back()->with('error', 'Não é possível propor um valor neste momento.');
        }

        $data = $request->validate([
            'final_price'  => 'required|numeric|min:0',
            'service_date' => 'required|date|after_or_equal:today',
        ]);

        $serviceRequest->update([
            'final_price'        => $data['final_price'],
            'service_date'       => $data['service_date'],
            'provider_accepted'  => true,
            'customer_accepted'  => false,
            'status'             => ServiceRequest::STATUS_PENDING_ACCEPT,
        ]);

        return back()->with('success', 'Proposta enviada com sucesso. Aguardando aceite do cliente.');
    }

    /**
     * Cliente aceita a proposta (valor + data).
     */
    public function acceptProposal(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user instanceof CustomUser || $serviceRequest->custom_user_id !== $user->id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$serviceRequest->canAcceptProposal()) {
            return back()->with('error', 'Não é possível aceitar essa proposta no momento.');
        }

        $serviceRequest->customer_accepted = true;
        $serviceRequest->trySetAccepted();

        return back()->with('success', 'Proposta aceita e serviço confirmado.');
    }

    /**
     * Cliente recusa a proposta.
     */
    public function rejectProposal(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user instanceof CustomUser || $serviceRequest->custom_user_id !== $user->id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$serviceRequest->canRejectProposal()) {
            return back()->with('error', 'Não é possível recusar essa proposta no momento.');
        }

        $serviceRequest->resetProposal();

        return back()->with('success', 'Proposta recusada. Continue negociando no chat.');
    }

    /**
     * Atualiza status: abrir chat, rejeitar, concluir.
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!($user->id === $serviceRequest->provider_id || $user->id === $serviceRequest->custom_user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'status' => 'required|in:chat_opened,rejected,completed',
        ]);

        $status = $data['status'];

        if ($status === ServiceRequest::STATUS_CHAT_OPENED) {
            if ($serviceRequest->isFinalized()) {
                return back()->with('error', 'Não é possível abrir chat em uma solicitação finalizada.');
            }
            $serviceRequest->status = ServiceRequest::STATUS_CHAT_OPENED;
            Chat::firstOrCreate(['service_request_id' => $serviceRequest->id]);
        }

        if ($status === ServiceRequest::STATUS_REJECTED) {
            if (!$serviceRequest->canReject()) {
                return back()->with('error', 'Não é possível rejeitar esta solicitação neste status.');
            }
            $serviceRequest->status = ServiceRequest::STATUS_REJECTED;
        }

        if ($status === ServiceRequest::STATUS_COMPLETED) {
            if (!$serviceRequest->canComplete()) {
                return back()->with('error', 'Só é possível concluir uma solicitação aceita.');
            }
            if (!($user instanceof CustomUser)) {
                return back()->with('error', 'Apenas o cliente pode concluir o serviço.');
            }
            $serviceRequest->status = ServiceRequest::STATUS_COMPLETED;
        }

        $serviceRequest->save();

        return back()->with('success', 'Status atualizado com sucesso.');
    }

    /**
     * Cliente cancela a solicitação.
     */
    public function cancel(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user instanceof CustomUser || $serviceRequest->custom_user_id !== $user->id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$serviceRequest->canCancel()) {
            return back()->with('error', 'Esta solicitação não pode mais ser cancelada.');
        }

        $serviceRequest->status = ServiceRequest::STATUS_CANCELLED;
        $serviceRequest->save();

        return redirect()->route('custom-user.service-requests.index')
                         ->with('success', 'Solicitação cancelada.');
    }
}
