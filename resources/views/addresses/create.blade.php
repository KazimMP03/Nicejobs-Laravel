@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/address/create.css') }}">

<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        CADASTRO DE ENDEREÇO
    </h3>

    <button type="submit" form="address-form"
        class="btn btn-primary fw-bold">
        <i class="fas fa-save me-2"></i> Salvar
    </button>
</div>

<div class="w-100 d-flex flex-column align-items-center py-5">

    <!-- Form Container -->
    <div class="shadow-sm bg-white rounded px-4 py-4 w-100"
        style="max-width: 1300px;">
        <div class="address-container">
            <div class="form-header" style="margin-bottom: 3rem;">
                <h5 class="form-subtitle">Preencha os dados do novo
                    endereço:</h5>
            </div>

            <form id="address-form" method="POST"
                action="{{ route('addresses.store') }}" class="address-form">

                @csrf

                <!-- Linha 1 -->
                <div class="form-floating mb-4">
                    <input id="cep" type="text"
                        class="form-control @error('cep') is-invalid @enderror"
                        name="cep"
                        value="{{ old('cep') }}"
                        placeholder="CEP" required
                        oninput="formatarCEP(this)"
                        onblur="buscarEnderecoViaCEP()">
                    <label for="cep">CEP</label>
                    @error('cep')<div class="invalid-feedback">{{ $message
                        }}</div>@enderror
                </div>

                <div class="form-floating form-group">
                    <input id="logradouro" type="text"
                        class="form-control @error('logradouro') is-invalid @enderror"
                        name="logradouro"
                        value="{{ old('logradouro') }}"
                        placeholder="Logradouro"
                        required>
                    <label for="logradouro">Logradouro</label>
                    @error('logradouro')<div class="invalid-feedback">{{
                        $message }}</div>@enderror
                </div>

                <div class="form-floating form-group">
                    <input id="numero" type="text"
                        class="form-control @error('numero') is-invalid @enderror"
                        name="numero"
                        value="{{ old('numero') }}"
                        placeholder="Número"
                        required>
                    <label for="numero">Número</label>
                    @error('numero')<div class="invalid-feedback">{{ $message
                        }}</div>@enderror
                </div>

                <!-- Linha 2 -->
                <div class="form-floating form-group">
                    <input id="complemento" type="text"
                        class="form-control @error('complemento') is-invalid @enderror"
                        name="complemento"
                        value="{{ old('complemento') }}"
                        placeholder="Complemento">
                    <label for="complemento">Complemento</label>
                    @error('complemento')<div class="invalid-feedback">{{
                        $message }}</div>@enderror
                </div>

                <div class="form-floating form-group">
                    <input id="bairro" type="text"
                        class="form-control @error('bairro') is-invalid @enderror"
                        name="bairro"
                        value="{{ old('bairro') }}"
                        placeholder="Bairro"
                        required>
                    <label for="bairro">Bairro</label>
                    @error('bairro')<div class="invalid-feedback">{{ $message
                        }}</div>@enderror
                </div>

                <div class="form-floating form-group">
                    <input id="cidade" type="text"
                        class="form-control @error('cidade') is-invalid @enderror"
                        name="cidade"
                        value="{{ old('cidade') }}"
                        placeholder="Cidade"
                        required>
                    <label for="cidade">Cidade</label>
                    @error('cidade')<div class="invalid-feedback">{{ $message
                        }}</div>@enderror
                </div>

                <!-- Linha 3 -->
                <div class="form-group">
                    <label for="estado"
                        class="form-label">Estado:</label>
                    <select id="estado"
                        class="form-select @error('estado') is-invalid @enderror"
                        name="estado" required>
                        <option value>Selecione...</option>
                        @foreach ([
                        'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá',
                        'AM'=>'Amazonas', 'BA'=>'Bahia',
                        'CE'=>'Ceará', 'DF'=>'Distrito Federal',
                        'ES'=>'Espírito Santo', 'GO'=>'Goiás',
                        'MA'=>'Maranhão', 'MT'=>'Mato Grosso',
                        'MS'=>'Mato Grosso do Sul', 'MG'=>'Minas Gerais',
                        'PA'=>'Pará', 'PB'=>'Paraíba', 'PR'=>'Paraná',
                        'PE'=>'Pernambuco', 'PI'=>'Piauí',
                        'RJ'=>'Rio de Janeiro', 'RN'=>'Rio Grande do Norte',
                        'RS'=>'Rio Grande do Sul',
                        'RO'=>'Rondônia', 'RR'=>'Roraima',
                        'SC'=>'Santa Catarina', 'SP'=>'São Paulo',
                        'SE'=>'Sergipe', 'TO'=>'Tocantins'
                        ] as $sigla => $estado)
                        <option value="{{ $sigla }}" {{ old('estado') == $sigla
                            ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message
                        }}</div>@enderror
                </div>

            </form>
        </div>

    </div>
    <!-- Botão Cancelar -->
    <div class="w-100 mt-5 px-4 d-flex justify-content-start">
        <a href="{{ route('addresses.index') }}"
            class="btn btn-cancel fw-bold">
            <i class="fas fa-arrow-left me-2"></i> Cancelar
        </a>
    </div>
    <script src="{{ asset('js/via-cep.js') }}"></script>

    @endsection
