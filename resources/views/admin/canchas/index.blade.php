@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Canchas</h2>
        <a href="{{ route('canchas.create') }}" class="btn btn-primary">Crear Cancha</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Club</th>
                <th>Horarios Activos</th> {{-- 🕒 NUEVA COLUMNA --}}
                <th>Hora Máxima de Reserva</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($canchas as $cancha)
        <tr>
            <td>{{ $cancha->id }}</td>
            <td>{{ $cancha->nombre }}</td>
            <td>{{ $cancha->descripcion }}</td>
            <td>{{ $cancha->club ? $cancha->club->nombre : "Sin club" }}</td>

            {{-- HORARIOS ASIGNADOS --}}
            <td>
                @php
                    $horariosActivos = $cancha->tiposReservacion->where('pivot.activo', 1);
                @endphp

                @if($horariosActivos->isNotEmpty())
                    <ul class="list-unstyled mb-0">
                        @foreach($horariosActivos as $tipo)
                            @php
                                $horaInicio = \Carbon\Carbon::createFromFormat('H:i:s', $tipo->hora_inicio)->format('H:i');
                                $horaFin = \Carbon\Carbon::createFromFormat('H:i:s', $tipo->hora_fin)->format('H:i');
                                // Si hay un precio personalizado en el pivot, usarlo, sino el precio global del tipo
                                $precio = $tipo->pivot->precio ?? $tipo->precio;
                            @endphp
                            <li>
                                {{ $horaInicio }} - {{ $horaFin }}
                                <span class="text-muted">($ {{ number_format($precio, 2) }})</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Sin horarios asignados</span>
                @endif
            </td>

            <td>{{ $cancha->duracion_maxima }}</td>
            <td>
                @if($cancha->is_active)
                    <span class="badge bg-success">Publicado</span>
                @else
                    <span class="badge bg-secondary">No Publicado</span>
                @endif
            </td>

            <td>
                <a href="{{ route('canchas.edit', $cancha->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <a href="{{ route('canchas.tipos.edit', $cancha->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-clock"></i> Asignar Horarios
                </a>
                {{-- Botón Publicar / Despublicar --}}
                    @if($cancha->is_active)
                        <form action="{{ route('canchas.desactivar', $cancha->id) }}" 
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-secondary">
                                Despublicar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('canchas.activar', $cancha->id) }}" 
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">
                                Publicar
                            </button>
                        </form>
                    @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">No hay canchas registradas.</td>
        </tr>
        @endforelse


        </tbody>
    </table>

</div>
@endsection
