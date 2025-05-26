<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <title>NiceJobssssssssss - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords"
            content="serviços, contratação, autônomos, plataforma, NiceJob, prestadores">
        <meta name="description"
            content="Plataforma para contratar prestadores de serviço com segurança, avaliações e agilidade. Encontre o profissional ideal no NiceJob.">

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

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}"
            rel="stylesheet">
        <link
            href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}"
            rel="stylesheet" />

        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>

    <body class="bg-white">
        <div class="container-xxl position-relative bg-white d-flex p-0">

            <!-- Spinner Start -->
            <div id="spinner"
                class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary"
                    style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
            </div>
            <!-- Spinner End -->

            <!-- Sign In Start -->
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center"
                    style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div
                                class="d-flex align-items-center justify-content-between mb-4">
                                <a href class>
                                    <img
                                        src="{{ asset('images/logo_big_rect_round.png') }}"
                                        alt="NiceJob Logo"
                                        style="height: 45px;">
                                </a>
                                <h3
                                    class="mb-0 fw-bold text-secondary">Login</h3>
                            </div>

                            <!-- Mensagens de Sucesso -->
                            @if(session('success'))
                            <div class="alert alert-success mb-3 text-center">
                                {{ session('success') }}
                            </div>
                            @endif

                            <!-- Mensagens de Erro -->
                            @if($errors->any())
                            <div
                                class="alert alert-danger mb-3 py-2 px-3 text-center">
                                <ul
                                    class="mb-0 d-inline-block text-start text-center"
                                    style="list-style: none; padding-left: 0;">
                                    @foreach($errors->all() as $error)
                                    <li class="my-1">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Formulário -->
                            <form action="{{ route('login.post') }}"
                                method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email"
                                        class="form-control" id="floatingInput"
                                        placeholder="nome@exemplo.com"
                                        value="{{ old('email') }}">
                                    <label for="floatingInput">E-mail</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="password" name="password"
                                        class="form-control"
                                        id="floatingPassword"
                                        placeholder="Digite aqui sua senha.">
                                    <label for="floatingPassword">Senha</label>
                                    <span
                                        class="position-absolute top-50 end-0 translate-middle-y me-3"
                                        style="cursor: pointer;"
                                        onclick="togglePassword()">
                                        <i class="fa fa-eye"
                                            id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                                <div
                                    class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="remember" name="remember">
                                        <label class="form-check-label"
                                            for="remember"
                                            style="font-size: 14px;">Manter
                                            conectado</label>
                                    </div>
                                    <a href="{{ route('password.request') }}"
                                        style="font-size: 14px;">Perdeu a
                                        senha?</a>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary py-3 w-100 mb-4 fw-bold fs-5">Entrar</button>
                            </form>
                            <!-- Fim do Formulário -->
                            <p class="text-center mb-0"
                                style="font-size: 15px;"> Ainda não tem uma
                                conta? Cadastre-se como
                                <a href="{{ route('register.provider.form') }}"
                                    class="fw-bold"
                                    style="font-size: 17px;">PRESTADOR</a> ou
                                como <a
                                    href="{{ route('register.custom-user.form') }}"
                                    class="fw-bold"
                                    style="font-size: 17px;">CLIENTE</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign In End -->

        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/chart/chart.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script
            src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="{{ asset('js/main.js') }}"></script>
    </body>

</html>