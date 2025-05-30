@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Meu Perfil</h2>

    {{-- Botão para editar perfil --}}
    <div class="mb-3">
        <a href="{{ route('custom-user.profile.edit') }}" class="btn btn-outline-primary">Editar Perfil</a>
    </div>

    {{-- Dados do cliente --}}
    <div class="mb-4">
        <p><strong>Nome:</strong> {{ $customUser->user_name }}</p>
        <p><strong>Tipo:</strong> {{ $customUser->user_type }}</p>
        <p><strong>Email:</strong> {{ $customUser->email }}</p>
        <p><strong>Telefone:</strong> {{ $customUser->phone }}</p>
        <p><strong>CPF/CNPJ:</strong> {{ $customUser->tax_id }}</p>

        @if($customUser->user_type === 'PF')
            <p><strong>Data de Nascimento:</strong> {{ $customUser->birth_date?->format('d/m/Y') }}</p>
        @else
            <p><strong>Data de Fundação:</strong> {{ $customUser->foundation_date?->format('d/m/Y') }}</p>
        @endif
    </div>
</div>
@endsection
