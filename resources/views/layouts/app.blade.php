<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestor de Eventos') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar-brand-title {
            font-weight: 700;
            letter-spacing: .03em;
        }
        .navbar-subtitle {
            font-size: .75rem;
            color: #6c757d;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 999px;
            border: none;
            color: #fff !important;
            padding: .45rem 1.4rem;
            font-weight: 600;
        }
        .btn-gradient:hover {
            opacity: .9;
            color: #fff !important;
        }
        .nav-notification-badge {
            font-size: .7rem;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                {{-- Marca a la izquierda (cámbiala o quítala si quieres) --}}
                <a class="navbar-brand d-flex flex-column justify-content-center" href="{{ url('/') }}">
                    <span class="navbar-brand-title text-primary">Gestor de Eventos</span>
                    <span class="navbar-subtitle">Municipalidad</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- Enlaces generales opcionales --}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item me-2">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- Usuario autenticado --}}
                            <li class="nav-item dropdown">
                                @php
                                    $esAdmin = auth()->user()->email === env('ADMIN_EMAIL');
                                    $unreadCount = !$esAdmin
                                        ? auth()->user()->unreadNotifications()->count()
                                        : 0;
                                @endphp

                                <a id="navbarDropdown"
                                   class="nav-link dropdown-toggle d-flex align-items-center"
                                   href="#" role="button" data-bs-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="me-2 fw-semibold">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    {{-- Opciones de ADMIN --}}
                                    @if($esAdmin)
                                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                                            👥 Usuarios
                                        </a>

                                        <div class="dropdown-divider"></div>
                                    @endif

                                    @if($esAdmin)
                                        <a class="dropdown-item" href="{{ route('events.index') }}">
                                            📅 Eventos
                                        </a>

                                        <div class="dropdown-divider"></div>
                                    @endif

                                    {{-- Opciones de CIUDADANO --}}
                                    @if(!$esAdmin)
                                        <a class="dropdown-item" href="{{ route('mis-eventos') }}">
                                            📅 Mis eventos
                                        </a>

                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                           href="{{ route('notifications.index') }}">
                                            <span>🔔 Notificaciones</span>
                                            @if($unreadCount > 0)
                                                <span class="badge bg-danger nav-notification-badge">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </a>

                                        <div class="dropdown-divider"></div>
                                    @endif

                                    {{-- Botón logout degradé --}}
                                    <a class="dropdown-item text-center btn-gradient mt-1"
                                       href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
