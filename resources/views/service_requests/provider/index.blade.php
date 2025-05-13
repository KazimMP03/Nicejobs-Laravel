@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Solicitações Recebidas</h2>

    @forelse($requests as $request)
        <div class="border rounded p-3 mb-3">
            <p><strong>Cliente:</strong> {{ $request->customUser->user_name }}</p>
            <p><strong>Serviço:</strong> {{ $request->service->title }}</p>
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
            <p><strong>Mensagem:</strong> {{ $request->message }}</p>

            <a href="{{ route('service-requests-provider.show', $request->id) }}" class="btn btn-primary btn-sm">Ver Detalhes</a>
        </div>
    @empty
        <div class="alert alert-info">Nenhuma solicitação recebida ainda.</div>
    @endforelse
</div>
@endsection
