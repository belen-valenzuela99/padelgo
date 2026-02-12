@extends('layouts.main')

@section('title', 'Dashboard Gestor')


@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <h1>Bienvenido al Dashboard Gestor</h1>

    <div class="container-fluid">
        <!-- BOTÓN -->
<!-- BOTÓN PARA ABRIR MODAL -->
<button class="btn btn-outline-primary mb-3"
        data-bs-toggle="modal"
        data-bs-target="#reporteModal">
     Reportes avanzados
</button>
<br>
<br>
<br>
    {{-- KPIs --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Reservas</h6>
                    <h2 class="fw-bold">{{ $totalReservas }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Ingresos Totales</h6>
                    <h2 class="fw-bold text-success">
                        $ {{ number_format($ingresosTotales, 0) }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Canchas activas</h6>
                    <h2 class="fw-bold">{{ $canchasActivas }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Clubes</h6>
                    <h2 class="fw-bold">{{ $totalClubes }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
    <div class="card-header bg-warning text-dark">
        <strong>Reservas pendientes</strong>
    </div>

    <div class="card-body p-0">
        @if($reservasPendientes->isEmpty())
            <p class="text-center text-muted py-4 mb-0">
                No hay reservas pendientes 🎉
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Cancha</th>
                            <th>Club</th>
                            <th>Jugador</th>
                            <th>Estado</th>
                            <th>Precio</th>
                            <th>Creado</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasPendientes as $reserva)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($reserva->reservacion_date)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ substr($reserva->hora_inicio, 0, 5) }} - 
                                    {{ substr($reserva->hora_final, 0, 5) }}
                                </td>

                                <td>{{ $reserva->cancha->nombre }}</td>

                                <td>{{ $reserva->cancha->club->nombre }}</td>

                                <td>{{ $reserva->user->name ?? '—' }}</td>

                                <td>
                                    @if($reserva->status === 'programado')
                                        <span class="badge bg-primary">Programado</span>
                                    @elseif($reserva->status === 'turno perdido')
                                        <span class="badge bg-danger">Turno perdido</span>
                                    @endif
                                </td>

                                <td>
                                    ${{ number_format($reserva->precio, 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ $reserva->created_at->format('d/m/Y H:i') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

    {{-- Últimas reservas --}}
     {{--<div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Últimas reservas</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Club</th>
                        <th>Cancha</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasReservas as $reserva)
                        <tr>
                            <td>{{ $reserva->user->name ?? '—' }}</td>
                            <td>{{ $reserva->cancha->club->nombre }}</td>
                            <td>{{ $reserva->cancha->nombre }}</td>
                            <td>{{ $reserva->reservacion_date }}</td>
                            <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_final }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($reserva->status) }}
                                </span>
                            </td>
                            <td>$ {{ number_format($reserva->precio, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay reservas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>--}}

<div class="card mt-4">
    <div class="card-header fw-semibold">
        Ingresos por mes
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mes</th>
                    <th>Total ingresado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingresosPorMes as $mes => $total)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }}
                        </td>
                        <td>
                            ${{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            No hay ingresos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<br>
<br>
<br>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Reservas por mes</h5>
            <canvas id="reservasMesChart"></canvas>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Canchas más reservadas</h5>
            <canvas id="canchasChart"></canvas>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Ingresos por mes</h5>
            <canvas id="ingresosMesChart"></canvas>
        </div>
    </div>
</div>





<br>
<br>
<br>

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

   <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/frontend/abono.svg") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Abonos</h5>
    <p class="card-text">Gestione los abonos mensuales para sus usuarios</p>
    <a href="{{ route('abonos.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>
    </div>
     </div>

     @include('gestor._modal')
    <script>
    // === RESERVAS POR MES ===
    const reservasMesRaw = @json($reservasPorMes);

    // Convertimos objeto -> arrays
    const reservasMesLabels = Object.keys(reservasMesRaw).map(mes => 'Mes ' + mes);
    const reservasMesData   = Object.values(reservasMesRaw);

    new Chart(document.getElementById('reservasMesChart'), {
        type: 'bar',
        data: {
            labels: reservasMesLabels,
            datasets: [{
                label: 'Reservas',
                data: reservasMesData,
                borderWidth: 1
            }]
        }
    });


    // === CANCHAS MÁS RESERVADAS ===

    const canchasRaw = @json($canchasMasReservadas);

    const canchasLabels = canchasRaw.map(c => c.nombre);
    const canchasData   = canchasRaw.map(c => c.total);

    new Chart(document.getElementById('canchasChart'), {
        type: 'pie',
        data: {
            labels: canchasLabels,
            datasets: [{
                label: 'Reservas',
                data: canchasData
            }]
        }
    });


  
const ingresosRaw = @json($ingresosPorMes);

// Nombres de meses en español
const meses = [
    '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// Labels y datos
const ingresosLabels = Object.keys(ingresosRaw)
    .map(mes => `${meses[mes]}`);

const ingresosData = Object.values(ingresosRaw);

new Chart(document.getElementById('ingresosMesChart'), {
    type: 'bar',
    data: {
        labels: ingresosLabels,
        datasets: [{
            label: 'Ingresos ($)',
            data: ingresosData,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

/**
 * ===============================
 * DATA INICIAL DESDE BACKEND
 * ===============================
 * $canchas debe venir con:
 * id, nombre, id_club
 */
const todasLasCanchas = @json($canchas);

/**
 * ===============================
 * FILTRAR CANCHAS POR CLUB
 * ===============================
 */
const clubSelect   = document.getElementById('clubSelect');
const canchaSelect = document.getElementById('canchaSelect');

function actualizarCanchas() {
    const clubId = clubSelect.value;

    canchaSelect.innerHTML = '<option value="all">Todas</option>';

    let canchasFiltradas = todasLasCanchas;

    if (clubId !== 'all') {
        canchasFiltradas = todasLasCanchas.filter(
            c => String(c.id_club) === String(clubId)
        );
    }

    canchasFiltradas.forEach(cancha => {
        const option = document.createElement('option');
        option.value = cancha.id;
        option.textContent = cancha.nombre;
        canchaSelect.appendChild(option);
    });
}

clubSelect.addEventListener('change', actualizarCanchas);

/**
 * ===============================
 * ENVÍO AJAX DEL FORMULARIO
 * ===============================
 */
document.getElementById('formReporte').addEventListener('submit', function (e) {
    e.preventDefault();

    const resultado = document.getElementById('resultadoReporte');
    resultado.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border"></div>
            <p class="mt-2">Generando reporte...</p>
        </div>
    `;

    fetch("{{ route('reportes.generar') }}", {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
    })
    .then(res => res.text())
    .then(html => {
        resultado.innerHTML = html;
    })
    .catch(() => {
        resultado.innerHTML = `
            <div class="alert alert-danger">
                Error al generar el reporte
            </div>
        `;
    });
});
</script>
@endsection
