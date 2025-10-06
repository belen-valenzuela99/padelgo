@extends('layouts.main') {{-- o el layout que uses --}}

@section('content')
<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Reservar Cancha de Pádel</h1>

    {{-- Fecha seleccionada --}}
    <div class="contenedor-fecha-actual">
        <button id="prevDay" class="btn dia"><</button>
        
        <h2 id="fechaActual" class="fecha-actual">
            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
        </h2>
        
        <button id="nextDay" class="btn dia">></button>
    </div>

    {{-- Horas disponibles --}}
    <div class="horarios">
        @foreach ($horas as $hora)
            <div class="border hora">
                <p class="font-semibold">{{ $hora }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- Script para cambiar el día --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const fechaEl = document.getElementById('fechaActual');
    const prevBtn = document.getElementById('prevDay');
    const nextBtn = document.getElementById('nextDay');

    let fechaActual = new Date('{{ $fecha }}');

    function cambiarDia(dias) {
        fechaActual.setDate(fechaActual.getDate() + dias);
        const nuevaFecha = fechaActual.toISOString().split('T')[0];
        window.location.href = `/reservas?fecha=${nuevaFecha}`;
    }

    prevBtn.addEventListener('click', () => cambiarDia(-1));
    nextBtn.addEventListener('click', () => cambiarDia(1));
});
</script>
@endsection
