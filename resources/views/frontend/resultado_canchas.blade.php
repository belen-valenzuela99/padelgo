@extends('layouts.main')

@section('content')
<div class="container py-5">
 <a href="{{route('home')}}" class="btn btn-primary">Volver al inicio</a>
    <h2 class="text-center mb-4">
        Canchas disponibles
    </h2>

    <p class="text-center text-muted">
        📅 {{ $fecha }} | ⏰ {{ $hora }}
    </p>

    @if($canchasDisponibles->isEmpty())
        <div class="alert alert-warning text-center">
            No hay canchas disponibles para ese día y horario 😕
        </div>
    @else
        <div class="row">
            @foreach ($canchasDisponibles as $cancha)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                            <div class="contenedor_imagen-card">
                        <img src="{{ asset($cancha->club->img) }}" class="card-img-top" alt="">
                    </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $cancha->nombre }}</h5>

                            <p class="card-text text-muted">
                                Club: {{ $cancha->club->nombre }}
                            </p>

                            <p class="card-text">
                                {{ $cancha->descripcion }}
                            </p>

                            <a href="{{ route('confirmacionReserva', [
                                    'id' => $cancha->id,
                                    'fecha' => $fecha
                                ]) }}"
                            class="btn btn-primary w-100">
                                Reservar
                            </a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
