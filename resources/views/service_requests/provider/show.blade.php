@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Solicitação</h2>

    <p><strong>Serviço:</strong> {{ $serviceRequest->service->title }}</p>
    <p><strong>Cliente:</strong> {{ $serviceRequest->customUser->user_name }}</p>
    <p><strong>Status:</strong> {{ ucfirst($serviceRequest->status) }}</p>
    <p><strong>Mensagem:</strong> {{ $serviceRequest->message }}</p>

    @if($serviceRequest->isPending())
        <form method="POST" action="{{ route('service-requests-provider.update', $serviceRequest->id) }}">
            @csrf
            @method('PUT')

            <button name="status" value="accepted" class="btn btn-success">Aceitar</button>
            <button name="status" value="rejected" class="btn btn-danger">Rejeitar</button>
        </form>
    @endif
</div>
@endsection
