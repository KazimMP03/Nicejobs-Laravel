@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Solicitação Recebida</h2>

    {{-- Dados do serviço --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Solicitante: <strong>{{ $serviceRequest->customUser->user_name }}</strong></h5>
            <p><strong>Descrição:</strong> {{ $serviceRequest->description }}</p>

            <p><strong>Orçamento Inicial:</strong> 
                R$ {{ number_format($serviceRequest->initial_budget, 2, ',', '.') }}
            </p>

            @if($serviceRequest->final_price)
                <p><strong>Valor Final Proposto:</strong> 
                    R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}
                </p>
            @endif

            @if($serviceRequest->service_date)
                <p><strong>Data do Serviço:</strong> 
                    {{ \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y') }}
                </p>
            @endif

            <p><strong>Status:</strong> 
                <span class="badge bg-{{ 
                    $serviceRequest->isRequested() ? 'secondary' : 
                    ($serviceRequest->isChatOpened() ? 'info' : 
                    ($serviceRequest->isPendingAcceptance() ? 'warning' : 
                    ($serviceRequest->isAccepted() ? 'success' : 'dark'))) }}">
                    {{ $serviceRequest->getStatusLabel() }}
                </span>
            </p>
        </div>
    </div>

    {{-- Endereço do serviço --}}
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

    {{-- Ações --}}
    @if(!$serviceRequest->isFinalized())
        <div class="d-flex gap-2 flex-wrap">

            {{-- Propor Valor e Data --}}
           @if($serviceRequest->canPropose())
                <form action="{{ route('service-requests.propose-price', $serviceRequest) }}" method="POST" class="d-flex gap-2 flex-wrap">
                    @csrf
                    @method('PUT')

                    <input type="number" step="0.01" name="final_price" 
                        class="form-control w-auto" 
                        placeholder="Valor Final (R$)" 
                        required>

                    <input type="date" name="service_date" 
                        class="form-control w-auto" 
                        required
                        min="{{ now()->format('Y-m-d') }}">

                    <button class="btn btn-info">Propor Valor e Data</button>
                </form>
            @endif

            {{-- Rejeitar --}}
            @if($serviceRequest->canReject())
                <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button class="btn btn-danger">Rejeitar</button>
                </form>
            @endif

            {{-- Acesso ao Chat --}}
            @if($serviceRequest->chat)
                <a href="{{ route('chat.show', $serviceRequest->id) }}" class="btn btn-secondary">
                    Ir para o Chat
                </a>
            @else
                {{-- Abrir Chat --}}
                <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="chat_opened">
                    <button class="btn btn-info">Abrir Chat</button>
                </form>
            @endif

        </div>
    @endif

    {{-- Se estiver concluído, aparece o botão de avaliação --}}
    @if($serviceRequest->isCompleted() && !$serviceRequest->wasReviewedBy(auth()->user()))
        <div class="mt-3">
            <a href="{{ route('service-requests.review', $serviceRequest) }}" class="btn btn-outline-primary">
                Avaliar {{ auth()->user() instanceof \App\Models\Provider ? 'Cliente' : 'Prestador' }}
            </a>
        </div>
    @endif
</div>
@endsection
