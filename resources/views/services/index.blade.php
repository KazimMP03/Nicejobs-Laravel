{{-- resources/views/services/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Meus Serviços</h1>
    <a href="{{ route('services.create') }}" class="btn btn-primary mb-3">Novo Serviço</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Preço (R$)</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td>{{ $service->title }}</td>
                <td>{{ $service->category->name }}</td>
                <td>{{ number_format($service->price,2,',','.') }}</td>
                <td>{{ ucfirst($service->status) }}</td>
                <td>
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza?')">Apagar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5">Nenhum serviço cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
