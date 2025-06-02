<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <title>NiceJob - Recuperar Senha</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Favicon -->
        <link rel="icon" type="image/png"
            href="{{ asset('images/logo_favicon.png') }}">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
            rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
            rel="stylesheet">

        <!-- Libraries Stylesheet (mantido local para preservar estilos) -->
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}"
            rel="stylesheet">
        <link
            href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}"
            rel="stylesheet" />

        <!-- Bootstrap Local (para evitar mudança visual) -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>

    <body class="bg-white">
        <div class="container-xxl position-relative bg-white d-flex p-0">

            <!-- Formulário de Recuperação -->
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center"
                    style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">

                            <div
                                class="d-flex align-items-center justify-content-between mb-4">
                                <a href="{{ route('login') }}">
                                    <img
                                        src="{{ asset('images/logo_big_rect_round.png') }}"
                                        alt="NiceJob Logo"
                                        style="height: 35px;">
                                </a>
                                <h3
                                    class="mb-0 fw-bold text-secondary fs-5">Recuperar
                                    Senha</h3>
                            </div>

                            <!-- Mensagens -->
                            @if(session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger text-center">
                                <ul class="mb-0" style="list-style: none;">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Formulário -->
                            <form action="{{ route('password.email') }}"
                                method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email"
                                        class="form-control" id="emailInput"
                                        placeholder="nome@exemplo.com"
                                        value="{{ old('email') }}" required>
                                    <label for="emailInput">E-mail
                                        cadastrado</label>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary w-100 py-3 fw-bold fs-5">Enviar
                                    link de
                                    redefinição</button>
                            </form>

                            <p class="text-center mt-3"
                                style="font-size: 15px;">
                                <a href="{{ route('login') }}"
                                    class="fw-bold text-decoration-none">
                                    <i class="fa fa-arrow-left me-1"></i>Voltar
                                    para o login
                                </a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS -->
        <!-- JavaScript Libraries via CDN -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

        <!-- Bibliotecas locais mantidas por estilo/compatibilidade -->
        <script
            src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('js/main.js') }}"></script>
    </body>

</html>