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
                <th>Descripcion</th>
                <th>Club</th>
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
                <td>{{ $cancha->duracion_maxima }}</td>

                <!-- ESTADO -->
                <td>
                    @if($cancha->is_active)
                        <span class="badge bg-success">Publicado</span>
                    @else
                        <span class="badge bg-secondary">No Publicado</span>
                    @endif
                </td>

                <!-- ACCIONES -->
                <td>
                    <a href="{{ route('canchas.edit', $cancha->id) }}" class="btn btn-sm btn-warning">Editar</a>

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
                <td colspan="7" class="text-center">No hay canchas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
