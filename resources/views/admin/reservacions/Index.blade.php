@extends('layouts.main')


@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Reservaciones</h2>
         <a href="{{ route('reservacions.create') }}" class="btn btn-primary">Crear Reservación</a>
        
    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Jugador</th>
                        <th>Club</th>
                        <th>Cancha</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservacions as $reservacion)
                        <tr>
                            <td class="fw-semibold text-muted">
                                #{{ $reservacion->id }}
                            </td>

                            <td>
                                {{ $reservacion->user?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $reservacion->cancha?->club?->nombre }}
                            </td>

                            <td>
                                {{ $reservacion->cancha?->nombre }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}
                            </td>

                            <td class="fw-semibold text-success">
                                ${{($reservacion->precio) }}
                            </td>

                            <td>
                                @if($reservacion->status === 'programado')
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                        Programado
                                    </span>
                                @elseif($reservacion->status === 'turno perdido')
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        Turno perdido
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                        {{ ucfirst($reservacion->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <a href="{{ route('reservacions.edit', $reservacion->id) }}"
                                   class="btn btn-sm btn-outline-warning me-2">
                                    Editar
                                </a>

                                <form action="{{ route('reservacions.destroy', $reservacion->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta reservación?');">
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
                            <td colspan="9" class="text-center py-4 text-muted">
                                No hay reservaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

</div>
@endsection
