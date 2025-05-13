@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Solicitar Serviço</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $service->title }}</h5>
            <p class="card-text">{{ $service->description }}</p>
            <p class="card-text"><strong>Provider:</strong> {{ $service->provider->user_name }}</p>
            <p class="card-text"><strong>Preço:</strong> R$ {{ number_format($service->price, 2, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('service-requests.store', $service->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="message" class="form-label">Mensagem (opcional)</label>
            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Descreva detalhes do que você precisa, preferências de horário, etc."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enviar Solicitação</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
