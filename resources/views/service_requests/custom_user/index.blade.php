@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Minhas Solicitações Enviadas</h2>

    @forelse($requests as $request)
        <div class="border rounded p-3 mb-3">
            <p><strong>Serviço:</strong> {{ $request->service->title }}</p>
            <p><strong>Provider:</strong> {{ $request->provider->user_name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
            <p><strong>Mensagem:</strong> {{ $request->message }}</p>

            <a href="{{ route('service-requests-custom-user.show', $request->id) }}" class="btn btn-primary btn-sm">Ver Detalhes</a>
        </div>
    @empty
        <div class="alert alert-info">Você ainda não enviou nenhuma solicitação.</div>
    @endforelse
</div>
@endsection
