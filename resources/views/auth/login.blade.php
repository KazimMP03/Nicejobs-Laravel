<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NiceJobs</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="lado-esquerdo">
            <h1>NiceJobs</h1>
            <img src="{{ asset('images/logo.png') }}" alt="Logo da Empresa">
        </div>
        <div class="lado-direito">
            <h1>Login</h1>
            <h2>Faça login no NiceJobs</h2>
            @if($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="text" name="email" placeholder="Digite seu email" required>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
                <button type="submit">Entrar</button>
            </form>

            <div class="separador"></div>
            
            <div class="cadastro-container">
                <p>Não tem uma conta? <a href="">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</body>
</html>
