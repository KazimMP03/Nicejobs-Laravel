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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary border-start border-4 border-primary ps-3 text-uppercase mb-0">
                Dados do Serviço
            </h4>
            <span class="badge fs-6 {{ $badgeClass }}">
                {{ $label }}
            </span>
        </div>

        {{-- Novo bloco estilizado do Solicitante --}}
        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded shadow-sm border-start border-4 border-info">
            @php
                $currentUser = auth()->user();
                // Se for Provider, pega o CustomUser; senão, pega o Provider
                $otherUser = $currentUser instanceof \App\Models\Provider
                            ? $serviceRequest->customUser
                            : $serviceRequest->provider;

                // Se tiver foto de perfil armazenada, usa ela; caso contrário, fallback genérico
                $avatarUrl = $otherUser && $otherUser->profile_photo
                            ? asset('storage/' . $otherUser->profile_photo)
                            : asset('images/user.png');
            @endphp

            <img
                src="{{ $avatarUrl }}"
                alt="Avatar de {{ $otherUser->user_name ?? 'Usuário' }}"
                class="rounded-circle border border-2 border-light"
                style="width: 40px; height: 40px; object-fit: cover; margin-right: 20px;"
            >

            <h5 class="fw-semibold mb-0">
                Solicitante: <span class="text-dark">{{ $serviceRequest->customUser->user_name }}</span>
            </h5>
        </div>
        
        <div class="card border-0">
            <div class="card-body bg-white">
                <div class="row gx-4 gy-3 align-items-center">
                    {{-- Descrição --}}
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-primary">
                            <i class="fas fa-align-left fa-lg text-primary me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1 text-primary fw-semibold">Descrição</h6>
                                <p class="mb-0 text-muted small">{{ $serviceRequest->description }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Orçamento Inicial --}}
                    <div class="col-12 col-md-3">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-warning">
                            <i class="fas fa-wallet fa-lg text-warning me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1 text-warning fw-semibold">Orçamento</h6>
                                <p class="mb-0 text-muted small">
                                    R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Data do Serviço --}}
                    <div class="col-12 col-md-3">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-info">
                            <i class="fas fa-calendar-alt fa-lg text-info me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1 text-info fw-semibold">Data</h6>
                                <p class="mb-0 text-muted small">
                                    {{ $serviceRequest->service_date
                                        ? \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y')
                                        : 'NÃO INFORMADO' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Valor Final Proposto --}}
                    @if($serviceRequest->final_price)
                    <div class="col-12 col-md-3">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-success">
                            <i class="fas fa-dollar-sign fa-lg text-success me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1 text-success fw-semibold">Valor Final</h6>
                                <p class="mb-0 text-muted small">
                                    R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
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
