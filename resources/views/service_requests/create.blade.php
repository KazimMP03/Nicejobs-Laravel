@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}">

<div class="container">

    {{-- Cabeçalho --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0 text-uppercase"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik',sans-serif;">
            SOLICITAR SERVIÇO PARA
            <span class="text-uppercase">{{ $provider->user_name }}</span>
        </h3>
    </div>

    {{-- Formulário de Solicitação --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <div class="card border-0">
            <div class="card-body bg-white">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('service-requests.store', ['provider' => $provider->id]) }}" method="POST">
                    @csrf

                    <div class="row gx-4 gy-4">
                        {{-- Descrição do Serviço --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Descrição do Serviço</label>
                            <textarea name="description" id="description" rows="5" 
                                      class="form-control" required>{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Descreva o serviço que você precisa, com o máximo de detalhes.</small>
                        </div>

                        {{-- Orçamento Inicial --}}
                        <div class="col-12 col-md-6">
                            <label for="initial_budget" class="form-label fw-semibold">Orçamento Inicial (R$)</label>
                            <input type="number" name="initial_budget" id="initial_budget" 
                                   value="{{ old('initial_budget') }}" step="0.01" min="0" 
                                   class="form-control" required>
                        </div>

                        {{-- Escolha do Endereço --}}
                        <div class="col-12 col-md-6">
                            <label for="address_id" class="form-label fw-semibold">Endereço para o serviço</label>
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
                    </div>

                    {{-- Botão Enviar --}}
                    <div class="d-flex justify-content-center mt-5">
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="fas fa-paper-plane me-2"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
@endsection
