@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('custom-user.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="user_name" class="form-label">Nome</label>
            <input type="text" name="user_name" id="user_name" value="{{ old('user_name', $customUser->user_name) }}" class="form-control" required>
        </div>

        {{-- Telefone --}}
        <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $customUser->phone) }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<!-- Script para adicionar máscara de telefone -->
<script src="{{ asset('js/phone-mask.js') }}"></script>
@endsection
