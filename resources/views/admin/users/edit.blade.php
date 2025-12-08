@extends('layouts.main')

@section('content')
<div class="container">
    
    <h1>Editar Usuario</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-select" required>
                @foreach(['admin', 'gestor', 'jugador'] as $role)
                    <option value="{{ $role }}" @if($user->role == $role) selected @endif>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
