@extends('layouts.main')


@section('content')
<div class="container">
<h2>Editar Red Social</h2>


<form action="{{ route('admin.redes_sociales.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $red->nombre) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">URL de la red</label>
        <input type="text" name="url_red" class="form-control" value="{{ old('url_red', $red->url_red) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="img" class="form-control">
    </div>
    @if($red->img)
    <img src="{{ asset($red->img) }}" width="80" class="mb-2">
    @endif

    <button type="submit" class="btn btn-success">Guardar</button>
</form>

</div>
@endsection