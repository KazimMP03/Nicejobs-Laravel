@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulário para editar informações gerais --}}
    <form action="{{ route('provider.profile.updateInfo') }}" method="POST" class="mb-4">
        @csrf

        <div class="mb-3">
            <label for="provider_description" class="form-label">Descrição</label>
            <textarea name="provider_description" id="provider_description" rows="4" class="form-control">{{ old('provider_description', $provider->provider_description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="work_radius" class="form-label">Raio de atuação (km)</label>
            <input type="number" name="work_radius" id="work_radius" value="{{ old('work_radius', $provider->work_radius) }}" class="form-control" required>
        </div>

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

    {{-- Formulário para atualizar a foto de perfil --}}
    <form action="{{ route('provider.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="profile_photo" class="form-label">Foto de Perfil</label><br>
            @if($provider->profile_photo)
                <img src="{{ asset('storage/' . $provider->profile_photo) }}" alt="Foto Atual" width="150" class="mb-2">
            @endif
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-secondary">Atualizar Foto</button>
    </form>
</div>
@endsection
