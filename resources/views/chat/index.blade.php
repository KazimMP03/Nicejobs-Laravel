@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Minhas Conversas</h2>

    {{-- Filtro --}}
    <div class="mb-3">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativos</option>
                <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Arquivados</option>
            </select>
        </form>
    </div>

    {{-- Listagem --}}
    @forelse($chats as $chat)
        @php
            $sr = $chat->serviceRequest;
            $otherUser = auth()->user() instanceof \App\Models\Provider
                ? $sr->customUser
                : $sr->provider;
        @endphp

        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ route('chat.show', $sr->id) }}" class="fw-bold">
                        Conversa sobre "{{ $sr->description }}"
                    </a><br>
                    com <strong>{{ $otherUser->user_name }}</strong>
                </div>
                <div>
                    <span class="badge bg-{{ 
                        $sr->isRequested() ? 'secondary' :
                        ($sr->isChatOpened() ? 'info' :
                        ($sr->isPendingAcceptance() ? 'warning' :
                        ($sr->isAccepted() ? 'success' : 'dark')))
                    }}">
                        {{ $sr->getStatusLabel() }}
                    </span>
                </div>
            </div>

            {{-- Ações --}}
            <div class="mt-2 d-flex gap-2 flex-wrap">

                {{-- Acesso ao Chat --}}
                <a href="{{ route('chat.show', $sr->id) }}" class="btn btn-secondary btn-sm">
                    Ir para o Chat
                </a>

                {{-- Ver detalhes da Solicitação --}}
                @if(auth()->user() instanceof \App\Models\Provider)
                    <a href="{{ route('service-requests.show', $sr->id) }}" class="btn btn-outline-primary btn-sm">
                        Ver Solicitação
                    </a>
                @elseif(auth()->user() instanceof \App\Models\CustomUser)
                    <a href="{{ route('custom-user.service-requests.show', $sr->id) }}" class="btn btn-outline-primary btn-sm">
                        Ver Solicitação
                    </a>
                @endif

            </div>
        </div>
    @empty
        <p>Você ainda não possui conversas {{ $status === 'archived' ? 'arquivadas' : ($status === 'active' ? 'ativas' : '') }}.</p>
    @endforelse
</div>
@endsection
