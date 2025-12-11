@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Editar Estado de Reservación</h2>

    <form action="{{ route('reservacions.update', $reservacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">ID</label>
            <input type="text" class="form-control" value="{{ $reservacion->id }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Cancha</label>
            <input type="text" class="form-control" 
                value="{{ $reservacion->cancha->nombre }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="text" class="form-control" 
                value="{{ $reservacion->reservacion_date }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Hora inicio</label>
            <input type="text" class="form-control" 
                value="{{ $reservacion->hora_inicio }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Hora fin</label>
            <input type="text" class="form-control" 
                value="{{ $reservacion->hora_final }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Duración</label>
            
        </div>

        {{-- ÚNICO CAMPO EDITABLE --}}
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

        <button type="submit" class="btn btn-success">Actualizar Estado</button>
    </form>
</div>
@endsection
