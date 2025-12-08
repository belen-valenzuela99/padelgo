@extends('layouts.main')

@section('content')
<div class="container">
    
    <h1>Crear Usuario</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="gestor">Gestor</option>
                <option value="jugador">Jugador</option>
            </select>
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>
</div>
@endsection
