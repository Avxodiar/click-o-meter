<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Click-o-Meter</title>

    <!-- App core CSS (Bootstrap)-->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles (carousel) for this template -->
    <link href="{{ mix('css/style.css') }}" rel="stylesheet">
</head>
<body>
<header>
    @section('header')
        @include('header')
    @show
</header>
<main role="main">

    @yield('content')

    <hr class="featurette-divider my-5">

    <!-- FOOTER -->
    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>&copy; 2021 Avxodiar. &middot;
            <a href="https://avxodiar.github.io/portfolio/" target="_blank" rel="nofollow">Портфолио</a>
        </p>
    </footer>
</main>

</body>
</html>
