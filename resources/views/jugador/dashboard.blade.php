@extends('layouts.main')

@section('title', 'Dashboard Jugador')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h1>Bienvenido al Dashboard Jugador</h1>
    <div class="container py-4">

    <h2 class="mb-4">Reportes</h2>

    {{-- KPIs --}}
    <div class="row mb-4">
        <x-kpi title="Total Reservas" :value="$totalReservas" />
        <x-kpi title="Reservas Activas" :value="$reservasActivas" />
        <x-kpi title="Turnos Completados" :value="$turnosCompletados" />
        <x-kpi title="Dinero Gastado" value="${{ number_format($dineroGastado,0,',','.') }}" />
    </div>

    {{-- Gráficos --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Gastos por mes</h5>
                <canvas id="gastosMesChart"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5>Reservas por club</h5>
                <canvas id="reservasClubChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Próximas reservas --}}
    <div class="card mb-4">
        <div class="card-header">Próximas reservas</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Club</th>
                        <th>Cancha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proximasReservas as $r)
                        <tr>
                            <td>{{ $r->reservacion_date }}</td>
                            <td>{{ $r->hora_inicio }} - {{ $r->hora_final }}</td>
                            <td>{{ $r->cancha->club->nombre }}</td>
                            <td>{{ $r->cancha->nombre }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay reservas próximas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
    <div class="container_card">
        
            <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/reservajugador.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Ver todos los clubes disponibles</h5>
    <p class="card-text">Elegí el club y reservá tu cancha</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Ver los clubes</a>
  </div>
</div>
              <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/reservaciones.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Mis reservas</h5>
     <p class="card-text">Administra todas tus reservas<br><br><br></p>
    <a href="{{ route('jugador.reservaciones.index') }}" class="btn btn-primary">consultar</a>
  </div>
</div>
    </div>

    <script>
    // === GASTOS POR MES ===
    const gastosRaw = @json($gastosPorMes);

    const gastosLabels = gastosRaw.map(r => {
        const fecha = new Date(r.anio, r.mes - 1);
        return fecha.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
    });

    const gastosData = gastosRaw.map(r => r.total);

    new Chart(document.getElementById('gastosMesChart'), {
        type: 'line',
        data: {
            labels: gastosLabels,
            datasets: [{
                label: 'Gasto ($)',
                data: gastosData,
                tension: 0.3
            }]
        }
    });

    // === RESERVAS POR CLUB ===
    const reservasClubRaw = @json($reservasPorClub);

    new Chart(document.getElementById('reservasClubChart'), {
        type: 'bar',
        data: {
            labels: reservasClubRaw.map(r => r.club),
            datasets: [{
                label: 'Reservas',
                data: reservasClubRaw.map(r => r.total)
            }]
        }
    });
</script>
@endsection
