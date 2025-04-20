@extends('layouts.app') {{-- Estende o layout base 'app.blade.php' --}}

@section('content') {{-- Inicia a seção de conteúdo --}}
<div class="container"> {{-- Container principal do Bootstrap --}}
    <div class="row justify-content-center"> {{-- Linha com conteúdo centralizado --}}
        <div class="col-md-8"> {{-- Coluna com 8 colunas de largura em telas médias --}}
            <div class="card"> {{-- Card que envolve todo o conteúdo --}}
                {{-- Cabeçalho do card com título e botão --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Meus Endereços</h5> {{-- Título da página --}}
                    {{-- Botão para adicionar novo endereço --}}
                    <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Endereço {{-- Ícone + texto --}}
                    </a>
                </div>

                <div class="card-body"> {{-- Corpo do card --}}
                    {{-- Seção para exibir mensagens de sucesso --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }} {{-- Exibe a mensagem flash de sucesso --}}
                        </div>
                    @endif

                    {{-- Verifica se a lista de endereços está vazia --}}
                    @if($addresses->isEmpty())
                        <div class="alert alert-info">
                            Você ainda não cadastrou nenhum endereço. {{-- Mensagem para lista vazia --}}
                        </div>
                    @else
                        {{-- Lista de endereços --}}
                        <div class="list-group">
                            {{-- Loop através de cada endereço --}}
                            @foreach($addresses as $address)
                                <div class="list-group-item"> {{-- Item da lista --}}
                                    <div class="d-flex justify-content-between"> {{-- Container flexível --}}
                                        <div> {{-- Div com informações do endereço --}}
                                            {{-- Linha com logradouro e número --}}
                                            <h6>{{ $address->logradouro }}, {{ $address->numero }}</h6>
                                            {{-- Linha com bairro, cidade e estado --}}
                                            <p class="mb-1">
                                                {{ $address->bairro }} - {{ $address->cidade }}/{{ $address->estado }}
                                            </p>
                                            {{-- CEP --}}
                                            <small class="text-muted">CEP: {{ $address->cep }}</small>
                                            {{-- Complemento (se existir) --}}
                                            @if($address->complemento)
                                                <br><small class="text-muted">Complemento: {{ $address->complemento }}</small>
                                            @endif
                                        </div>
                                        {{-- Grupo de botões de ação --}}
                                        <div class="btn-group">
                                            {{-- Botão de edição --}}
                                            <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> {{-- Ícone de edição --}}
                                            </a>
                                            {{-- Formulário para exclusão --}}
                                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" style="display: inline;">
                                                @csrf {{-- Token CSRF para proteção --}}
                                                @method('DELETE') {{-- Método HTTP spoofing para DELETE --}}
                                                {{-- Botão de exclusão com confirmação --}}
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este endereço?')">
                                                    <i class="fas fa-trash"></i> {{-- Ícone de lixeira --}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Fim da seção de conteúdo --}}
@endsection 