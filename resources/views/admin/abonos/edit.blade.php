@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Editar Abono</h2>

    <form action="{{ route('abonos.update', $abono->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Jugador</label>
                <select name="user_id" class="form-control">
                    @foreach($usuarios as $user)
                        <option value="{{ $user->id }}" 
                            {{ $abono->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Cancha</label>
                <select name="cancha_id" class="form-control">
                    @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}"
                            {{ $abono->cancha_id == $cancha->id ? 'selected' : '' }}>
                            {{ $cancha->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Día de la semana</label>
                <select name="dia_semana" class="form-control">
                    @foreach(['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                        <option value="{{ $dia }}" 
                            {{ $abono->dia_semana === $dia ? 'selected' : '' }}>
                            {{ ucfirst($dia) }}
                        </option>   
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
    <label class="form-label">Mes</label>

    @php
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    @endphp

    <select name="mes" class="form-control" required>
        @foreach($meses as $numero => $nombre)
            <option value="{{ $numero }}" {{ $abono->mes == $numero ? 'selected' : '' }}>
                {{ $nombre }}
            </option>
        @endforeach
    </select>
</div>


            <div class="col-md-4">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" value="{{ $abono->precio }}" class="form-control">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Hora inicio</label>
                <input type="time" name="hora_inicio" class="form-control" value="{{ $abono->hora_inicio }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Hora fin</label>
                <input type="time" name="hora_fin" class="form-control" value="{{ $abono->hora_fin }}">
            </div>
        </div>

        <button class="btn btn-success">Actualizar Abono</button>
        <a href="{{ route('abonos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
