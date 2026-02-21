{{-- INGRESOS --}}
@if(array_key_exists('ingresos', $data))
<div class="card mb-4">
    <div class="card-header fw-semibold">
        Ingresos por cancha
    </div>

    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Club</th>
                <th>Cancha</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data['ingresos']['filas'] as $i)
            <tr>
                <td>{{ $i->club }}</td>
                <td>{{ $i->cancha }}</td>
                <td class="text-end">
                    ${{ number_format($i->total, 0, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Sin datos</td>
            </tr>
        @endforelse

        @if($data['ingresos']['filas']->count())
        <tr class="table-success fw-bold">
            <td colspan="2">TOTAL GENERAL</td>
            <td class="text-end">
                ${{ number_format($data['ingresos']['total_general'], 0, ',', '.') }}
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
@endif

{{-- RESERVAS --}}
@if(array_key_exists('reservas', $data))
<div class="card mb-4">
    <div class="card-header fw-semibold">
        Reservas por cancha
    </div>

    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Club</th>
                <th>Cancha</th>
                <th class="text-center">Cantidad</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data['reservas']['filas'] as $r)
            <tr>
                <td>{{ $r->club }}</td>
                <td>{{ $r->cancha }}</td>
                <td class="text-center">{{ $r->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Sin datos</td>
            </tr>
        @endforelse

        @if($data['reservas']['filas']->count())
        <tr class="table-success fw-bold">
            <td colspan="2">TOTAL GENERAL</td>
            <td class="text-center">
                {{ $data['reservas']['total_general'] }}
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
@endif

   {{-- ABONOS --}}
@if(array_key_exists('abonos', $data))
<div class="card mb-4">
    <div class="card-header fw-semibold">
        Abonos por cancha
    </div>

    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Club</th>
                <th>Cancha</th>
                <th class="text-center">Abonos activos</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data['abonos']['filas'] as $a)
            <tr>
                <td>{{ $a->club }}</td>
                <td>{{ $a->cancha }}</td>
                <td class="text-center">{{ $a->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Sin datos</td>
            </tr>
        @endforelse

        @if($data['abonos']['filas']->count())
        <tr class="table-success fw-bold">
            <td colspan="2">TOTAL GENERAL</td>
            <td class="text-center">
                {{ $data['abonos']['total_general'] }}
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
@endif
</div>
