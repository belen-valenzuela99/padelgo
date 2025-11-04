@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Editar Reservación</h2>

    <form action="{{ route('reservacions.update', $reservacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="cancha_id" class="form-label">Cancha</label>
            <select name="cancha_id" id="cancha_id" class="form-control" required>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}" {{ $reservacion->cancha_id == $cancha->id ? 'selected' : '' }}>
                        {{ $cancha->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ $reservacion->reservacion_date }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Hora de inicio</label>
            <input type="time" name="hora" id="hora" value="{{ $reservacion->hora_inicio }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_tipo_reservacion" class="form-label">Duración</label>
            <select name="id_tipo_reservacion" id="id_tipo_reservacion" class="form-control" required>
                @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ $reservacion->id_tipo_reservacion == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->franja_horaria }} hora(s)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control">
                @foreach (\App\Models\Reservacion::STATUS as $estado)
                    <option value="{{ $estado }}" {{ $reservacion->status == $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Reservación</button>
    </form>
</div>
@endsection