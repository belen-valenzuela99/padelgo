@extends('layouts.main')


@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Reservaciones</h2>
    
    </div>


    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Usuario</th>
                <th>Club</th>
                <th>Cancha</th>
                <th>Fecha de reservación</th>
                <th>Hora de inicio</th>
                <th>Hora de final</th>
                <th>Precio</th> 
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            @forelse($reservaciones as $reservacion)
            <tr>
                <td>{{ $reservacion->user?->name }}</td>
                <td>{{ $reservacion->cancha?->club?->nombre }}</td>
                <td>{{ $reservacion->cancha?->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}</td>
                <td>{{ $reservacion->precio }}</td>
                <td>{{ $reservacion->status }}</td>
                
            </tr>
            @empty
          <tr>
                <td colspan="9" class="text-center">No hay reservación registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection