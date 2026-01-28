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

    @if (auth()->user()->role === 'admin')
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
    @else

    @endif
    
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

 <h4 class="mt-4 mb-3">Redes Sociales del Club</h4>
<form action="{{ route('club_red_social.sync') }}" method="POST">

    @csrf

    <input type="hidden" name="id_club" value="{{ $club->id }}">

    @foreach($redesSociales as $red)
        @php
            $valor = old('redes.' . $red->id, $redesClub[$red->id] ?? '');
            $checked = !empty($valor);
        @endphp

        <div class="row align-items-center mb-3 p-2 border rounded">
            {{-- Checkbox --}}
            <div class="col-md-1 text-center">
                <input
                    type="checkbox"
                    class="form-check-input toggle-red"
                    data-target="red-{{ $red->id }}"
                    {{ $checked ? 'checked' : '' }}
                >
            </div>

            {{-- Imagen --}}
            <div class="col-md-1 text-center">
                <img src="{{ asset($red->img) }}"
                     alt="{{ $red->nombre }}"
                     class="img-fluid"
                     style="max-height: 35px;">
            </div>

            {{-- Nombre --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-0">
                    {{ $red->nombre }}
                </label>
            </div>

            {{-- Input --}}
            <div class="col-md-7">
                <input
                    type="url"
                    class="form-control"
                    id="red-{{ $red->id }}"
                    name="redes[{{ $red->id }}]"
                    placeholder="URL de {{ $red->nombre }}"
                    value="{{ $valor }}"
                    {{ $checked ? '' : 'disabled' }}
                >
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-success mt-3">
        Guardar redes sociales
    </button>
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


    document.querySelectorAll('.toggle-red').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const input = document.getElementById(this.dataset.target);
            input.disabled = !this.checked;
        });
    });


</script>

@endsection
