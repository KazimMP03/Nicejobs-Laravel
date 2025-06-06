@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
            MINHAS CONVERSAS
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
                        </select>
                    </div>
                </form>
            </div>

            {{-- Listagem de Conversas --}}
            @forelse($chats as $chat)
                @php
                    $sr = $chat->serviceRequest;
                    $currentUser = auth()->user();

                    if ($currentUser instanceof \App\Models\Provider) {
                        $otherUser = $sr->customUser;
                    } else {
                        $otherUser = $sr->provider;
                    }

                    if ($otherUser && !empty($otherUser->profile_photo)) {
                        $avatarUrl = asset('storage/' . $otherUser->profile_photo);
                    } else {
                        $avatarUrl = asset('images/user.png');
                    }
                @endphp

                <div
                    class="shadow-sm bg-white rounded d-flex align-items-center px-3 py-2 mb-3"
                    style="cursor: pointer;"
                    onclick="window.location='{{ route('chat.show', $sr->id) }}'"
                >
                    {{-- Avatar (foto do outro usuário) --}}
                    <div class="me-3 flex-shrink-0">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="Avatar de {{ $otherUser->user_name ?? 'Usuário' }}"
                            class="rounded-circle"
                            style="width: 70px; height: 70px; object-fit: cover;"
                        >
                    </div>

                    {{-- Conteúdo principal (nome, status, descrição) --}}
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            {{-- Nome do outro usuário --}}
                            <div>
                                <span class="fw-bold text-dark" style="font-size: 1rem;">
                                    {{ $otherUser->user_name }}
                                </span>
                            </div>

                            {{-- Badge de status da ServiceRequest (canto superior direito) --}}
                            @php
                                $label = $sr->getStatusLabel();

                                if ($sr->isChatOpened()) {
                                    // Chat aberto → azul
                                    $badgeClass = 'bg-info';
                                }
                                elseif ($sr->isPendingAcceptance()) {
                                    // Pendente de aceite → amarelo
                                    $badgeClass = 'bg-warning text-dark';
                                }
                                elseif ($label === 'Concluído') {
                                    // Quando o getStatusLabel() devolver exatamente "Concluído" → verde
                                    $badgeClass = 'bg-success';
                                }
                                else {
                                    // Qualquer outro status (Solicitado, Cancelado etc.) → cinza/escuro
                                    $badgeClass = 'bg-danger';
                                }
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ $label }}
                            </span>

                        </div>

                        {{-- Descrição do serviço --}}
                        <div class="mt-1">
                            <small class="text-secondary">
                                Serviço: <strong>“{{ $sr->description }}”</strong>
                            </small>
                        </div>
                    </div>
                </div>

            @empty
                <div class="shadow-sm bg-white rounded px-4 py-4 text-center">
                    <p class="mb-0 text-secondary">
                        Você ainda não possui conversas
                        @if($status === 'archived') arquivadas
                        @elseif($status === 'active') ativas
                        @endif
                        .
                    </p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
