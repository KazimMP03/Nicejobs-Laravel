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

            {{-- Listagem de Conversas --}}
            <div class="bg-white shadow-sm rounded">
                @forelse($chats as $chat)
                    @php
                        $sr = $chat->serviceRequest;
                        $currentUser = auth()->user();
                        $otherUser = $currentUser instanceof \App\Models\Provider
                                     ? $sr->customUser
                                     : $sr->provider;
                        $avatarUrl = $otherUser && $otherUser->profile_photo
                                     ? asset('storage/' . $otherUser->profile_photo)
                                     : asset('images/user.png');
                    @endphp

                    <div
                        class="d-flex align-items-center px-3 py-2 border-bottom"
                        style="cursor: pointer;"
                        onclick="window.location='{{ route('chat.show', $sr->id) }}'"
                    >
                        <div class="me-3 flex-shrink-0">
                            <img
                                src="{{ $avatarUrl }}"
                                alt="Avatar de {{ $otherUser->user_name ?? 'Usuário' }}"
                                class="rounded-circle"
                                style="width: 50px; height: 50px; object-fit: cover;"
                            >
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fw-bold text-dark" style="font-size: 1rem;">
                                    {{ $otherUser->user_name }}
                                </span>
                                @php
                                    $label = $sr->getStatusLabel();
                                    if ($sr->isChatOpened())             { $badgeClass = 'bg-info'; }
                                    elseif ($sr->isPendingAcceptance()) { $badgeClass = 'bg-warning text-dark'; }
                                    elseif ($label === 'Concluído')     { $badgeClass = 'bg-success'; }
                                    else                                { $badgeClass = 'bg-danger'; }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $label }}
                                </span>
                            </div>
                            <small class="text-secondary">
                                Serviço: <strong>“{{ $sr->description }}”</strong>
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-secondary">
                        Você ainda não possui conversas
                        @if($status === 'archived') arquivadas
                        @elseif($status === 'active') ativas
                        @endif
                        .
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
