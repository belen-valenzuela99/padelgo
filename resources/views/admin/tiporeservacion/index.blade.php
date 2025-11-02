@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tipo Reservaciones</h2>
        <a href="{{ route('tiporeservacion.create') }}" class="btn btn-primary">Crear Tipo de Reservacion</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Franja horaria</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tiporeservacion as $tipo)
            <tr>
                <td>{{ $tipo->id }}</td>
                <td>{{ $tipo->franja_horaria }}</td>
                <td>{{ $tipo->precio }}</td>
                <td>
                    <a href="{{ route('tiporeservacion.edit', $tipo->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('tiporeservacion.destroy', $tipo->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este tipo de reservacion?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay Tipo de reservaciones registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
