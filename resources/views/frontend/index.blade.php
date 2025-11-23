@extends('layouts.main') {{-- o el layout que uses --}}

@section('content')
<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Reserva tu cancha</h1>
    <div>
        <p>Clubes Disponibles</p>
    </div>
        <div class="container_card">

            @forelse($clubes as $club)

                <div class="card" style="width: 18rem;">
                    <div class="contenedor_imagen-card">
                        <img src="{{ asset($club->img) }}" class="card-img-top" alt="...">
                    </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $club->nombre }}</h5>
                    <p class="card-text">{{ $club->direccion }}</p>
                    <p class="card-text">{{ $club->descripcion }}</p>
                    <a href="{{ route('clubDetalle', $club->id) }}" class="btn btn-primary">Ver Canchas</a>
                </div>
                </div>
            @empty

                    <p>No hay clubes disponibles</p>
                
            @endforelse
        </div>
    
    
</div>

{{-- Script para cambiar el día --}}
<script>

</script>
@endsection
