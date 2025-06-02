@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulário de dados do usuário --}}
    <div class="card mb-4">
        <div class="card-header">Informações Gerais</div>
        <div class="card-body">
            <form action="{{ route('custom-user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div class="mb-3">
                    <label for="user_name" class="form-label">Nome</label>
                    <input type="text" name="user_name" id="user_name"
                           value="{{ old('user_name', $customUser->user_name) }}"
                           class="form-control" required maxlength="255">
                </div>

                {{-- Telefone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone', $customUser->phone) }}"
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        </div>
    </div>

    {{-- Formulário para atualizar a foto de perfil --}}
    <div class="card mb-4">
        <div class="card-header">Foto de Perfil</div>
        <div class="card-body">
            <form action="{{ route('custom-user.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Foto atual --}}
                @if($customUser->profile_photo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $customUser->profile_photo) }}" alt="Foto de Perfil" width="150" class="rounded">
                    </div>
                @endif

                {{-- Campo de upload --}}
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Selecionar nova foto</label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="form-control">
                </div>

                <button type="submit" class="btn btn-secondary">Atualizar Foto</button>
            </form>
        </div>
    </div>
</div>

{{-- Script de máscara de telefone --}}
<script src="{{ asset('js/phone-mask.js') }}"></script>
@endsection
