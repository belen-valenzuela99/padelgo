@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <h3>Conflictos detectados</h3>

    @if(count($disponibles) === 0)
    <h5 class="text-danger">Todas las fechas están ocupadas</h5>
    @endif


    <div class="alert alert-warning">
        Algunas fechas ya están reservadas.
    </div>

    <h5> Fechas NO disponibles</h5>
    <ul>
        @foreach($no_disponibles as $fecha)
            <li>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</li>
        @endforeach
    </ul>

    <h5 class="mt-3"> Fechas disponibles</h5>
    <ul>
        @foreach($disponibles as $fecha)
            <li>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</li>
        @endforeach
    </ul>

    <form action="{{ route('abonos.confirmar') }}" method="POST">
    @csrf

    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    @if(count($disponibles) > 0)
        <button class="btn btn-success">
            Confirmar y tomar fechas disponibles
        </button>
    @else
        <div class="alert alert-danger mt-3">
             No hay fechas disponibles para este abono.
            Por favor, elegí otra fecha, horario o cancha.
        </div>
    @endif

    <a href="{{ route('abonos.create') }}" class="btn btn-secondary mt-2">
        Elegir otro abono
    </a>
</form>

</div>
@endsection
