@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/address/index.css') }}">

<div class="address-page">
    <!-- Cabeçalho Minimalista -->
    <div class="page-header">
        <h1 class="page-title">Meus Endereços</h1>
        <p class="page-description">Gerencie seus endereços cadastrados</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Grid Ultra-Compacto -->
    <div class="address-grid">
        <!-- Cartão Adicionar -->
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
                <div class="address-card">
                    <div class="address-content">
                        <div class="address-street">
                            {{ $address->logradouro }}, {{ $address->numero }}
                        </div>
                        <div class="address-info">
                            {{ $address->bairro }}
                        </div>
                        <div class="address-info">
                            {{ $address->cidade }}/{{ $address->estado }}
                        </div>
                        <div class="address-cep-complement">
                            CEP: {{ $address->cep }} 
                            @if($address->complemento)
                                • {{ Str::limit($address->complemento, 15) }}
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botões Micro -->
                    <div class="address-actions">
                        <a href="{{ route('addresses.edit', $address->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Excluir este endereço?')">
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