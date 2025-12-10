@extends('layouts.main')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Abonos</h2>
        <a href="{{ route('abonos.create') }}" class="btn btn-primary">Crear Abono</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Jugador</th>
                <th>Cancha</th>
                <th>Día</th>
                <th>Mes</th>
                <th>Horario</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($abonos as $abono)
                <tr>
                    <td>{{ $abono->id }}</td>
                    <td>{{ $abono->usuario?->name }}</td>
                    <td>{{ $abono->cancha?->nombre }}</td>
                    <td>{{ $abono->dia_semana }}</td>
                    <td> {{ $meses[$abono->mes] ?? 'Mes inválido' }}</td>
                    <td>{{ $abono->hora_inicio }} - {{ $abono->hora_fin }}</td>
                    <td>${{ $abono->precio }}</td>
                    <td>
                        <a href="{{ route('abonos.edit', $abono->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('abonos.destroy', $abono->id) }}" method="POST" class="d-inline-block"
                              onsubmit="return confirm('¿Eliminar este abono y sus reservaciones?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No hay abonos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
