@extends('layouts.main')


@section('content')
<div class="container py-5">

  {{-- HERO DEL CLUB --}}
<section class="club-hero d-flex align-items-center justify-content-center text-center">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <h1 class="club-hero-title">{{ $club->nombre }}</h1>
        <p class="club-hero-subtitle">📍 {{ $club->direccion }}</p>
    </div>

    <img src="{{ asset($club->img) }}" alt="Club" class="club-hero-bg">
</section>
<br>

    {{-- BADGES DEL CLUB --}}
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">

        <span class="badge-club">🏟 {{ count($canchas) }} Canchas disponibles</span>

        <span class="badge-club">⏰ Abierto hoy: 08:00 - 23:00</span>
        

        <span class="badge-club">⭐ Reservas rápidas</span>

    </div>

    {{-- TÍTULO PRINCIPAL --}}
    <h2 class="text-center titulo-modern mb-2">
        Reserva tu cancha
    </h2>

    <p class="text-center subtitulo-modern mb-4">
        Elegí la cancha ideal para tu juego
    </p>

    {{-- SEPARADOR LÍNEA --}}
    <div class="separador mb-5"></div>

    {{-- Cards de canchas --}}
    <div class="row g-4 justify-content-center">

        @forelse($canchas as $cancha)
            <div class="col-md-4 col-sm-6">
                <div class="cancha-card">

                    {{-- Encabezado de la cancha --}}
                    <h5 class="cancha-title">{{ $cancha->nombre }}</h5>

                    <span class="badge-cancha">🎾 Cancha disponible</span>

                    <p class="cancha-desc mt-3">
                        {{ $cancha->descripcion }}
                    </p>

                    {{-- Botón --}}
                    <a href="{{ route('confirmacionReserva', $cancha->id) }}"
                       class="btn boton-reserva w-100 mt-3">
                        Ver horas disponibles
                    </a>
                </div>
            </div>

        @empty
            <div class="text-center py-5">
                <p class="text-muted fs-5">No hay canchas disponibles en este momento.</p>
            </div>
        @endforelse

    </div>

    <br>
    <br>

    <div class="row">
        <div class="col-7 ">
            <iframe src="{{ $club->mapa }}" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-5  contenedor-blog-info">
            <div class="blog-info">

                 <h3 class="mb-3">Ubicacion</h3>
                <p>  📍 {{ $club->direccion }}</p>
                 
            </div>
                
            <div class="blog-info">
    <h3 class="mb-3">Redes Sociales</h3>

    <div class="d-flex flex-wrap gap-3">
        @forelse($club->redesSociales as $red)
            @if($red->pivot->url_red)
                <a 
                    href="{{ $red->pivot->url_red }}" 
                    target="_blank"
                    class="d-flex align-items-center gap-2 text-decoration-none border rounded px-3 py-2 shadow-sm bg-light"
                >
                    {{-- Imagen --}}
                    <img 
                        src="{{ asset($red->img) }}" 
                        alt="{{ $red->nombre }}"
                        class="img-fluid"
                        style="width: 28px; height: 28px; object-fit: contain;"
                    >

                    {{-- Nombre --}}
                    <span class="fw-semibold text-dark">
                        {{ $red->nombre }}
                    </span>
                </a>
            @endif
        @empty
            <p class="text-muted">Este club no tiene redes sociales cargadas.</p>
        @endforelse
    </div>
</div>


            @php
                if (!function_exists('servicioIcono')) {
                    function servicioIcono($servicio) {
                        return match ($servicio) {
                            'Wi-Fi' => 'bi-wifi',
                            'Vestuario' => 'bi-person-badge',
                            'Ayuda Médica' => 'bi-heart-pulse',
                            'Torneos' => 'bi-trophy',
                            'Cumpleaños' => 'bi-balloon',
                            'Parrilla' => 'bi-fire',
                            'Escuelita deportiva' => 'bi-dribbble',
                            'Bar / Restaurante' => 'bi-cup-hot',
                            'Quincho' => 'bi-house',
                            default => 'bi-check-circle',
                        };
                    }
                }
            @endphp

                <div class="blog-info mt-4">
                <h3 class="mb-3">Servicios </h3>

                <div class="d-flex flex-wrap gap-3">
                    @forelse($club->servicios as $servicio)

                        <div class="d-flex align-items-center gap-2 border rounded px-3 py-2 shadow-sm bg-light">
                            {{-- Ícono --}}
                        <i class="bi {{ servicioIcono($servicio->nombre_servicio) }} fs-5" style="color:#2f3b65;"></i>

                            {{-- Nombre --}}
                            <span class="fw-semibold">
                                {{ $servicio->nombre_servicio }}
                            </span>
                        </div>

                    @empty
                        <p class="text-muted">Este club no tiene servicios cargados.</p>
                    @endforelse
                </div>
            </div>

            </div>
    </div>

    

</div>
@endsection

