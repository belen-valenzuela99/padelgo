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

</div>
@endsection

