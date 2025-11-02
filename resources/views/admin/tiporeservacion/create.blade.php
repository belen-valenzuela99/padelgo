@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Crear Tipo Reservacion</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tiporeservacion.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="franja_horaria" class="form-label">Franja Horaria</label>
            <input type="text" class="form-control" id="franja_horaria" name="franja_horaria" value="{{ old('franja_horaria') }}" required>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" rows="3" value="{{ old('precio') }}">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('tiporeservacion.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
