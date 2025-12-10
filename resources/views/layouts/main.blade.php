<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Proyecto')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            @auth
            <a class="navbar-brand d-flex align-items-center rounded" href="{{ route('home') }}">
                    <img src="{{ asset('img/frontend/logo.jpg') }}" 
                        alt="Logo" 
                        class="rounded-circle"
                        style="height: 40px; width: 40px; object-fit: cover;">
                    <span class="ms-2">PadelGo</span>
                </a>


            
            @endauth
            @guest
                <a class="navbar-brand d-flex align-items-center rounded" href="{{ route('home') }}">
                    <img src="{{ asset('img/frontend/logo.jpg') }}" 
                        alt="Logo" 
                        class="rounded-circle"
                        style="height: 40px; width: 40px; object-fit: cover;">
                    <span class="ms-2">PadelGo</span>
                </a>



            @endguest
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 d-flex justify-content-end gap-4">
                    
                    @auth
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('clubes.index') }}">Clubes</a>
                            </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Usuarios</a>
                            </li>
                        @elseif(Auth::user()->role === 'jugador') 
                         <li class="nav-item">
                                <a class="nav-link" href="{{ route('jugador.reservaciones.index') }}">Mis Reservaciones</a>
                            </li>
                        @elseif(Auth::user()->role === 'gestor') 
                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('canchas.index') }}">Canchas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tiporeservacion.index') }}">Tipo de Reservaciones</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('reservacions.index') }}">Reservaciones</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <button class="btn border dropdown-toggle" type="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                            </li>
                        @endif
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    
<!-- Carrusel opcional -->
@yield('carousel')

<!-- Contenido principal -->
<div class="container mt-4 main-body">
    @yield('content')
</div>

<footer class="footer-padelgo py-5 mt-5">
    <div class="container">
        <div class="row">

            <!-- Columna 1 -->
            <div class="col-12 col-lg-3 col-md-6 col-sm-6 mb-4">
                <h5 class="footer-title">PadelGO</h5>
                <p class="footer-text">
                    Reserva canchas de manera rápida y sencilla.  
                    Tu juego empieza aquí.
                </p>
            </div>

            <!-- Columna 2 -->
            <div class="col-12 col-lg-3 col-md-6 col-sm-6 mb-4">
                <h5 class="footer-title">Enlaces útiles</h5>
                <ul class="footer-list">
                    <li><a href="#" class="footer-link">Inicio</a></li>
                    <li><a href="#" class="footer-link">Clubes</a></li>
                    <li><a href="#" class="footer-link">Reservas</a></li>
                    <li><a href="#" class="footer-link">Contacto</a></li>
                </ul>
            </div>

            <!-- Columna 3 -->
            <div class="col-12 col-lg-3 col-md-6 col-sm-6 mb-4">
                <h5 class="footer-title">Contacto</h5>
                <p class="footer-text">
                    📍 Argentina <br> 
                    📞 +54 9 111 222 333 <br> 
                    ✉ contacto@padelgo.com
                </p>
            </div>

            <!-- Columna 4 -->
            <div class="col-12 col-lg-3 col-md-6 col-sm-6 mb-4">
                <h5 class="footer-title">Síguenos</h5>
                <div class="footer-socials">
                    <a href="#" class="footer-social">Facebook</a>
                    <a href="#" class="footer-social">Instagram</a>
                    <a href="#" class="footer-social">Twitter</a>
                </div>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="text-center footer-copy mt-3">
            © {{ date('Y') }} PadelGO — Todos los derechos reservados.
        </div>

    </div>
</footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
