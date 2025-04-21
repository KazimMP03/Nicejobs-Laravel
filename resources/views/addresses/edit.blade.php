@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/address/edit.css') }}">
    <div class="address-container">
        <div class="form-header">
            <h1 class="form-title">Editar Endereço</h1>
            <p class="form-description">Atualize os dados do endereço</p>
        </div>

        <form method="POST" action="{{ route('addresses.update', $address->id) }}" class="address-form">
            @csrf
            @method('PUT')

            <!-- Linha 1 -->
            <div class="form-group">
                <label for="cep" class="form-label required">CEP</label>
                <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep"
                    value="{{ old('cep', $address->cep) }}" required oninput="formatarCEP(this)"
                    onblur="buscarEnderecoViaCEP()">
                @error('cep')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="logradouro" class="form-label required">Logradouro</label>
                <input id="logradouro" type="text" class="form-control @error('logradouro') is-invalid @enderror"
                    name="logradouro" value="{{ old('logradouro', $address->logradouro) }}" required>
                @error('logradouro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="numero" class="form-label required">Número</label>
                <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero"
                    value="{{ old('numero', $address->numero) }}" required>
                @error('numero')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Linha 2 -->
            <div class="form-group">
                <label for="complemento" class="form-label">Complemento</label>
                <input id="complemento" type="text" class="form-control @error('complemento') is-invalid @enderror"
                    name="complemento" value="{{ old('complemento', $address->complemento) }}">
                @error('complemento')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="bairro" class="form-label required">Bairro</label>
                <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                    value="{{ old('bairro', $address->bairro) }}" required>
                @error('bairro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="cidade" class="form-label required">Cidade</label>
                <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade"
                    value="{{ old('cidade', $address->cidade) }}" required>
                @error('cidade')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Linha 3 -->
            <div class="form-group">
                <label for="estado" class="form-label required">Estado</label>
                <select id="estado" class="form-select @error('estado') is-invalid @enderror" name="estado" required>
                    <option value="AC" @if(old('estado', $address->estado) == 'AC') selected @endif>Acre</option>
                    <option value="AL" @if(old('estado', $address->estado) == 'AL') selected @endif>Alagoas</option>
                    <option value="AP" @if(old('estado', $address->estado) == 'AP') selected @endif>Amapá</option>
                    <option value="AM" @if(old('estado', $address->estado) == 'AM') selected @endif>Amazonas</option>
                    <option value="BA" @if(old('estado', $address->estado) == 'BA') selected @endif>Bahia</option>
                    <option value="CE" @if(old('estado', $address->estado) == 'CE') selected @endif>Ceará</option>
                    <option value="DF" @if(old('estado', $address->estado) == 'DF') selected @endif>Distrito Federal</option>
                    <option value="ES" @if(old('estado', $address->estado) == 'ES') selected @endif>Espírito Santo</option>
                    <option value="GO" @if(old('estado', $address->estado) == 'GO') selected @endif>Goiás</option>
                    <option value="MA" @if(old('estado', $address->estado) == 'MA') selected @endif>Maranhão</option>
                    <option value="MT" @if(old('estado', $address->estado) == 'MT') selected @endif>Mato Grosso</option>
                    <option value="MS" @if(old('estado', $address->estado) == 'MS') selected @endif>Mato Grosso do Sul</option>
                    <option value="MG" @if(old('estado', $address->estado) == 'MG') selected @endif>Minas Gerais</option>
                    <option value="PA" @if(old('estado', $address->estado) == 'PA') selected @endif>Pará</option>
                    <option value="PB" @if(old('estado', $address->estado) == 'PB') selected @endif>Paraíba</option>
                    <option value="PR" @if(old('estado', $address->estado) == 'PR') selected @endif>Paraná</option>
                    <option value="PE" @if(old('estado', $address->estado) == 'PE') selected @endif>Pernambuco</option>
                    <option value="PI" @if(old('estado', $address->estado) == 'PI') selected @endif>Piauí</option>
                    <option value="RJ" @if(old('estado', $address->estado) == 'RJ') selected @endif>Rio de Janeiro</option>
                    <option value="RN" @if(old('estado', $address->estado) == 'RN') selected @endif>Rio Grande do Norte</option>
                    <option value="RS" @if(old('estado', $address->estado) == 'RS') selected @endif>Rio Grande do Sul</option>
                    <option value="RO" @if(old('estado', $address->estado) == 'RO') selected @endif>Rondônia</option>
                    <option value="RR" @if(old('estado', $address->estado) == 'RR') selected @endif>Roraima</option>
                    <option value="SC" @if(old('estado', $address->estado) == 'SC') selected @endif>Santa Catarina</option>
                    <option value="SP" @if(old('estado', $address->estado) == 'SP') selected @endif>São Paulo</option>
                    <option value="SE" @if(old('estado', $address->estado) == 'SE') selected @endif>Sergipe</option>
                    <option value="TO" @if(old('estado', $address->estado) == 'TO') selected @endif>Tocantins</option>
                </select>
                @error('estado')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Botões -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/via-cep.js') }}"></script>
@endsection