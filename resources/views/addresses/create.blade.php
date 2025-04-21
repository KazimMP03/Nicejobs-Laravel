@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/address/create.css') }}">

    <div class="address-container">
        <div class="form-header">
            <h1 class="form-title">Cadastro de Endereço</h1>
            <p class="form-description">Preencha os dados do novo endereço</p>
        </div>

        <form method="POST" action="{{ route('addresses.store') }}" class="address-form">
            @csrf

            <!-- Linha 1 -->
            <div class="form-group">
                <label for="cep" class="form-label required">CEP</label>
                <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep"
                    value="{{ old('cep') }}" required oninput="formatarCEP(this)" onblur="buscarEnderecoViaCEP()">
                @error('cep')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="logradouro" class="form-label required">Logradouro</label>
                <input id="logradouro" type="text" class="form-control @error('logradouro') is-invalid @enderror"
                    name="logradouro" value="{{ old('logradouro') }}" required>
                @error('logradouro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="numero" class="form-label required">Número</label>
                <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero"
                    value="{{ old('numero') }}" required>
                @error('numero')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Linha 2 -->
            <div class="form-group">
                <label for="complemento" class="form-label">Complemento</label>
                <input id="complemento" type="text" class="form-control @error('complemento') is-invalid @enderror"
                    name="complemento" value="{{ old('complemento') }}">
                @error('complemento')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="bairro" class="form-label required">Bairro</label>
                <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                    value="{{ old('bairro') }}" required>
                @error('bairro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="cidade" class="form-label required">Cidade</label>
                <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade"
                    value="{{ old('cidade') }}" required>
                @error('cidade')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Linha 3 -->
            <div class="form-group">
                <label for="estado" class="form-label required">Estado</label>
                <select id="estado" class="form-select @error('estado') is-invalid @enderror" name="estado" required>
                    <option value="">Selecione...</option>
                    @foreach ([
                        'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá', 'AM'=>'Amazonas', 'BA'=>'Bahia',
                        'CE'=>'Ceará', 'DF'=>'Distrito Federal', 'ES'=>'Espírito Santo', 'GO'=>'Goiás',
                        'MA'=>'Maranhão', 'MT'=>'Mato Grosso', 'MS'=>'Mato Grosso do Sul', 'MG'=>'Minas Gerais',
                        'PA'=>'Pará', 'PB'=>'Paraíba', 'PR'=>'Paraná', 'PE'=>'Pernambuco', 'PI'=>'Piauí',
                        'RJ'=>'Rio de Janeiro', 'RN'=>'Rio Grande do Norte', 'RS'=>'Rio Grande do Sul',
                        'RO'=>'Rondônia', 'RR'=>'Roraima', 'SC'=>'Santa Catarina', 'SP'=>'São Paulo',
                        'SE'=>'Sergipe', 'TO'=>'Tocantins'
                    ] as $sigla => $estado)
                        <option value="{{ $sigla }}" {{ old('estado') == $sigla ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
                @error('estado')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Botões -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/via-cep.js') }}"></script>
@endsection
