@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Escolha uma categoria de serviço</h2>

    <ul class="list-group">
        @foreach($categories as $category)
            <li class="list-group-item">
                <a href="{{ route('explore.byCategory', $category->id) }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
