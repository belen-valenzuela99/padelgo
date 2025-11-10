
@extends('layouts.main') {{-- o el layout que uses --}}

@section('content')
<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Se confirmo tu reserva</h1>
    <div>
    </div>
        <div class="">
                   <div class="card shadow-sm w-75 mx-auto mt-4">
  <div class="card-header bg-primary text-white fw-bold">
    Detalles de la reservación
  </div>
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <tbody>
    <tr>
          <th class="bg-light text-end w-25">Usuario</th>
          <td>{{ $reservacion->user->name ?? 'Desconocido' }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Cancha</th>
          <td>{{ $reservacion->cancha->nombre ?? 'Sin cancha' }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Fecha de Reservación</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Inicio</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Finalización</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Tipo de Reserva</th>
          <td>{{ $reservacion->tipoReservacion->franja_horaria ?? 'N/A' }} hora(s)</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Estado</th>
          <td>{{ ucfirst($reservacion->status) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
    
        </div>
    
    
</div>

{{-- Script para cambiar el día --}}
<script>

</script>
@endsection
