@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Minha Solicitação</h2>

    {{-- Dados do serviço --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Prestador: <strong>{{ $serviceRequest->provider->user_name }}</strong></h5>
            <p><strong>Descrição:</strong> {{ $serviceRequest->description }}</p>
            <p><strong>Orçamento Inicial:</strong> R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}</p>
            @if($serviceRequest->final_price)
                <p><strong>Valor Final Proposto:</strong> R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}</p>
            @endif
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ 
                    $serviceRequest->isRequested() ? 'secondary' : 
                    ($serviceRequest->isChatOpened() ? 'info' : 
                    ($serviceRequest->isPendingAcceptance() ? 'warning' : 
                    ($serviceRequest->isAccepted() ? 'success' : 'dark'))) }}">
                    {{ ucfirst($serviceRequest->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- Endereço --}}
    <div class="card mb-4">
        <div class="card-header">Endereço do Serviço</div>
        <div class="card-body">
            <p>{{ $serviceRequest->address->logradouro }}, {{ $serviceRequest->address->numero }}</p>
            <p>{{ $serviceRequest->address->bairro }} - {{ $serviceRequest->address->cidade }}/{{ $serviceRequest->address->estado }}</p>
            <p>CEP: {{ $serviceRequest->address->cep }}</p>
            @if($serviceRequest->address->complemento)
                <p>Complemento: {{ $serviceRequest->address->complemento }}</p>
            @endif
        </div>
    </div>

    {{-- Ações --}}
    @if(!$serviceRequest->isFinalized())
        <div class="d-flex gap-2 flex-wrap">

            {{-- Aceitar Proposta --}}
            @if($serviceRequest->canAcceptProposal())
                <form action="{{ route('service-requests.accept-proposal', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success">Aceitar Proposta</button>
                </form>
            @endif

            {{-- Recusar Proposta --}}
            @if($serviceRequest->canRejectProposal())
                <form action="{{ route('service-requests.reject-proposal', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-warning">Recusar Proposta</button>
                </form>
            @endif

            {{-- Concluir (Aparece apenas se está aceito) --}}
            @if($serviceRequest->isAccepted())
                <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button class="btn btn-primary">Concluir Serviço</button>
                </form>
            @endif

            {{-- Cancelar --}}
            @if($serviceRequest->canCancel())
                <form action="{{ route('custom-user.service-requests.cancel', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-outline-warning">Cancelar</button>
                </form>
            @endif

            {{-- Ir para o Chat --}}
            @if($serviceRequest->chat)
                <a href="{{ route('chat.show', $serviceRequest->id) }}" class="btn btn-secondary">
                    Ir para o Chat
                </a>
            @endif

        </div>
    @else
        <div class="alert alert-info">
            Esta solicitação está {{ ucfirst($serviceRequest->status) }}.
        </div>
    @endif
</div>
@endsection
