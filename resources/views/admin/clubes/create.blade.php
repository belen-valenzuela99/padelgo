@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Crear Club</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
</ul>
        </div>
    @endif

    <form action="{{ route('clubes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Direccion</label>
            <textarea class="form-control" id="direccion" name="direccion" rows="3">{{ old('direccion') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Imagen del Club</label>
            <input type="file" class="form-control" id="img" name="img" accept="image/*">
        </div>


        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('clubes.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
