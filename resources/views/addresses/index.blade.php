{{-- resources/views/addresses/index.blade.php --}}

@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/address/index.css') }}">

<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        MEUS ENDEREÇOS
    </h3>
    <a href="{{ route('addresses.create') }}" class="btn btn-primary fw-bold">
        <i class="fas fa-plus me-2"></i> Novo Endereço
    </a>

</div>

<!-- Mensagem de sucesso -->
@if(session('success'))
<div class="w-100 d-flex justify-content-center mb-3">
    <div class="alert alert-success text-center px-4 py-2 mb-0"
        style="max-width: 600px;">
        {{ session('success') }}
    </div>
</div>
@endif

<div class="d-flex justify-content-center align-items-start w-100 py-5">
    <div class="shadow-sm rounded px-4 py-4"
        style="max-width: 1300px; background-color: #fefefe;">

        <!-- Grid de Endereços -->
        <div class="d-flex flex-wrap justify-content-center gap-4">
            @if($addresses->isEmpty())
            <div
                class="empty-message text-center text-dark fw-bold bg-light p-4 rounded"
                style="font-size: 1.5rem;">
                Nenhum endereço cadastrado!
                <div class="fw-normal mt-2 text-secondary"
                    style="font-size: 1rem;">
                    Cadastre um agora mesmo clicando no botão acima.
                </div>
            </div>
            @else
            @foreach($addresses as $address)
            <div
                class="address-card {{ $address->pivot->is_default ? 'default' : '' }}"
                style="width: 280px;">
                <div class="address-content">
                    {{-- Título + badge ou ação definir padrão --}}
                    <div class="address-street">
                        {{ $address->logradouro }}, {{ $address->numero }}

                        @if($address->pivot->is_default)
                        <span class="badge badge-default">(Padrão)</span>
                        @else
                        <form
                            action="{{ route('addresses.setDefault', $address->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="set-default-badge">(Padrão)</button>
                        </form>
                        @endif
                    </div>

                    {{-- Demais informações --}}
                    <div class="address-info">{{ $address->bairro }}</div>
                    <div class="address-info">{{ $address->cidade }}/{{
                        $address->estado }}</div>
                    <div class="address-cep-complement">
                        CEP: {{ $address->cep }}
                        @if($address->complemento)
                        • {{ Str::limit($address->complemento, 15) }}
                        @endif
                    </div>
                </div>

                {{-- Botões de ação --}}
                <div class="address-actions">
                    <a href="{{ route('addresses.edit', $address->id) }}"
                        class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    <form
                        action="{{ route('addresses.destroy', $address->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete"
                            onclick="return confirm('Excluir este endereço?')">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    @endsection