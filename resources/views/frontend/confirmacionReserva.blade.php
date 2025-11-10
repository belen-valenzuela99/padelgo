@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>

    <form action="{{ route('reservar.store') }}" method="POST">
        @csrf

            <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">


        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Hora de inicio</label>
            <input type="time" name="hora" id="hora" step="3600" value="00:00" class="form-control" required>
            
        </div>

        <div class="mb-3">
            <label for="id_tipo_reservacion" class="form-label">Duración</label>
            <select name="id_tipo_reservacion" id="id_tipo_reservacion" class="form-control" required>
                <option value="">Seleccione una duración</option>
                @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->franja_horaria }} hora(s)- {{ $tipo->precio }}</option>
                @endforeach
            </select>
        </div>

    
            <input type="hidden" name="status" value="programado">
        

        <button type="submit" class="btn btn-primary">Pedir Reservacion</button>
    </form>
</div>
@endsection