@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Crear Abono</h2>

    <form action="{{ route('abonos.store') }}" method="POST">
        @csrf

        <div class="row mb-3">

            {{-- Jugador --}}
            <div class="col-md-6">
                <label class="form-label">Jugador</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Seleccionar jugador</option>
                    @foreach($usuarios as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cancha --}}
            <div class="col-md-6">
                <label class="form-label">Cancha</label>
                <select name="cancha_id" class="form-control" required>
                    <option value="">Seleccionar cancha</option>
                    @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}">
                            {{ $cancha->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row mb-3">

            {{-- Día de la semana --}}
            <div class="col-md-6">
                <label class="form-label">Día de la semana</label>
                <select name="dia_semana" class="form-control" required>
                    <option value="">Seleccionar día</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miércoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sábado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
            </div>

            {{-- Mes --}}
<div class="col-md-6">
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
        <option value="">Seleccionar mes</option>
        @foreach($meses as $numero => $nombre)
            <option value="{{ $numero }}">{{ $nombre }}</option>
        @endforeach
    </select>
</div>


        </div>

        <div class="row mb-3">

            {{-- Hora inicio --}}
            <div class="col-md-6">
                <label class="form-label">Hora de inicio</label>
                <input type="time" name="hora_inicio" class="form-control" required>
            </div>

            {{-- Hora fin --}}
            <div class="col-md-6">
                <label class="form-label">Hora de fin</label>
                <input type="time" name="hora_fin" class="form-control" required>
            </div>

        </div>

        {{-- Precio --}}
        <div class="mb-3">
            <label class="form-label">Precio del abono</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Abono</button>
        <a href="{{ route('abonos.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
