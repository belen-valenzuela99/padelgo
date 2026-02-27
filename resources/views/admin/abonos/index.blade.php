@extends('layouts.main')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Abonos</h2>
        <a href="{{ route('abonos.create') }}" class="btn btn-primary">Crear Abono</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    @endphp

    <div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
                                <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label">Mes</label>
                        <select id="mesFiltro" class="form-select">
                            <option value="">Todos</option>
                            @foreach($meses as $num => $nombre)
                                <option value="{{ $num }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Día</label>
                        <select id="diaFiltro" class="form-select">
                            <option value="">Todos</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select id="activeFiltro" class="form-select">
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                            
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
            <table class="table table-bordered  table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Jugador</th>
                        <th>Cancha</th>
                        <th>Día</th>
                        <th>Mes</th>
                        <th>Horario</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($abonos as $abono)
                        
                            <tr 
                data-mes="{{ $abono->mes }}"
                data-dia="{{ $abono->dia_semana }}"
                data-activo="{{ $abono->activo }}"
            >

                            <td class="fw-semibold text-muted">
                                #{{ $abono->id }}
                            </td>

                            <td>
                                {{ $abono->usuario?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $abono->cancha?->nombre }}
                            </td>

                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                    {{ $abono->dia_semana }}
                                </span>
                            </td>

                            <td>
                                {{ $meses[$abono->mes] ?? 'Mes inválido' }}
                            </td>

                            <td>
                                {{ $abono->hora_inicio }} - {{ $abono->hora_fin }}
                            </td>

                            <td class="fw-semibold text-success">
                                ${{ $abono->precio }}
                            </td>

                            <td class="">
                                @if($abono->activo)
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                
                                {{-- Botón Publicar / Despublicar --}}
                                @if($abono->activo)
                                    <form action="{{ route('abonos.desactivar', $abono->id) }}" 
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-secondary">
                                            Desactivar
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('abonos.activar', $abono->id) }}" 
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Activar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('abonos.destroy', $abono->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este abono y sus reservaciones?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        Eliminar
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No hay abonos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

</div>
<script>
const mesFiltro  = document.getElementById('mesFiltro');
const diaFiltro  = document.getElementById('diaFiltro');
const filas = document.querySelectorAll('tbody tr[data-mes]');
const activeFiltro = document.getElementById("activeFiltro");

function normalizar(texto) {
    return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "") // quita tildes
        .trim();
}

function filtrarTabla() {
    const mes  = mesFiltro.value;
    const active  = activeFiltro.value;
    const dia  = normalizar(diaFiltro.value);

    filas.forEach(fila => {
        const mesFila = fila.dataset.mes;
        const activeFila = fila.dataset.activo;
        const diaFila = normalizar(fila.dataset.dia);

        let visible = true;

        if (mes && mesFila !== mes) visible = false;
        if (active && activeFila !== active) visible = false;
        if (dia && diaFila !== dia) visible = false;

        fila.style.display = visible ? '' : 'none';
    });
}

mesFiltro.addEventListener('change', filtrarTabla);
activeFiltro.addEventListener('change', filtrarTabla);
diaFiltro.addEventListener('change', filtrarTabla);

document.getElementById('limpiarFiltros').addEventListener('click', () => {
    mesFiltro.value = '';
    diaFiltro.value = '';
    activeFiltro.value = '';
    filtrarTabla();
});
</script>

@endsection
