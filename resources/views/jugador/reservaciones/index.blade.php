@extends('layouts.main')

@section('content')
<div class="container py-4">

    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Mis Reservaciones</h2>
            <small class="text-muted">Historial completo de tus turnos</small>
        </div>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-calendar-check me-2"></i>Listado de reservaciones
        </div>

        <div class="table-responsive">
            <div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" id="fechaDesde" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select id="estadoFiltro" class="form-select">
                    <option value="">Todos</option>
                    @foreach(\App\Models\Reservacion::STATUS as $estado)
                        <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button type="button" id="limpiarFiltros"
                        class="btn btn-outline-secondary w-100">
                    Limpiar
                </button>
            </div>

        </div>
    </div>
</div>

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>Usuario</th>
                        <th>Club</th>
                        <th>Cancha</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservaciones as $reservacion)
                        <tr data-fecha="{{ $reservacion->reservacion_date }}"
                             data-status="{{ $reservacion->status }}"
                                                            >
                            <td>{{ $reservacion->user?->name }}</td>


                            <td class="fw-semibold">
                                {{ $reservacion->cancha?->club?->nombre }}
                            </td>

                            <td>{{ $reservacion->cancha?->nombre }}</td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d/m/Y') }}
                            </td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}
                            </td>

                            <td class="text-end fw-bold text-success">
                                ${{ number_format($reservacion->precio, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                @php
                                    $statusClass = match($reservacion->status) {
                                        'programado' => 'primary',
                                        'turno completado' => 'success',
                                        'turno perdido' => 'warning',
                                        'cancelado' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($reservacion->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                                No hay reservaciones registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
const fechaDesde   = document.getElementById('fechaDesde');
const fechaHasta   = document.getElementById('fechaHasta');
const estadoFiltro = document.getElementById('estadoFiltro');
const filas        = document.querySelectorAll('tbody tr');

function filtrarTabla() {
    const desde  = fechaDesde.value;
    const hasta  = fechaHasta.value;
    const estado = estadoFiltro.value;

    filas.forEach(fila => {
        const fechaFila  = fila.dataset.fecha;
        const estadoFila = fila.dataset.status;

        let visible = true;

        if (desde && fechaFila < desde) visible = false;
        if (hasta && fechaFila > hasta) visible = false;
        if (estado && estadoFila !== estado) visible = false;

        fila.style.display = visible ? '' : 'none';
    });
}

fechaDesde.addEventListener('change', filtrarTabla);
fechaHasta.addEventListener('change', filtrarTabla);
estadoFiltro.addEventListener('change', filtrarTabla);

document.getElementById('limpiarFiltros').addEventListener('click', () => {
    fechaDesde.value = '';
    fechaHasta.value = '';
    estadoFiltro.value = '';
    filtrarTabla();
});
</script>

@endsection
<i class="fas fa-zhihuz"></i>