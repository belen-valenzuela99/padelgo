@extends('layouts.main')

@section('title', 'Dashboard Gestor')

@section('content')
    <h1>Bienvenido al Dashboard Gestor</h1>
    
     <div class="container_card">
        
            <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/admin.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Canchas</h5>
    <p class="card-text">Gestione los tipos y horarios de sus canchas</p>
    <a href="{{ route('canchas.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>

      <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/reservaciones.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Reservaciones</h5>
    <p class="card-text">Gestione las reservaciones</p>
    <a href="{{ route('reservacions.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>

      <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/tipodereservacion.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Tipo de Reservaciones</h5>
    <p class="card-text">Gestione las franjas horarias o los abonos de sus reservaciones</p>
    <a href="{{ route('tiporeservacion.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>
    </div>
@endsection
