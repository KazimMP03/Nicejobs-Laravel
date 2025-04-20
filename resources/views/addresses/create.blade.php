@extends('layouts.app')

@section('content')
    {{-- Container principal --}}
    <div class="container">
        {{-- Linha centralizada --}}
        <div class="row justify-content-center">
            {{-- Coluna com largura média (8 colunas em dispositivos médios) --}}
            <div class="col-md-8">
                {{-- Card que envolve todo o formulário --}}
                <div class="card">
                    {{-- Cabeçalho do card com título --}}
                    <div class="card-header">
                        <h5 class="mb-0">Cadastrar Novo Endereço</h5>
                    </div>

                    {{-- Corpo do card --}}
                    <div class="card-body">
                        {{-- Formulário que envia dados via POST para a rota addresses.store --}}
                        <form method="POST" action="{{ route('addresses.store') }}">
                            {{-- Token CSRF para proteção contra ataques --}}
                            @csrf

                            {{-- Campo CEP --}}
                            <div class="row mb-3">
                                <label for="cep" class="col-md-4 col-form-label text-md-end">CEP</label>
                                <div class="col-md-6">
                                    {{-- Input com validação, máscara e busca automática --}}
                                    <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror"
                                        name="cep" value="{{ old('cep') }}" required autocomplete="postal-code"
                                        oninput="formatarCEP(this)" onblur="buscarEnderecoViaCEP()">
                                    {{-- Exibição de erros de validação --}}
                                    @error('cep')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Logradouro --}}
                            <div class="row mb-3">
                                <label for="logradouro" class="col-md-4 col-form-label text-md-end">Logradouro</label>
                                <div class="col-md-6">
                                    <input id="logradouro" type="text"
                                        class="form-control @error('logradouro') is-invalid @enderror" name="logradouro"
                                        value="{{ old('logradouro') }}" required>
                                    @error('logradouro')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Número --}}
                            <div class="row mb-3">
                                <label for="numero" class="col-md-4 col-form-label text-md-end">Número</label>
                                <div class="col-md-6">
                                    <input id="numero" type="text"
                                        class="form-control @error('numero') is-invalid @enderror" name="numero"
                                        value="{{ old('numero') }}" required>
                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Complemento (opcional) --}}
                            <div class="row mb-3">
                                <label for="complemento" class="col-md-4 col-form-label text-md-end">Complemento</label>
                                <div class="col-md-6">
                                    <input id="complemento" type="text"
                                        class="form-control @error('complemento') is-invalid @enderror" name="complemento"
                                        value="{{ old('complemento') }}">
                                    @error('complemento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Bairro --}}
                            <div class="row mb-3">
                                <label for="bairro" class="col-md-4 col-form-label text-md-end">Bairro</label>
                                <div class="col-md-6">
                                    <input id="bairro" type="text"
                                        class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                                        value="{{ old('bairro') }}" required>
                                    @error('bairro')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Cidade --}}
                            <div class="row mb-3">
                                <label for="cidade" class="col-md-4 col-form-label text-md-end">Cidade</label>
                                <div class="col-md-6">
                                    <input id="cidade" type="text"
                                        class="form-control @error('cidade') is-invalid @enderror" name="cidade"
                                        value="{{ old('cidade') }}" required>
                                    @error('cidade')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Estado (select com todos os estados do Brasil) --}}
                            <div class="row mb-3">
                                <label for="estado" class="col-md-4 col-form-label text-md-end">Estado</label>
                                <div class="col-md-6">
                                    <select id="estado" class="form-control @error('estado') is-invalid @enderror"
                                        name="estado" required>
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
                                    @error('estado')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Botões de ação --}}
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    {{-- Botão para submeter o formulário --}}
                                    <button type="submit" class="btn btn-primary">
                                        Salvar Endereço
                                    </button>
                                    {{-- Link para cancelar e voltar à lista de endereços --}}
                                    <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inclusão do script JavaScript para manipulação do CEP --}}
    <script src="{{ asset('js/via-cep.js') }}"></script>
@endsection