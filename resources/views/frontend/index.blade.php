@extends('layouts.main')

@section('carousel')
<section>
    <div id="carouselHome" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">

        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="{{ asset('img/frontend/carrusel1.jpg') }}" class="d-block w-100" alt="">
                <div class="carousel-caption-left">
                    <div>
                        <h2>Reserva rápido con <span class="text-verde">PadelGO</span></h2>
                        <p class="sub">Encuentra tu cancha ideal al instante</p>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/frontend/carrusel2.jpg') }}" class="d-block w-100" alt="">
                <div class="carousel-caption-left">
                    <div>
                        <h2>Elegí tu club favorito</h2>
                        <p class="sub">Disponibilidad en vivo y reservas inmediatas</p>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/frontend/carrusel3.jpg') }}" class="d-block w-100" alt="">
                <div class="carousel-caption-left">
                    <div>
                        <h2>Simple. Rápido. Fácil.</h2>
                        <p class="sub">Juga sin complicaciones</p>
                    </div>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</section>
<div class="separador"></div>
<section class="my-5 text-center">
    <h2 class="fw-bold">Encontrá tu cancha perfecta</h2>
    <p class="text-muted">Reservá en segundos, con disponibilidad en tiempo real.</p>
</section>


@endsection

@section('content')

<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Clubes Disponibles</h1>

    <div class="mb-4 d-flex justify-content-center">
    <input type="text" id="buscadorClub" class="form-control w-50 shadow-sm"
           placeholder="Buscar club por nombre o dirección...">
    </div>

    @php
    $hoy = \Carbon\Carbon::now()->format('Y-m-d');
    $maxFecha = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
@endphp 

<div class="mb-4  buscador_club gap-2">
    <form  class="d-flex justify-content-center gap-3" action="{{ route('buscar.canchas') }}" method="POST">
    @csrf
     <input
        type="date"
        name="fecha_buscador"
        id="fecha_buscador"
        class="form-control shadow-sm"
        value="{{ $hoy }}"
        min="{{ $hoy }}"
        max="{{ $maxFecha }}"
    >

    <select name="horario_buscador" id="horario_buscador" class="form-select shadow-sm">
    @for ($h = 8; $h <= 23; $h++)
        <option value="{{ sprintf('%02d:00', $h) }}">
            {{ sprintf('%02d:00', $h) }}
        </option>
    @endfor

    @for ($h = 0; $h <= 2; $h++)
        <option value="{{ sprintf('%02d:00', $h) }}">
            {{ sprintf('%02d:00', $h) }}
        </option>
    @endfor
</select>


    <button type="submit" class="btn btn-primary">
        Buscar
    </button>
</form>
</div>



    <div class="container_card">
        @forelse($clubes as $club)
            <div class="card" style="width: 18rem;">
                <div class="contenedor_imagen-card">
                    <img src="{{ asset($club->img) }}" class="card-img-top" alt="">
                </div>
                <div class="card-body card-club">
                    <h5 class="card-title">{{ $club->nombre }}</h5>
                    <p class="card-text">{{ $club->direccion }}</p>
                    <p class="card-text">{{ $club->descripcion }}</p>

                    <a href="{{ route('clubDetalle', $club->id) }}" class="btn btn-primary">
                        Ver Club
                    </a>
                </div>
            </div>
        @empty
            <p>No hay clubes disponibles</p>
        @endforelse
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const buscador = document.getElementById("buscadorClub");
    const cards = document.querySelectorAll(".card");

    buscador.addEventListener("input", e => {
        const valor = e.target.value.toLowerCase();
        cards.forEach(card => {
            const texto = card.innerText.toLowerCase();
            card.style.display = texto.includes(valor) ? "" : "none";
        });
    });
});
</script>

@endsection

