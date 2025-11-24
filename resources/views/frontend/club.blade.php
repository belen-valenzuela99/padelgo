@extends('layouts.main') {{-- o el layout que uses --}}

@section('content')
<div class="container mx-auto text-center p-6">
    <div class="contenedor_imagen">
        <img src="{{ asset($club->img) }}" class="card-img-top" alt="...">
     </div>
    <h1 class="text-2xl font-bold mb-4">Reserva tu cancha</h1>
    <div>
        <p>Canchas Disponibles</p>
        <div>{{ $club->descripcion }}</div>
    </div>
        <div class="container_card">

            @forelse($canchas as $cancha)

                <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $cancha->nombre }}</h5>
                    <p class="card-text">{{ $cancha->descripcion }}</p>
                    <a href="{{route('confirmacionReserva', $cancha->id)}}" class="btn btn-primary">Ver Horas disponibles</a>
                </div>
                </div>
            @empty

                    <p>No hay canchas disponibles</p>
                
            @endforelse
        </div>
    
    
</div>

{{-- Script para cambiar el día --}}
<script>

</script>
@endsection
