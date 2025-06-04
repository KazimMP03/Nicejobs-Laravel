<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>NiceJob - Home</title>
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

    <body>
        <div class="container-xxl position-relative bg-white d-flex p-0">
            @include('components.sidebar')

            <div class="content">
                @include('components.header')

                <!-- Content Start -->
                <div class="container-fluid pt-4 px-4">
                    <div
                        class="row bg-light rounded mx-0 d-flex justify-content-center p-4"
                        style="min-height: calc(100vh - 185px);">

                        <main>
                            @yield('content')
                        </main>
                    </div>

                </div>
                <!-- Content End -->

                @include('components.footer')
            </div>
        </div>

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
