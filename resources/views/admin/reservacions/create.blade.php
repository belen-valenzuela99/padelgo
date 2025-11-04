@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>

    <form action="{{ route('reservacions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="cancha_id" class="form-label">Cancha</label>
            <select name="cancha_id" id="cancha_id" class="form-control" required>
                <option value="">Seleccione una cancha</option>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Hora de inicio</label>
            <input type="time" name="hora" id="hora" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_tipo_reservacion" class="form-label">Duración</label>
            <select name="id_tipo_reservacion" id="id_tipo_reservacion" class="form-control" required>
                <option value="">Seleccione una duración</option>
                @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->franja_horaria }} hora(s)</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="programado" selected>Programado</option>
                <option value="cancelado">Cancelado</option>
                <option value="turno perdido">Turno perdido</option>
                <option value="turno completado">Turno completado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Reservación</button>
    </form>
</div>
@endsection