@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Avaliar 
        @if(auth()->user() instanceof \App\Models\Provider)
            Cliente: <strong>{{ $serviceRequest->customUser->user_name }}</strong>
        @else
            Prestador: <strong>{{ $serviceRequest->provider->user_name }}</strong>
        @endif
    </h2>

    <div class="card">
        <div class="card-body">
            {{-- Informações da Service Request --}}
            <p><strong>Descrição do serviço:</strong> {{ $serviceRequest->description }}</p>
            <p><strong>Data do serviço:</strong> 
                {{ $serviceRequest->service_date ? \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y') : 'Não informado' }}
            </p>
            @if($serviceRequest->final_price)
                <p><strong>Valor Final:</strong> 
                    R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}
                </p>
            @endif

            <hr>

            {{-- Formulário de Avaliação --}}
            <form action="{{ route('service-requests.review', $serviceRequest) }}" method="POST">
                @csrf

                {{-- Nota --}}
                <div class="mb-3">
                    <label class="form-label">Nota</label>
                    <select name="rating" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="5">⭐⭐⭐⭐⭐ - Excelente</option>
                        <option value="4">⭐⭐⭐⭐ - Muito Bom</option>
                        <option value="3">⭐⭐⭐ - Bom</option>
                        <option value="2">⭐⭐ - Ruim</option>
                        <option value="1">⭐ - Péssimo</option>
                    </select>
                </div>

                {{-- Comentário --}}
                <div class="mb-3">
                    <label class="form-label">Comentário (opcional)</label>
                    <textarea name="comment" class="form-control" rows="4" 
                        placeholder="Escreva aqui sua avaliação..."></textarea>
                </div>

                {{-- Botões --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
                    <a href="{{ route(auth()->user() instanceof \App\Models\Provider ? 'service-requests.show' : 'custom-user.service-requests.show', $serviceRequest) }}" 
                       class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
