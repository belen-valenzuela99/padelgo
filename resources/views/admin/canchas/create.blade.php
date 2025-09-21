@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Crear Cancha</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('canchas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicacion</label>
            <textarea class="form-control" id="ubicacion" name="ubicacion" rows="3">{{ old('ubicacion') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('canchas.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
