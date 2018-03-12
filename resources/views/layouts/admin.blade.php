<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"> Sistem Informasi Rapor </a>
        
        <!-- Hamburger bar that only appears if the screen is too narrow -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item">
                    <a class="nav-link @if(isset($current_page) && $current_page === "semesters") active @endif" href="{{ route("semesters.index") }}"> Semester </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(isset($current_page) && $current_page === "rooms") active @endif" href="{{ route("rooms.index") }}"> Ruangan </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(isset($current_page) && $current_page === "room_semesters") active @endif" href="{{ route("room_semesters.index") }}"> Kelas </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(isset($current_page) && $current_page === "students") active @endif" href="{{ route("students.index") }}"> Siswa </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> Guru </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#"> Nilai </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div style="height: 30px"></div>
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
