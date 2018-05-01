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
    @yield("styles")
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"> Sistem Informasi Rapor </a>
        
            <!-- Hamburger bar that only appears if the screen is too narrow -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    @if(auth()->user()->privilege == 'administrator')

                    <li class="nav-item">
                        <a class="nav-link @if(isset($current_page) && $current_page === "terms") active @endif" href="{{ route("terms.index") }}"> Tahun Ajaran </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(isset($current_page) && $current_page === "rooms") active @endif" href="{{ route("rooms.index") }}"> Ruangan </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(isset($current_page) && $current_page === "students") active @endif" href="{{ route("students.index") }}"> Siswa </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Guru </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(isset($current_page) && $current_page === "courses") active @endif" href="{{ route('courses.term_index') }}"> Mapel </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(isset($current_page) && $current_page === "course_teachers") active @endif" href="{{ route('course_teachers.term_index') }}"> Guru Mapel </a>
                    </li>

                    @endif

                </ul>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        Log Out
                        <i class="fa fa-sign-out"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container">
        
        <div style="height: 50px">

        </div>

        <div class="alert alert-info">
            Anda log in dengan akun milik <strong> {{ auth()->user()->name }} </strong> dengan status <strong> {{ \App\User::PRIVILEGE[auth()->user()->privilege] }} </strong>
        </div>

        <div style="height: 30px"></div>
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
