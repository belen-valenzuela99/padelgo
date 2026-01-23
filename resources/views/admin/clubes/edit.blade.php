@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Editar club</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clubes.update', $club->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 


        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $club->nombre) }}" required>
        </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">
            {{ old('descripcion', $club->descripcion) }}
        </textarea>
    </div>

    <div class="mb-3">
        <label for="id_user" class="form-label">Gestor del Club</label>
        <select class="form-select" id="id_user" name="id_user">
            <option value="">Sin asignar</option>

            @foreach($gestores as $gestor)
                <option value="{{ $gestor->id }}"
                    {{ old('id_user', $club->id_user) == $gestor->id ? 'selected' : '' }}>
                    {{ $gestor->name }}
                </option>
            @endforeach
        </select>
    </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Direccion</label>
            <textarea class="form-control" id="direccion" name="direccion" rows="3">{{ old('direccion', $club->direccion) }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="img" class="form-label">Imagen del Club</label>
            @if($club->img)
                <div class="mb-2">
                <img src="{{ asset($club->img) }}" alt="Imagen del club" class="img-thumbnail" style="max-width: 200px;">
                </div>
            @endif
            <input type="file" class="form-control" id="img" name="img" accept="image/*">
            <small class="text-muted">Si subes una nueva imagen, reemplazará la actual.</small>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Buscar ubicación en Google Maps</label>
            <div class="input-group">
                <input 
                    type="text" 
                    id="busqueda_mapa" 
                    class="form-control" 
                    placeholder="Ej: Full Padel Outdoor, Mendoza">
                <button type="button" class="btn btn-outline-primary" onclick="buscarMapa()">Buscar</button>
            </div>
        </div>

        {{-- Campo oculto que se guarda en la BD --}}
        <input type="hidden" id="mapa" name="mapa" value="{{ old('mapa', $club->mapa) }}">

        <div class="mb-3">
            <label class="form-label">Vista previa del mapa</label>
            <div class="border rounded p-2">
                <iframe 
                    id="iframe_mapa"
                    width="100%" 
                    height="300" 
                    style="border:0;" 
                    loading="lazy"
                    allowfullscreen
                    src="{{ old('mapa', $club->mapa) }}">
                </iframe>
            </div>
        </div>



        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('clubes.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
<script>
function buscarMapa() {
    let lugar = document.getElementById('busqueda_mapa').value;

    if (lugar.trim() === '') {
        alert('Escribí una ubicación primero');
        return;
    }

    let url = "https://www.google.com/maps?q=" + encodeURIComponent(lugar) + "&output=embed";

    // Actualiza vista previa
    document.getElementById('iframe_mapa').src = url;

    // Guarda el link para enviar al backend
    document.getElementById('mapa').value = url;
}
</script>

@endsection
