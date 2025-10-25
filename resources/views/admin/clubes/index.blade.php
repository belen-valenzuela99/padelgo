@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Clubes</h2>
        <a href="{{ route('clubes.create') }}" class="btn btn-primary">Crear Club</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Acciones</th>
            </tr>
        </thead>
<tbody>
            @forelse($clubes as $club)
            <tr>
                <td>{{ $club->id }}</td>
                <td>{{ $club->nombre }}</td>
                <td>{{ $club->direccion }}</td>
                <td>
                    <a href="{{ route('clubes.edit', $club->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('clubes.destroy', $club->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este club?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay clubes registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
