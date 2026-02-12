{{-- INGRESOS --}}
@if(array_key_exists('ingresos', $data))
<div class="card mb-3">
    <div class="card-header">Ingresos por cancha</div>
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Cancha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data['ingresos'] as $i)
        <tr>
            <td>{{ $i->cancha }}</td>
            <td>${{ number_format($i->total, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center">Sin datos</td>
        </tr>
        @endforelse

        </tbody>
    </table>
</div>

@endif

<div class="row">
    {{-- RESERVAS --}}
    @if(array_key_exists('reservas', $data))
    <div class="card mb-3">
    <div class="card-header">Reservas por cancha</div>
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Cancha</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
       @forelse($data['reservas'] as $r)
        <tr>
            <td>{{ $r->cancha }}</td>
            <td class="text-center">{{ $r->total }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center">Sin datos</td>
        </tr>
        @endforelse

        </tbody>
    </table>
</div>

    @endif

    {{-- ABONOS --}}
    @if(array_key_exists('abonos', $data))
    <div class="card">
    <div class="card-header">Abonos por cancha</div>
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Cancha</th>
                <th>Abonos activos</th>
            </tr>
        </thead>
        <tbody>
       @forelse($data['abonos'] as $a)
        <tr>
            <td>{{ $a->cancha }}</td>
            <td class="text-center">{{ $a->total }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center">Sin datos</td>
        </tr>
        @endforelse

        </tbody>
    </table>
</div>

    @endif
</div>
