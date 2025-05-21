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
                        {{ ucfirst($sr->status) }}
                    </span>
                </div>
            </div>

            {{-- Ações --}}
            <div class="mt-2 d-flex gap-2 flex-wrap">
                {{-- Provider: Propor Valor e Data --}}
                @if(auth()->user() instanceof \App\Models\Provider && $sr->canPropose())
                    <form action="{{ route('service-requests.propose-price', $sr) }}" method="POST" class="d-flex gap-2 flex-wrap">
                        @csrf
                        @method('PUT')

                        <input type="number" step="0.01" name="final_price" class="form-control w-auto" placeholder="Valor Final (R$)" required>
                        <input type="date" name="service_date" class="form-control w-auto" required>

                        <button class="btn btn-info btn-sm">Propor</button>
                    </form>
                @endif

                {{-- CustomUser: Aceitar Proposta --}}
                @if(auth()->user() instanceof \App\Models\CustomUser && $sr->canAcceptProposal())
                    <form action="{{ route('service-requests.accept-proposal', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success btn-sm">Aceitar Proposta</button>
                    </form>
                @endif

                {{-- CustomUser: Recusar Proposta --}}
                @if(auth()->user() instanceof \App\Models\CustomUser && $sr->canRejectProposal())
                    <form action="{{ route('service-requests.reject-proposal', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-warning btn-sm">Recusar Proposta</button>
                    </form>
                @endif

                {{-- Provider: Rejeitar --}}
                @if(auth()->user() instanceof \App\Models\Provider && $sr->canReject())
                    <form action="{{ route('service-requests.update', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button class="btn btn-danger btn-sm">Rejeitar</button>
                    </form>
                @endif

                {{-- CustomUser: Cancelar --}}
                @if(auth()->user() instanceof \App\Models\CustomUser && $sr->canCancel())
                    <form action="{{ route('custom-user.service-requests.cancel', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-outline-warning btn-sm">Cancelar</button>
                    </form>
                @endif

                {{-- Concluir (CustomUser) --}}
                @if(auth()->user() instanceof \App\Models\CustomUser && $sr->canComplete())
                    <form action="{{ route('service-requests.update', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button class="btn btn-primary btn-sm">Concluir</button>
                    </form>
                @endif

                {{-- Acesso ao Chat --}}
                @if($sr->chat)
                    <a href="{{ route('chat.show', $sr->id) }}" class="btn btn-secondary btn-sm">
                        Ir para o Chat
                    </a>
                @else
                    {{-- Abrir Chat --}}
                    <form action="{{ route('service-requests.update', $sr) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="chat_opened">
                        <button class="btn btn-info btn-sm">Abrir Chat</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>Você ainda não possui conversas {{ $status === 'archived' ? 'arquivadas' : ($status === 'active' ? 'ativas' : '') }}.</p>
    @endforelse
</div>
@endsection
