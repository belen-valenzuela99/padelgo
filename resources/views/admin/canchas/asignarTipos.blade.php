@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Asignar Tipos de Reservación a <strong>{{ $cancha->nombre }}</strong></h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('canchas.tipos.update', $cancha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Horario</th>
                    <th>Precio Global</th>
                    <th>Precio Personalizado (opcional)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipos as $tipo)
                    @php
                        $pivot = $tiposAsignados[$tipo->id] ?? null;
                    @endphp
                    <tr>
                        <td>
                            <input 
                                type="checkbox" 
                                name="tipos[{{ $tipo->id }}][activo]" 
                                @checked($pivot && $pivot['activo'] == 1)>
                        </td>
                        <td>{{ $tipo->hora_inicio }} - {{ $tipo->hora_fin }}</td>
                        <td>S/. {{ number_format($tipo->precio, 2) }}</td>
                        <td>
                            <input type="number" step="0.01" name="tipos[{{ $tipo->id }}][precio]" class="form-control"
                                value="{{ $pivot['precio'] ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('canchas.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
