@extends('layouts.main')

@section('content')

<div class="container my-5">

    <h2 class="text-center mb-4">Confirmar Abono</h2>

    <div class="row g-4">

        {{-- DETALLE --}}
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-primary text-white">
                    Detalle del Abono
                </div>

                <div class="card-body">

                    <p><strong>Jugador:</strong> {{ $preAbono->usuario->name }}</p>
                    <p><strong>Club:</strong> {{ $preAbono->club->nombre }}</p>
                    <p><strong>Cancha:</strong> {{ $preAbono->cancha->nombre }}</p>

                    <p><strong>Mes:</strong> {{ $preAbono->mes }}</p>
                    <p><strong>Día:</strong> {{ $preAbono->dia_semana }}</p>
                    <p><strong>Horario:</strong> 
                        {{ $preAbono->hora_inicio }} - {{ $preAbono->hora_fin }}
                    </p>

                    <p><strong>Fechas del abono:</strong></p>
                    <ul>
                        @foreach($preAbono->fechas as $fecha)
                            <li>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</li>
                        @endforeach
                    </ul>

                    <hr>

                    <h4 class="text-success">
                        Total: ${{ number_format($preAbono->precio,0,',','.') }}
                    </h4>

                </div>
            </div>
        </div>

        {{-- PAGO --}}
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-success text-white">
                    Información de Pago
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.abonos.storeFinal') }}" method="POST" id="formPago">
                        @csrf

                        {{-- reenviamos todos los datos --}}
                        <input type="hidden" name="user_id" value="{{ $preAbono->usuario->id }}">
                        <input type="hidden" name="cancha_id" value="{{ $preAbono->cancha->id }}">
                        <input type="hidden" name="mes" value="{{ $preAbono->mes }}">
                        <input type="hidden" name="dia_semana" value="{{ $preAbono->dia_semana }}">
                        <input type="hidden" name="hora_inicio" value="{{ $preAbono->hora_inicio }}">
                        <input type="hidden" name="hora_fin" value="{{ $preAbono->hora_fin }}">
                        <input type="hidden" name="precio" value="{{ $preAbono->precio }}">

                        <div class="mb-3">
                            <label class="form-label">Nombre del Titular</label>
                            <input type="text" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Número de Tarjeta</label>
                            <input type="text" id="numTarjeta" class="form-control" maxlength="19" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vencimiento</label>
                                <input type="text" id="vencimiento" class="form-control" maxlength="5" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="password" id="cvv" class="form-control" maxlength="3" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">
                                Confirmar y Crear Abono
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- ============================
     VALIDACIÓN JAVASCRIPT
============================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const tarjeta = document.getElementById('numTarjeta');
    const vencimiento = document.getElementById('vencimiento');
    const cvv = document.getElementById('cvv');
    const form = document.getElementById('formPago');

    // Formato tarjeta
    tarjeta.addEventListener('input', function () {
        this.value = this.value
            .replace(/\D/g, '')
            .replace(/(.{4})/g, '$1 ')
            .trim()
            .slice(0, 19);
    });

    // Formato MM/AA
    vencimiento.addEventListener('input', function () {
        this.value = this.value
            .replace(/\D/g, '')
            .slice(0, 4);

        if (this.value.length >= 3) {
            this.value = this.value.slice(0, 2) + '/' + this.value.slice(2);
        }
    });

    // CVV
    cvv.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });

    // Validación final
    form.addEventListener('submit', function (e) {

        // Tarjeta
        if (tarjeta.value.replace(/\s/g, '').length !== 16) {
            alert("El número de tarjeta debe tener 16 dígitos.");
            e.preventDefault();
            return;
        }

        // Vencimiento formato correcto
        if (!/^\d{2}\/\d{2}$/.test(vencimiento.value)) {
            alert("Formato de vencimiento inválido. Use MM/AA.");
            e.preventDefault();
            return;
        }

        const partes = vencimiento.value.split('/');
        const mes = parseInt(partes[0]);
        const anio = parseInt(partes[1]);

        // Mes válido 01–12
        if (mes < 1 || mes > 12) {
            alert("El mes debe estar entre 01 y 12.");
            e.preventDefault();
            return;
        }

        // Fecha actual
        const fechaActual = new Date();
        const mesActual = fechaActual.getMonth() + 1; // 1–12
        const anioActual = fechaActual.getFullYear() % 100; // últimos 2 dígitos

        // Año inválido
        if (anio < anioActual) {
            alert("La tarjeta está vencida (año inválido).");
            e.preventDefault();
            return;
        }

        // Mismo año pero mes vencido
        if (anio === anioActual && mes < mesActual) {
            alert("La tarjeta está vencida (mes inválido).");
            e.preventDefault();
            return;
        }

        // CVV
        if (cvv.value.length !== 3) {
            alert("El CVV debe tener 3 dígitos.");
            e.preventDefault();
            return;
        }

    });

});
</script>


@endsection