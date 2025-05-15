@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Solicitações Recebidas</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info">Nenhuma solicitação recebida ainda.</div>
    @else
        <div class="list-group">
            @foreach($requests as $request)
                <a href="{{ route('service-requests.show', $request->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $request->customUser->user_name }}</h5>
                        <small>Status: <span class="badge bg-{{ $request->status === 'requested' ? 'secondary' : ($request->status === 'chat_opened' ? 'info' : ($request->status === 'accepted' ? 'success' : 'danger')) }}">
                            {{ ucfirst($request->status) }}
                        </span></small>
                    </div>
                    <p class="mb-1">{{ Str::limit($request->description, 100) }}</p>
                    <small>Orçamento inicial: R$ {{ number_format($request->initial_budget, 2, ',', '.') }}</small>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
