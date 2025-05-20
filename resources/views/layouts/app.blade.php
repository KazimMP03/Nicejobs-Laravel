<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NiceJobs</title>
    <!-- Favicon (ícone da aba do navegador) -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

</body>
</html>
