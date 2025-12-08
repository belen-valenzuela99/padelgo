@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <h1>Bienvenido al Dashboard Admin</h1>
    <div class="container_card">
        
            <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/admin.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Clubes</h5>
    <p class="card-text">Gestione los club y asignale un gestor</p>
    <a href="{{ route('clubes.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>

<div class="card" style="width: 18rem;">
  <img src="{{ asset("img/user.svg") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Usuarios</h5>
    <p class="card-text">Gestione los usuarios y asignele un rol</p>
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>
    </div>
@endsection
