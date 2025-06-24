@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
            MINHAS SOLICITAÇÕES
        </h3>
    </div>

    <div class="d-flex justify-content-center w-100">
        <div class="w-100" style="max-width: 800px;">
            {{-- Filtro --}}
            <div class="mb-4">
                <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="input-group" style="max-width: 250px;">
                        <span class="input-group-text">
                            <i class="fas fa-filter text-secondary"></i>
                        </span>
                        <select name="status"
                                class="form-select"
                                onchange="this.form.submit()">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Arquivados</option>
                            {{-- Opções individuais de status --}}
                            @foreach($statusOrder as $st)
                                <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                                    {{ $statusLabels[$st] ?? $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            
            @if($requests->isEmpty())
                <div class="p-4 text-center text-secondary">
                    Você ainda não enviou nenhuma solicitação.
                </div>
            @else
                <div class="bg-white shadow-sm rounded">
                    @foreach($requests as $request)
                        @php
                            $user = $request->provider;
                            $avatarUrl = $user->profile_photo
                                         ? asset('storage/' . $user->profile_photo)
                                         : asset('images/user.png');

                            // Mapeamento de status para label e cor
                            switch ($request->status) {
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
                                    $badgeClass = 'bg-dark text-white';
                                    $label      = 'Cancelado';
                                    break;
                                case 'completed':
                                    $badgeClass = 'bg-success';
                                    $label      = 'Concluído';
                                    break;
                                default:
                                    $badgeClass = 'bg-light text-dark';
                                    $label      = ucfirst(str_replace('_', ' ', $request->status));
                            }
                        @endphp

                        <div
                            class="d-flex align-items-center px-3 py-2 border-bottom"
                            style="cursor: pointer;"
                            onclick="window.location='{{ route('custom-user.service-requests.show', $request->id) }}'"
                        >
                            <div class="me-3 flex-shrink-0">
                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="Avatar de {{ $user->user_name }}"
                                    class="rounded-circle"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                >
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="fw-bold text-dark" style="font-size: 1rem;">
                                        {{ $user->user_name }}
                                    </span>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $label }}
                                    </span>
                                </div>
                                <small class="text-secondary d-block">
                                    {{ Str::limit($request->description, 100) }}
                                </small>
                                <small class="text-secondary">
                                    Orçamento inicial: <strong>R$ {{ number_format($request->initial_budget, 2, ',', '.') }}</strong>
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
