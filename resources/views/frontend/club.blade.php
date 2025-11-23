@extends('layouts.main')

@section('content')
<div class="container py-5">

    {{-- Imagen del club --}}
    <div class="text-center mb-4">
        <div class="contenedor_imagen">
            <img src="{{ asset($club->img) }}" class="img-fluid shadow-sm" alt="Imagen del club">
        </div>
    </div>

    <h1 class="text-center mb-3 titulo-minimal">
        Reserva tu cancha
    </h1>

    <p class="text-center mb-4 subtitulo-minimal">
        Canchas Disponibles
    </p>


    {{-- Cards de canchas --}}
    <div class="container_card">

        @forelse($canchas as $cancha)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $cancha->nombre }}</h5>
                    <p class="card-text">{{ $cancha->descripcion }}</p>

                    <a href="{{ route('confirmacionReserva', $cancha->id) }}" 
                       class="btn btn-primary w-100">
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
