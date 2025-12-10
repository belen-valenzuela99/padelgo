@extends('layouts.main')

@section('title', 'Dashboard Jugador')

@section('content')
    <h1>Bienvenido al Dashboard Jugador</h1>
    <div class="container_card">
        
            <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/reservajugador.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Ver todos los clubes disponibles</h5>
    <p class="card-text">Elegi el club y la cancha de tu preferencia</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Ver los clubes</a>
  </div>
</div>
              <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/reservajugador.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Ver todas mis reservas</h5>
     <p class="card-text"><br><br><br></p>
    <a href="{{ route('jugador.reservaciones.index') }}" class="btn btn-primary">consultar</a>
  </div>
</div>
    </div>
@endsection
