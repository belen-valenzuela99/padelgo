@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Editar Cancha</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('canchas.update', $cancha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $cancha->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="id_club" class="form-label">Club asociado</label>
            <select class="form-select" id="id_club" name="id_club" required>
                <option value="">Seleccionar club...</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->id }}" 
                        {{ old('id_club', $cancha->id_club) == $club->id ? 'selected' : '' }}>
                        {{ $club->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('canchas.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
