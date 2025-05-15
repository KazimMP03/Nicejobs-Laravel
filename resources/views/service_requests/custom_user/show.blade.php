@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Minha Solicitação</h2>

    {{-- Dados do serviço --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Prestador: <strong>{{ $serviceRequest->provider->user_name }}</strong></h5>

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

    {{-- Ações do CustomUser --}}
    @if($serviceRequest->isChatOpened())
        <a href="{{ route('chat.open', $serviceRequest->id) }}" class="btn btn-primary">Ir para o Chat</a>
    @endif

    @if($serviceRequest->isRequested() || $serviceRequest->isChatOpened())
        <form action="{{ route('custom-user.service-requests.cancel', $serviceRequest->id) }}" method="POST" class="mt-3">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-warning">Cancelar Solicitação</button>
        </form>
    @endif
</div>
@endsection
