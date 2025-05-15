@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Solicitar Serviço de {{ $provider->user_name }}</h2>

    {{-- Mensagens de validação --}}
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('service-requests.store', ['provider' => $provider->id]) }}" method="POST">
        @csrf

        {{-- Descrição da solicitação --}}
        <div class="mb-3">
            <label for="description" class="form-label">Descrição do Serviço</label>
            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
            <small class="form-text text-muted">Descreva o serviço que você precisa, com o máximo de detalhes.</small>
        </div>

        {{-- Orçamento inicial --}}
        <div class="mb-3">
            <label for="initial_budget" class="form-label">Orçamento Inicial (R$)</label>
            <input type="number" name="initial_budget" id="initial_budget" value="{{ old('initial_budget') }}" step="0.01" min="0" class="form-control" required>
        </div>

        {{-- Escolha do endereço --}}
        <div class="mb-3">
            <label for="address_id" class="form-label">Endereço para o serviço</label>
            <select name="address_id" id="address_id" class="form-select" required>
                @forelse($addresses as $address)
                    <option value="{{ $address->id }}" {{ $address->pivot->is_default ? 'selected' : '' }}>
                        {{ $address->logradouro }}, {{ $address->numero }} - {{ $address->bairro }}, {{ $address->cidade }}/{{ $address->estado }}
                        ({{ $address->cep }})
                        @if($address->pivot->is_default) - Padrão @endif
                    </option>
                @empty
                    <option disabled>Nenhum endereço cadastrado.</option>
                @endforelse
            </select>
            <small class="form-text text-muted">Caso não selecione, será usado o endereço padrão.</small>
        </div>

        {{-- Botão de enviar --}}
        <button type="submit" class="btn btn-success">Enviar Solicitação</button>
    </form>
</div>
@endsection
