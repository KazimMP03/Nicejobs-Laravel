@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}"> {{-- reutilizando o CSS de formulários --}}

{{-- TÍTULO “EDIÇÃO DE PERFIL” --}}
<div class="w-100 d-flex align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        EDIÇÃO DE PERFIL
    </h3>
</div>

<div class="w-100 d-flex justify-content-center align-items-start mt-5 mb-5">
    {{-- Container centralizado com sombra e largura máxima de 700px --}}
    <div class="shadow-sm bg-white rounded w-100" style="max-width: 700px;">
        <div class="row g-0">
            {{-- COLUNA ESQUERDA: FOTO + UPLOAD --}}
            <div class="col-md-4 d-flex flex-column align-items-center py-4"
                 style="background-color: #f8f9fa; border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                @php
                    $profilePhoto = $customUser->profile_photo
                        ? asset('storage/' . $customUser->profile_photo)
                        : asset('images/user.png');
                @endphp

                {{-- Formulário dedicado ao upload de foto --}}
                <form id="photo-form" action="{{ route('custom-user.profile.updatePhoto') }}" method="POST"
                      enctype="multipart/form-data" class="text-center">
                    @csrf

                    <div class="position-relative d-inline-block mb-3">
                        {{-- Label que “abre” o file input --}}
                        <label for="profile_photo" style="cursor: pointer; display: inline-block;">
                            <img src="{{ $profilePhoto }}" alt="Foto do Usuário"
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

                    {{-- Exibir nome do usuário abaixo da foto --}}
                    <h5 class="fw-bold text-dark text-center mb-1" style="word-wrap: break-word;">
                        {!! wordwrap(e($customUser->user_name), 20, '<br>', true) !!}
                    </h5>
                </form>
            </div>

            {{-- COLUNA DIREITA: FORMULÁRIO PRINCIPAL --}}
            <div class="col-md-8 px-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form id="info-form" action="{{ route('custom-user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- CAMPO NOME --}}
                    <div class="form-floating mb-4">
                        <input type="text"
                               name="user_name"
                               id="user_name"
                               value="{{ old('user_name', $customUser->user_name) }}"
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
                               value="{{ old('phone', $customUser->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror word-wrap-break"
                               placeholder="(00) 00000-0000"
                               required
                               maxlength="20">
                        <label for="phone">Telefone</label>
                        @error('phone')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BOTÕES: CANCELAR À ESQUERDA | SALVAR À DIREITA --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        {{-- Botão Cancelar --}}
                        <a href="{{ route('custom-user.profile.show') }}"
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
