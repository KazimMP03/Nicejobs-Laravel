{{-- resources/views/addresses/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/address/index.css') }}">

    <div class="address-page">
        <!-- Cabeçalho -->
        <div class="page-header">
            <h1 class="page-title">Meus Endereços</h1>
            <p class="page-description">Gerencie seus endereços cadastrados</p>
        </div>

        <!-- Mensagem de sucesso -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid de Endereços -->
        <div class="address-grid">
            {{-- Cartão para adicionar novo --}}
            <a href="{{ route('addresses.create') }}" class="add-address-card">
                <div class="add-address-btn">
                    <i class="fas fa-plus"></i>
                    <span>Novo Endereço</span>
                </div>
            </a>

            @if($addresses->isEmpty())
                <div class="empty-message">
                    Nenhum endereço cadastrado
                </div>
            @else
                @foreach($addresses as $address)
                    <div class="address-card {{ $address->pivot->is_default ? 'default' : '' }}">
                        <div class="address-content">
                            {{-- Título + badge ou ação definir padrão --}}
                            <div class="address-street">
                                {{ $address->logradouro }}, {{ $address->numero }}

                                @if($address->pivot->is_default)
                                    <span class="badge badge-default">(Padrão)</span>
                                @else
                                    <form action="{{ route('addresses.setDefault', $address->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="set-default-badge">(Padrão)</button>
                                    </form>
                                @endif
                            </div>

                            {{-- Demais informações --}}
                            <div class="address-info">{{ $address->bairro }}</div>
                            <div class="address-info">{{ $address->cidade }}/{{ $address->estado }}</div>
                            <div class="address-cep-complement">
                                CEP: {{ $address->cep }}
                                @if($address->complemento)
                                    • {{ Str::limit($address->complemento, 15) }}
                                @endif
                            </div>
                        </div>

                        {{-- Botões de ação --}}
                        <div class="address-actions">
                            <a href="{{ route('addresses.edit', $address->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST">
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