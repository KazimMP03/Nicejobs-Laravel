@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}">

<div class="container">

    {{-- Cabeçalho --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
            DETALHES DA SOLICITAÇÃO
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
                    Solicitante: <span class="text-dark">{{ $serviceRequest->customUser->user_name }}</span>
                </h5>
                <p class="mb-3"><strong>Descrição:</strong> {{ $serviceRequest->description }}</p>
                <p class="mb-3"><strong>Orçamento Inicial:</strong> R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}</p>

                @if($serviceRequest->final_price)
                    <p class="mb-3"><strong>Valor Final Proposto:</strong> R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}</p>
                @endif

                @if($serviceRequest->service_date)
                    <p class="mb-3"><strong>Data do Serviço:</strong> {{ \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>
    </section>


    {{-- Seção: Endereço do Serviço --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Endereço do Serviço
        </h4>
        <div class="card border-0">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0 text-primary border-start border-4 border-primary ps-3 text-uppercase">
                Gerenciar Solicitação
            </h4>
        </div>

        <div class="card border-0 bg-white shadow-sm">
            <div class="card-body">
                @if($serviceRequest->canPropose())
                    {{-- Instrução para o usuário --}}
                    <p class="text-secondary mb-4">
                        Esses dados são para propor um novo valor ou data no serviço
                    </p>

                    {{-- Form com campos lado a lado --}}
                    <form action="{{ route('service-requests.propose-price', $serviceRequest) }}"
                          method="POST"
                          class="d-flex align-items-center gap-3">
                        @csrf @method('PUT')

                        {{-- Valor --}}
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text">R$</span>
                            <input type="number"
                                   step="0.01"
                                   name="final_price"
                                   class="form-control form-control-lg"
                                   placeholder="Valor"
                                   required>
                        </div>

                        {{-- Data --}}
                        <input type="date"
                               name="service_date"
                               class="form-control form-control-lg"
                               required
                               min="{{ now()->format('Y-m-d') }}"
                               style="width: 300px;">

                        {{-- Dropdown de Ações com ícone --}}
                        <div class="dropdown ms-auto">
                            <button class="btn btn-lg btn-primary dropdown-toggle"
                                    type="button"
                                    id="actionsDropdown"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    style="min-width: 220px;">
                                <i class="fas fa-cog me-1"></i> Ações
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsDropdown">
                                @if($serviceRequest->canPropose())
                                <li>
                                    <form method="POST"
                                          action="{{ route('service-requests.propose-price', $serviceRequest) }}"
                                          class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="submit">
                                            <i class="fas fa-tag"></i> Propor Valor
                                        </button>
                                    </form>
                                </li>
                                @endif

                                @if($serviceRequest->canReject())
                                <li>
                                    <form method="POST"
                                          action="{{ route('service-requests.update', $serviceRequest) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="submit">
                                            <i class="fas fa-times text-danger"></i> Rejeitar
                                        </button>
                                    </form>
                                </li>
                                @endif

                                <li>
                                    @if($serviceRequest->chat)
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{ route('chat.show', $serviceRequest->id) }}">
                                        <i class="fas fa-comments"></i> Abrir Chat
                                    </a>
                                    @else
                                    <form method="POST"
                                          action="{{ route('service-requests.update', $serviceRequest) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="chat_opened">
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="submit">
                                            <i class="fas fa-comments"></i> Abrir Chat
                                        </button>
                                    </form>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </form>
                @else
                    <div class="me-auto"></div>
                @endif
            </div>
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
            <i class="fas fa-star me-1"></i> Avaliar {{ auth()->user() instanceof \App\Models\Provider ? 'Cliente' : 'Prestador' }}
        </a>
    </section>
    @endif

</div>
@endsection
