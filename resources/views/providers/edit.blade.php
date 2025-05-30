@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulário para editar informações do perfil --}}
    <div class="card mb-4">
        <div class="card-header">Informações Gerais</div>
        <div class="card-body">
            <form action="{{ route('provider.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Nome de Exibição --}}
                <div class="mb-3">
                    <label for="user_name" class="form-label">Nome</label>
                    <input type="text" name="user_name" id="user_name"
                           value="{{ old('user_name', $provider->user_name) }}"
                           class="form-control" required maxlength="255">
                </div>
                
                {{-- Telefone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', $provider->phone) }}"
                        class="form-control" required maxlength="20">
                </div>

                {{-- Descrição --}}
                <div class="mb-3">
                    <label for="provider_description" class="form-label">Descrição</label>
                    <textarea name="provider_description" id="provider_description" rows="4"
                              class="form-control">{{ old('provider_description', $provider->provider_description) }}</textarea>
                </div>

                {{-- Raio de atuação --}}
                <div class="mb-3">
                    <label for="work_radius" class="form-label">Raio de atuação (km)</label>
                    <input type="number" name="work_radius" id="work_radius"
                           value="{{ old('work_radius', $provider->work_radius) }}"
                           class="form-control" min="1" required>
                </div>

                {{-- Disponibilidade --}}
                <div class="mb-3">
                    <label for="availability" class="form-label">Disponibilidade</label>
                    <select name="availability" id="availability" class="form-select" required>
                        <option value="weekdays" {{ $provider->availability === 'weekdays' ? 'selected' : '' }}>Dias úteis</option>
                        <option value="weekends" {{ $provider->availability === 'weekends' ? 'selected' : '' }}>Fins de semana</option>
                        <option value="both" {{ $provider->availability === 'both' ? 'selected' : '' }}>Ambos</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Informações</button>
            </form>
        </div>
    </div>

    {{-- Formulário para atualizar a foto de perfil --}}
    <div class="card mb-4">
        <div class="card-header">Foto de Perfil</div>
        <div class="card-body">
            <form action="{{ route('provider.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    @if($provider->profile_photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $provider->profile_photo) }}" alt="Foto Atual" width="150" class="rounded">
                        </div>
                    @endif

                    <label for="profile_photo" class="form-label">Selecionar nova foto</label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="form-control">
                </div>

                <button type="submit" class="btn btn-secondary">Atualizar Foto</button>
            </form>
        </div>
    </div>
</div>

<!-- Script para adicionar máscara de telefone -->
<script src="{{ asset('js/phone-mask.js') }}"></script>
@endsection
