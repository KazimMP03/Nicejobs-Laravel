@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Solicitação Recebida</h2>

    {{-- Dados do serviço --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Solicitante: <strong>{{ $serviceRequest->customUser->user_name }}</strong></h5>

            <p><strong>Descrição do serviço:</strong><br>
                {{ $serviceRequest->description }}
            </p>

            <p><strong>Orçamento Inicial:</strong> R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}</p>

            @if($serviceRequest->final_price)
                <p><strong>Valor Final Acordado:</strong> R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}</p>
            @endif

            <p><strong>Status:</strong> <span class="badge bg-{{ $serviceRequest->status === 'requested' ? 'secondary' : ($serviceRequest->status === 'chat_opened' ? 'info' : 'success') }}">
                {{ ucfirst($serviceRequest->status) }}
            </span></p>
        </div>
    </div>

    {{-- Endereço do Serviço --}}
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

    {{-- Ações do Provider --}}
    @if($serviceRequest->isRequested())
        <div class="d-flex gap-2">
            <form action="{{ route('service-requests.update', $serviceRequest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="open_chat">
                <button type="submit" class="btn btn-info">Abrir Chat</button>
            </form>

            <form action="{{ route('service-requests.update', $serviceRequest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="btn btn-danger">Rejeitar Solicitação</button>
            </form>
        </div>
    @elseif($serviceRequest->isChatOpened())
        <a href="{{ route('chat.open', $serviceRequest->id) }}" class="btn btn-primary">Ir para o Chat</a>
    @endif
</div>
@endsection
