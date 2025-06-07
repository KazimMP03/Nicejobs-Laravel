@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}">

<div class="container">

    {{-- Cabeçalho --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik',sans-serif;">
            MINHA SOLICITAÇÃO
        </h3>
    </div>

    {{-- Seção: Dados do Serviço --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
       @php
            // Mapeamento de status para label e cor
            switch ($serviceRequest->status) {
                case 'requested':
                    $badgeClass = 'bg-secondary';
                    $label      = 'Solicitado';
                    break;
                case 'chat_opened':
                    $badgeClass = 'bg-info';
                    $label      = 'Chat Aberto';
                    break;
                case 'pending_acceptance':
                    $badgeClass = 'bg-warning text-dark';
                    $label      = 'Pendente de Aceitar';
                    break;
                case 'accepted':
                    $badgeClass = 'bg-success';
                    $label      = 'Aceito';
                    break;
                case 'rejected':
                    $badgeClass = 'bg-danger';
                    $label      = 'Recusado';
                    break;
                case 'cancelled':
                    $badgeClass = 'bg-danger';
                    $label      = 'Cancelado';
                    break;
                case 'completed':
                    $badgeClass = 'bg-success';
                    $label      = 'Concluído';
                    break;
                default:
                    $badgeClass = 'bg-light text-dark';
                    $label      = ucfirst(str_replace('_', ' ', $serviceRequest->status));
            }
        @endphp

        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="fw-bold text-primary border-start border-4 border-primary ps-3 text-uppercase mb-0">
                Dados do Serviço
            </h4>
            <span class="badge fs-6 {{ $badgeClass }}">
                {{ $label }}
            </span>
        </div>

        <div class="card border-0 bg-white">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">
                    {{ $label }}: <span class="text-dark">
                        {{ auth()->user() instanceof \App\Models\Provider 
                            ? $serviceRequest->customUser->user_name 
                            : $serviceRequest->provider->user_name }}
                    </span>
                </h5>

                <p class="mb-3"><strong>Descrição:</strong> {{ $serviceRequest->description }}</p>
                <p class="mb-3"><strong>Orçamento Inicial:</strong>
                    R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}
                </p>

                @if($serviceRequest->final_price)
                    <p class="mb-3"><strong>Valor Final Proposto:</strong>
                        R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}
                    </p>
                @endif

                @if($serviceRequest->service_date)
                    <p class="mb-0"><strong>Data do Serviço:</strong>
                        {{ \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y') }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- Seção: Endereço do Serviço --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Endereço do Serviço
        </h4>
        <div class="card border-0 bg-white">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">
                    <span class="text-dark">{{ $serviceRequest->address->logradouro }}, {{ $serviceRequest->address->numero }}</span>
                </h5>
                <p class="mb-1">{{ $serviceRequest->address->bairro }} - {{ $serviceRequest->address->cidade }}/{{ $serviceRequest->address->estado }}</p>
                <p class="mb-1">CEP: {{ $serviceRequest->address->cep }}</p>
                @if($serviceRequest->address->complemento)
                    <p class="mb-0">Complemento: {{ $serviceRequest->address->complemento }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Seção: Ações Disponíveis --}}
    @if(!$serviceRequest->isFinalized())
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Ações Disponíveis
        </h4>
        <div class="d-flex justify-content-center flex-wrap">

            @if($serviceRequest->canAcceptProposal())
                <form action="{{ route('service-requests.accept-proposal', $serviceRequest) }}" method="POST" class="me-3 mb-2">
                    @csrf @method('PUT')
                    <button class="btn btn-success fw-bold" style="margin-right: 50px;">
                        <i class="fas fa-check me-2"></i> Aceitar
                    </button>
                </form>
            @endif

            @if($serviceRequest->canRejectProposal())
                <form action="{{ route('service-requests.reject-proposal', $serviceRequest) }}" method="POST" class="me-3 mb-2">
                    @csrf @method('PUT')
                    <button class="btn btn-cancel fw-bold" style="margin-right: 50px;">
                        <i class="fas fa-times me-2"></i> Recusar
                    </button>
                </form>
            @endif

            @if($serviceRequest->isAccepted())
                <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST" class="me-3 mb-2">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button class="btn btn-sucess fw-bold" style="margin-right: 50px;">
                        <i class="fas fa-check-circle me-2"></i> Concluir
                    </button>
                </form>
            @endif

            @if($serviceRequest->canCancel())
                <form action="{{ route(auth()->user() instanceof \App\Models\Provider 
                            ? 'provider.service-requests.cancel' 
                            : 'custom-user.service-requests.cancel', 
                            $serviceRequest) }}" method="POST" class="me-3 mb-2">
                    @csrf @method('PUT')
                    <button class="btn btn-danger fw-bold" style="margin-right: 50px;">
                        <i class="fas fa-arrow-left me-2"></i> Cancelar
                    </button>
                </form>
            @endif

            @if($serviceRequest->chat)
                <a href="{{ route('chat.show', $serviceRequest->id) }}" 
                class="btn btn-info fw-bold" 
                style="margin-right: 50px;">
                    <i class="fas fa-comments me-2"></i> Abrir Chat
                </a>
            @endif

        </div>
    </section>
    @endif

    {{-- Seção: Avaliação --}}
    @if($serviceRequest->isCompleted() && !$serviceRequest->wasReviewedBy(auth()->user()))
    <section class="mb-5 p-4 shadow-sm rounded bg-white text-center">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Avaliação
        </h4>
        <a href="{{ route('service-requests.review', $serviceRequest) }}" class="btn btn-outline-primary fw-bold">
            <i class="fas fa-star me-1"></i>
            Avaliar {{ auth()->user() instanceof \App\Models\Provider ? 'Cliente' : 'Prestador' }}
        </a>
    </section>
    @endif

</div>
@endsection
