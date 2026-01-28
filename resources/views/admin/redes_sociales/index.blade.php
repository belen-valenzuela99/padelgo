@extends('layouts.main')


@section('content')
<div class="container">
<h2>Redes Sociales</h2>


<a href="{{ route('redes_sociales.create') }}" class="btn btn-primary mb-3">Nueva Red</a>


@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif


<table class="table table-bordered">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Imagen</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
@foreach($redes as $red)
<tr>
<td>{{ $red->id }}</td>
<td>{{ $red->nombre }}</td>
<td>
@if($red->img)
<img src="{{ asset($red->img) }}" width="40">
@else
Sin imagen
@endif
</td>
<td>
<a href="{{ route('redes_sociales.edit', $red->id) }}" class="btn btn-warning btn-sm">Editar</a>


<form action="{{ route('redes_sociales.destroy', $red->id) }}" method="POST" class="d-inline">
@csrf
@method('DELETE')
<button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta red?')">Eliminar</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection