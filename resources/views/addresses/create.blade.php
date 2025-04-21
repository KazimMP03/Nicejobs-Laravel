@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/create-provider.css') }}">
    <div class="address-container">
        <div class="form-header">
            <h1 class="form-title">Cadastro de Endereço</h1>
            <p class="form-description">Preencha os dados do novo endereço</p>
        </div>

        <form method="POST" action="{{ route('addresses.store') }}" class="address-form">
            @csrf

            <!-- Linha 1 -->
            <div class="form-group">
                <label for="cep" class="form-label">CEP*</label>
                <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep"
                    value="{{ old('cep') }}" required oninput="formatarCEP(this)" onblur="buscarEnderecoViaCEP()">
                @error('cep')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="logradouro" class="form-label">Logradouro*</label>
                <input id="logradouro" type="text" class="form-control @error('logradouro') is-invalid @enderror"
                    name="logradouro" value="{{ old('logradouro') }}" required>
                @error('logradouro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="numero" class="form-label">Número*</label>
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
                <label for="bairro" class="form-label">Bairro*</label>
                <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                    value="{{ old('bairro') }}" required>
                @error('bairro')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="cidade" class="form-label">Cidade*</label>
                <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade"
                    value="{{ old('cidade') }}" required>
                @error('cidade')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- Linha 3 - Versão simplificada -->
            <div class="form-group">
                <label for="estado" class="form-label">Estado*</label>
                <select id="estado" class="form-control @error('estado') is-invalid @enderror" name="estado" required>
                    <option value="">Selecione...</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>
            </div>
            @error('estado')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>

    <!-- Botões centralizados -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Salvar Endereço</button>
        <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
    </form>
    </div>

    <script src="{{ asset('js/via-cep.js') }}"></script>
@endsection