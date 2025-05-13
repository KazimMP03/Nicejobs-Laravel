@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Solicitação</h2>

    <p><strong>Serviço:</strong> {{ $serviceRequest->service->title }}</p>
    <p><strong>Provider:</strong> {{ $serviceRequest->provider->user_name }}</p>
    <p><strong>Status:</strong> {{ ucfirst($serviceRequest->status) }}</p>
    <p><strong>Mensagem:</strong> {{ $serviceRequest->message }}</p>

    @if($serviceRequest->isPending())
        <form method="POST" action="{{ route('service-requests-custom-user.update', $serviceRequest->id) }}">
            @csrf
            @method('PUT')

            <button name="status" value="cancelled" class="btn btn-warning">Cancelar Solicitação</button>
        </form>
    @endif
</div>
@endsection
