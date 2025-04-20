@extends('layouts.app') {{-- Estende o layout base da aplicação --}}

@section('content') {{-- Inicia a seção de conteúdo --}}
<div class="container"> {{-- Container principal do Bootstrap --}}
    <div class="row justify-content-center"> {{-- Linha com conteúdo centralizado --}}
        <div class="col-md-8"> {{-- Coluna com 8 colunas de largura em telas médias --}}
            <div class="card"> {{-- Card que envolve o formulário --}}
                <div class="card-header"> {{-- Cabeçalho do card --}}
                    <h5 class="mb-0">Editar Endereço</h5> {{-- Título da página --}}
                </div>

                <div class="card-body"> {{-- Corpo do card --}}
                    {{-- Formulário de edição com método PUT --}}
                    <form method="POST" action="{{ route('addresses.update', $address->id) }}">
                        @csrf {{-- Token CSRF para proteção contra ataques --}}
                        @method('PUT') {{-- Diretiva para simular o método HTTP PUT --}}

                        {{-- Campo CEP --}}
                        <div class="row mb-3"> {{-- Linha do formulário --}}
                            <label for="cep" class="col-md-4 col-form-label text-md-end">CEP</label> {{-- Label --}}
                            <div class="col-md-6">
                                {{-- Input com validação, máscara e busca automática --}}
                                <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror"
                                    name="cep" value="{{ old('cep', $address->cep) }}" required autocomplete="postal-code"
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
                                    value="{{ old('logradouro', $address->logradouro) }}" required>
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
                                    value="{{ old('numero', $address->numero) }}" required>
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
                                    value="{{ old('complemento', $address->complemento) }}">
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
                                    value="{{ old('bairro', $address->bairro) }}" required>
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
                                    value="{{ old('cidade', $address->cidade) }}" required>
                                @error('cidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Campo Estado (select) --}}
                        <div class="row mb-3">
                            <label for="estado" class="col-md-4 col-form-label text-md-end">Estado</label>
                            <div class="col-md-6">
                                <select id="estado" class="form-control @error('estado') is-invalid @enderror"
                                    name="estado" required>
                                    <option value="">Selecione...</option>
                                    {{-- Lista de todos os estados brasileiros --}}
                                    {{-- Cada option verifica se deve ser selecionado com base no valor antigo ou atual --}}
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
                                @error('estado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Botões de ação --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4"> {{-- Offset para alinhamento --}}
                                <button type="submit" class="btn btn-primary">
                                    Atualizar Endereço {{-- Botão de submissão --}}
                                </button>
                                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                                    Cancelar {{-- Botão para voltar sem salvar --}}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Inclui o script JavaScript para manipulação do CEP --}}
<script src="{{ asset('js/via-cep.js') }}"></script>
{{-- Fim da seção de conteúdo --}}
@endsection 