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
                <th>Ubicacion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($canchas as $cancha)
            <tr>
                <td>{{ $cancha->id }}</td>
                <td>{{ $cancha->nombre }}</td>
                <td>{{ $cancha->ubicacion }}</td>
                <td>
                    <a href="{{ route('canchas.edit', $cancha->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('canchas.destroy', $cancha->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay canchas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
