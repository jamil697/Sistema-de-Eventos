<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestor de Eventos') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8f9fa; /* Fondo gris muy suave para toda la app */
            font-family: 'Nunito', sans-serif;
        }

        /* NAVBAR ESTILO */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* MARCA (LOGO) */
        .brand-icon {
            width: 45px;
            height: 45px;
            
            border-radius: 12px;
            
        }
        .brand-text {
            line-height: 1.2;
        }
        .brand-title {
            font-weight: 800;
            font-size: 1.1rem;
            color: #212529;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6c757d;
            font-weight: 700;
        }

        /* BOTONES DE LOGIN/REGISTRO */
        .nav-link-login {
            color: #495057;
            font-weight: 700;
            padding: 0.5rem 1.2rem !important;
            transition: color 0.2s;
        }
        .nav-link-login:hover {
            color: #0d6efd;
        }

        .btn-register-custom {
            background-color: #0d6efd;
            color: #fff !important;
            border-radius: 50px; /* Pill shape */
            padding: 0.5rem 1.5rem !important;
            font-weight: 700;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-register-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
            background-color: #0b5ed7;
        }

        /* PERFIL DE USUARIO */
        .user-avatar-nav {
            border: 2px solid #e9ecef;
            transition: border-color 0.2s;
        }
        .dropdown:hover .user-avatar-nav {
            border-color: #0d6efd;
        }

        /* NOTIFICACIONES */
        .badge-notify {
            position: absolute;
            top: 5px;
            right: 0px;
            border: 2px solid #fff;
        }
        
        /* DROPDOWN ITEMS */
        .dropdown-item {
            padding: 10px 20px;
            font-weight: 600;
            color: #495057;
            border-radius: 6px;
            margin: 0 5px;
            width: auto;
        }
        .dropdown-item:hover {
            background-color: #f0f7ff;
            color: #0d6efd;
        }
        .dropdown-item i {
            margin-right: 8px;
            color: #adb5bd;
        }
        .dropdown-item:hover i {
            color: #0d6efd;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 8px 0;
            margin-top: 15px !important; /* Espacio extra */
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm py-3">
            <div class="container">

                {{-- 1. MARCA (LOGO MEJORADO) --}}
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
    
    {{-- AGREGUÉ 'me-3' AQUÍ EN LAS CLASES --}}
                    <div class="brand-icon shadow-sm p-0 overflow-hidden me-3"> 
                        <img src="{{ asset('images/logo-muni.png') }}" 
                            alt="Logo Municipalidad" 
                            class="w-100 h-100"
                            style="object-fit: cover;"> 
                    </div>

                    <div class="brand-text d-flex flex-column">
                        <span class="brand-title">DESCUBRE LOS MEJORES EVENTOS Y PARTICIPA</span>
                        <span class="brand-subtitle">MUNICIPALIDAD PROVINCIAL DE HUÁNUCO</span>
                    </div>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        {{-- Aquí puedes poner links generales si quieres --}}
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item me-2">
                                    <a class="nav-link nav-link-login" href="{{ route('login') }}">
                                        {{ __('Iniciar Sesión') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn-register-custom" href="{{ route('register') }}">
                                        {{ __('Registrarse') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            {{-- Lógica de Usuario Autenticado --}}
                            @php
                                $user = Auth::user();
                                $esAdmin = $user->email === env('ADMIN_EMAIL');
                                $unreadCount = !$esAdmin ? $user->unreadNotifications()->count() : 0;
                            @endphp

                            {{-- Link directo a notificaciones (fuera del dropdown para acceso rápido) --}}
                            @if(!$esAdmin)
                                <li class="nav-item me-3 position-relative">
                                    <a href="{{ route('notifications.index') }}" class="nav-link text-secondary" style="font-size: 1.2rem;">
                                        <i class="bi bi-bell"></i>
                                        @if($unreadCount > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            {{-- Dropdown de Usuario --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="d-flex align-items-center">
                                        {{-- Nombre a la izquierda --}}
                                        <span class="fw-bold me-2 text-dark d-none d-lg-block">{{ $user->name }}</span>
                                        {{-- Avatar generado --}}
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&size=40" 
                                             class="rounded-circle user-avatar-nav" width="40" height="40">
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="navbarDropdown">
                                    
                                    <div class="px-4 py-2 border-bottom mb-2 d-lg-none">
                                        <span class="fw-bold d-block">{{ $user->name }}</span>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>

                                    {{-- MENÚ DE ADMIN --}}
                                    @if($esAdmin)
                                        <h6 class="dropdown-header text-uppercase small fw-bold">Administración</h6>
                                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                                            <i class="bi bi-people-fill"></i> Gestionar Usuarios
                                        </a>
                                        <a class="dropdown-item" href="{{ route('events.index') }}">
                                            <i class="bi bi-calendar-check-fill"></i> Gestionar Eventos
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif

                                    {{-- MENÚ DE CIUDADANO --}}
                                    @if(!$esAdmin)
                                        <h6 class="dropdown-header text-uppercase small fw-bold">Mi Cuenta</h6>
                                        <a class="dropdown-item" href="{{ route('mis-eventos') }}">
                                            <i class="bi bi-ticket-detailed-fill"></i> Mis Inscripciones
                                        </a>
                                        <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                            <i class="bi bi-bell-fill"></i> Notificaciones
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif

                                    {{-- CERRAR SESIÓN --}}
                                    <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right text-danger"></i> {{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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