@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Editar Tipo de Reservación</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tiporeservacion.update', $tiporeservacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Inicio</label>
            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio"
                value="{{ old('hora_inicio', $tiporeservacion->hora_inicio) }}" required>
        </div>

        <div class="mb-3">
            <label for="hora_fin" class="form-label">Hora Final</label>
            <input type="time" class="form-control" id="hora_fin" name="hora_fin"
                value="{{ old('hora_fin', $tiporeservacion->hora_fin) }}" required>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio"
                value="{{ old('precio', $tiporeservacion->precio) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('tiporeservacion.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection