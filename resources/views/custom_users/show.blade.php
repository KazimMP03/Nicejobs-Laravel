@extends('layouts.app')

@section('content')
<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        MEU PERFIL
    </h3>

    <a href="{{ route('custom-user.profile.edit') }}"
        class="btn btn-primary fw-bold">
        <i class="fas fa-edit me-2"></i> Editar Perfil
    </a>
</div>

<div class="d-flex justify-content-center align-items-center w-100"
    style="flex: 1; margin-top: 80px;">
    <table class="table mx-auto shadow-sm rounded bg-white"
        style="width: auto; border-collapse: separate; border-spacing: 0.5rem 0.5rem;">
        @php
            $profilePhoto = $customUser->profile_photo
                // Se houver um caminho armazenado no banco, assumimos que ele é relativo a storage/app/public
                ? asset('storage/' . $customUser->profile_photo)
                // Caso não tenha foto cadastrada, usa placeholder genérico
                : asset('images/user.png');
        @endphp
        <tr>
            {{-- COLUNA ESQUERDA: FOTO + NOME --}}
            <td class="text-center align-middle border-0 px-4 py-3 ms-3"
                style="background-color: #f8f9fa; border-radius: 0.5rem 0 0 0.5rem;">
                <img src="{{ $profilePhoto }}" alt="Foto do Usuário"
                    class="rounded-circle mb-3 shadow"
                    style="width: 140px; height: 140px; object-fit: cover;">
                <h4 class="fw-bold text-dark mb-0">
                    {!! wordwrap(e($customUser->user_name), 25, '<br>', true) !!}
                </h4>
            </td>

            {{-- TÍTULOS DOS CAMPOS --}}
            <td
                class="align-middle text-center fw-bold border-0 px-4 py-3 text-secondary"
                style="white-space: nowrap; background-color: #ffffff;">
                <p class="mb-3 mt-3">Tipo:</p>
                <p class="mb-3">E-mail:</p>
                <p class="mb-3">Telefone:</p>
                <p class="mb-3">CPF/CNPJ:</p>
                @if($customUser->user_type === 'PF')
                <p class="mb-3">Nascimento:</p>
                @else
                <p class="mb-3">Fundação:</p>
                @endif
            </td>

            {{-- VALORES DOS CAMPOS --}}
            <td class="align-middle text-start border-0 px-4 py-3 text-dark"
                style="background-color: #ffffff;">
                <p class="mb-3 mt-3">{{ $customUser->user_type }}</p>
                <p class="mb-3">{{ $customUser->email }}</p>
                <p class="mb-3">{{ $customUser->phone }}</p>
                <p class="mb-3">{{ $customUser->tax_id }}</p>
                @if($customUser->user_type === 'PF')
                <p class="mb-3">{{ $customUser->birth_date?->format('d/m/Y')
                    }}</p>
                @else
                <p class="mb-3">{{
                    $customUser->foundation_date?->format('d/m/Y') }}</p>
                @endif
            </td>
        </tr>
    </table>
</div>

@endsection
