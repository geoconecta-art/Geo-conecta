<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mapa: {{ $map->name }} </title>
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('img/escudo.png') }}">
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.4.0') }}">
    
    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>    

    @yield('style')
</head>

<body>
    <!-- main @s -->
    <div class="nk-main ">
        <!-- sidebar @s -->
        @include('public._sidebar')
        <!-- sidebar @e -->

        <!-- wrap @s -->
        <div class="nk-wrap ">
            @include('public._header')
            
            <div class="nk-content mt-4">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <div class="nk-footer">
                <div class="container-fluid">
                    <div class="nk-footer-copyright text-center">&copy; Todos los derechos reservados.</div>
                </div>
            </div>
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</body>

<!-- JavaScript -->
<script src="{{ asset('assets/js/bundle.js?ver=2.4.0') }}"></script>
<script src="{{ asset('assets/js/scripts.js?ver=2.4.0') }}"></script>
@yield('script')
</html>
