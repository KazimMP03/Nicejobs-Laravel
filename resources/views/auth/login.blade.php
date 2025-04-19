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
            
            <!-- Mensagens de Sucesso -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Mensagens de Erro -->
            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Digite seu email" required value="{{ old('email') }}">
                <input type="password" name="password" placeholder="Digite sua senha" required>
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Lembrar de mim</label>
                </div>
                <button type="submit">Entrar</button>
            </form>

            <div class="separador"></div>
            
            <div class="cadastro-container">
                <p>Não tem uma conta? 
                    <a href="{{ route('register.provider.form') }}">Sou Prestador</a> ou 
                    <a href="{{ route('register.custom-user.form') }}">Sou Cliente</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>