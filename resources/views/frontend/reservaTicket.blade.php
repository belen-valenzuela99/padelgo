
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
          <th class="bg-light text-end w-25">Canchas</th>
          <td>{{ $reservacion->cancha_id }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Fecha de Reservacion</th>
          <td>{{ $reservacion->reservacion_date }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Inicio</th>
          <td>{{ $reservacion->hora_inicio }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Finalizacion</th>
          <td>{{ $reservacion->hora_final }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Tipo de Reserva</th>
          <td>{{ $reservacion->id_tipo_reservacion }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Estado</th>
          <td>{{ $reservacion->status }}</td>
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
