@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}"> {{-- reutilizando o CSS de formulários --}}

{{-- TÍTULO “MEU PERFIL” --}}
<div class="w-100 d-flex align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        EDIÇÃO DE PERFIL
    </h3>
</div>

<div class="w-100 d-flex justify-content-center align-items-start mt-5 mb-5">
    {{-- Reduzi de 900px para 700px --}}
    <div class="shadow-sm bg-white rounded w-100" style="max-width: 700px;">
        <div class="row g-0">
            {{-- COLUNA ESQUERDA: FOTO + UPLOAD --}}
            <div class="col-md-4 d-flex flex-column align-items-center py-4"
                 style="background-color: #f8f9fa; border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                @php
                    $profilePhoto = $provider->profile_photo
                        ? asset('storage/' . $provider->profile_photo)
                        : asset('images/user.png');
                @endphp

                {{-- Formulário dedicado ao upload de foto --}}
                <form id="photo-form" action="{{ route('provider.profile.updatePhoto') }}" method="POST"
                      enctype="multipart/form-data" class="text-center">
                    @csrf

                    <div class="position-relative d-inline-block mb-3">
                        {{-- Label que “abre” o file input --}}
                        <label for="profile_photo" style="cursor: pointer; display: inline-block;">
                            <img src="{{ $profilePhoto }}" alt="Foto do Prestador"
                                 class="rounded-circle shadow"
                                 style="width: 140px; height: 140px; object-fit: cover;">

                            <span class="position-absolute top-100 start-100 translate-middle"
                                  style="background-color: white; border-radius: 50%; padding: .25rem;">
                                <i class="bi bi-pencil-fill text-secondary" style="font-size: 1rem;"></i>
                            </span>
                        </label>

                        <input type="file"
                               name="profile_photo"
                               id="profile_photo"
                               accept="image/*"
                               class="d-none @error('profile_photo') is-invalid @enderror"
                               onchange="document.getElementById('photo-form').submit()">

                        @error('profile_photo')
                            <div class="text-danger mt-2" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Exibir nome e descrição curta ao lado da foto (opcional) --}}
                    <h5 class="fw-bold text-dark text-center mb-1" style="word-wrap: break-word;">
                        {!! wordwrap(e($provider->user_name), 20, '<br>', true) !!}
                    </h5>
                    @if($provider->provider_description)
                        <p class="text-muted text-center"
                           style="max-width: 200px; font-size: .9rem; line-height: 1.2;">
                            {!! wordwrap(e($provider->provider_description), 20, '<br>', true) !!}
                        </p>
                    @endif
                </form>
            </div>

            {{-- COLUNA DIREITA: FORMULÁRIO PRINCIPAL --}}
            <div class="col-md-8 px-4 py-4">
                <form id="info-form" action="{{ route('provider.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- CAMPO NOME --}}
                    <div class="form-floating mb-4">
                        <input type="text"
                               name="user_name"
                               id="user_name"
                               value="{{ old('user_name', $provider->user_name) }}"
                               class="form-control @error('user_name') is-invalid @enderror word-wrap-break"
                               placeholder="Nome completo"
                               required
                               maxlength="255">
                        <label for="user_name">Nome completo</label>
                        @error('user_name')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAMPO TELEFONE --}}
                    <div class="form-floating mb-4">
                        <input type="text"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', $provider->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror word-wrap-break"
                               placeholder="(00) 00000-0000"
                               required
                               maxlength="20">
                        <label for="phone">Telefone</label>
                        @error('phone')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAMPO DESCRIÇÃO --}}
                    <div class="form-floating mb-4">
                        <textarea name="provider_description"
                                  id="provider_description"
                                  class="form-control @error('provider_description') is-invalid @enderror word-wrap-break"
                                  placeholder="Descreva seu serviço (máx. 1000 caracteres)"
                                  style="height: 100px;"
                                  maxlength="1000">{{ old('provider_description', $provider->provider_description) }}</textarea>
                        <label for="provider_description">Descrição do serviço</label>
                        @error('provider_description')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAMPO RAIO DE ATUAÇÃO --}}
                    <div class="form-floating mb-4">
                        <input type="number"
                               name="work_radius"
                               id="work_radius"
                               value="{{ old('work_radius', $provider->work_radius) }}"
                               class="form-control @error('work_radius') is-invalid @enderror"
                               placeholder="Alcance (KM)"
                               min="1"
                               required>
                        <label for="work_radius">Alcance (KM)</label>
                        @error('work_radius')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAMPO DISPONIBILIDADE --}}
                    <div class="form-floating mb-4">
                        <select name="availability"
                                id="availability"
                                class="form-select @error('availability') is-invalid @enderror"
                                required
                                style="padding-top: calc(1rem + 8px);">
                            <option value="weekdays" {{ old('availability', $provider->availability) === 'weekdays' ? 'selected' : '' }}>
                                Dias úteis
                            </option>
                            <option value="weekends" {{ old('availability', $provider->availability) === 'weekends' ? 'selected' : '' }}>
                                Fins de semana
                            </option>
                            <option value="both" {{ old('availability', $provider->availability) === 'both' ? 'selected' : '' }}>
                                Ambos
                            </option>
                        </select>
                        <label for="availability" class="form-label text-secondary">
                            Disponibilidade
                        </label>
                        @error('availability')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BOTÕES: CANCELAR À ESQUERDA | SALVAR À DIREITA --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        {{-- Botão Cancelar --}}
                        <a href="{{ route('provider.profile.show') }}"
                           class="btn btn-cancel fw-bold">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>

                        {{-- Botão Salvar --}}
                        <button type="submit"
                                class="btn btn-primary fw-bold">
                            <i class="fas fa-save me-2"></i> Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script de máscara de telefone (reutilize o seu phone-mask.js) --}}
<script src="{{ asset('js/phone-mask.js') }}"></script>
@endsection
