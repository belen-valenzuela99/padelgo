@extends('layouts.main')

@section('content')

<div class="container my-5">

    <h2 class="titulo-minimal text-center mb-1">Confirmar Reservación</h2>
    <p class="subtitulo-minimal text-center mb-4">
        Revisá los datos antes de realizar la compra
    </p>

    <div class="row g-4">

        {{-- ======================================
               TARJETA IZQUIERDA - DETALLE
        ======================================= --}}
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-white" style="background-color: var(--pg-blue)">
                    <strong>Detalle de la Reserva</strong>
                </div>

                <div class="card-body">

                    <p class="mb-2"><strong>Cancha:</strong> {{ $preReserva->cancha->nombre }}</p>

                    <p class="mb-2">
                        <strong>Fecha:</strong>
                        {{ \Carbon\Carbon::parse($preReserva->fecha)->format('d/m/Y') }}
                    </p>

                    <p class="mb-2"><strong>Hora Inicio:</strong> {{ $preReserva->hora_inicio }}</p>
                    <p class="mb-2"><strong>Hora Final:</strong> {{ $preReserva->hora_final }}</p>
                    <p class="mb-2"><strong>Tipo de Reservación:</strong> {{ $preReserva->tipoReservacion->nombre }}</p>

                    <p class="mb-3">
                        <strong>Duración:</strong> {{ $preReserva->tipoReservacion->franja_horaria }} hora(s)
                    </p>

                    <hr>

                    <h4 class="text-success">
                        <strong>Total:</strong>
                        ${{ number_format($preReserva->tipoReservacion->precio, 0, ',', '.') }}
                    </h4>

                </div>
            </div>
        </div>

        {{-- ======================================
               TARJETA DERECHA - "PAGO"
        ======================================= --}}
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-white" style="background-color: var(--pg-green-dark)">
                    <strong>Información de Pago</strong>
                </div>

                <div class="card-body">

                    <form action="{{ route('jugador.reservar.confirmada') }}" method="POST" id="formPago">
                        @csrf

                        <input type="hidden" name="fecha" value="{{ $preReserva->fecha }}">
                        <input type="hidden" name="hora_inicio" value="{{ $preReserva->hora_inicio }}">
                        <input type="hidden" name="hora_final" value="{{ $preReserva->hora_final }}">
                        <input type="hidden" name="cancha_id" value="{{ $preReserva->cancha_id }}">
                        <input type="hidden" name="id_tipo_reservacion" value="{{ $preReserva->id_tipo_reservacion }}">

                        {{-- Nombre del titular --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre del Titular</label>
                            <input type="text" id="titular" class="form-control" placeholder="Ej: Belén Valenzuela" required>
                        </div>

                        {{-- Número de tarjeta --}}
                        <div class="mb-3">
                            <label class="form-label">Número de Tarjeta</label>
                            <input type="text" id="numTarjeta" class="form-control"
                                   maxlength="19" placeholder="XXXX XXXX XXXX XXXX" required>
                        </div>

                        {{-- Vencimiento / CVV --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vencimiento</label>
                                <input type="text" id="vencimiento" class="form-control"
                                       maxlength="5" placeholder="MM/AA" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="password" id="cvv" class="form-control" maxlength="3" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100 py-2">
                                Finalizar Compra
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

    // ==============================
    // Formatear tarjeta: XXXX XXXX XXXX XXXX
    // ==============================
    tarjeta.addEventListener('input', function () {
        this.value = this.value
            .replace(/\D/g, '')            // solo números
            .replace(/(.{4})/g, '$1 ')     // espacio cada 4 números
            .trim()
            .slice(0, 19);                 // límite 16 + espacios
    });

    // ==============================
    // Vencimiento MM/AA
    // ==============================
    vencimiento.addEventListener('input', function () {
        this.value = this.value
            .replace(/\D/g, '')             // solo números
            .slice(0, 4);                   // MMYY

        if (this.value.length >= 3) {
            this.value = this.value.slice(0, 2) + '/' + this.value.slice(2);
        }
    });

    // ==============================
    // CVV solo números (3)
    // ==============================
    cvv.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });

    // ==============================
    // Validación general antes de enviar
    // ==============================
    form.addEventListener('submit', function (e) {

        if (tarjeta.value.replace(/\s/g, '').length !== 16) {
            alert("El número de tarjeta debe tener 16 dígitos.");
            e.preventDefault();
            return;
        }

        if (!/^\d{2}\/\d{2}$/.test(vencimiento.value)) {
            alert("Formato de vencimiento inválido. Use MM/AA.");
            e.preventDefault();
            return;
        }

        if (cvv.value.length !== 3) {
            alert("El CVV debe tener 3 dígitos.");
            e.preventDefault();
            return;
        }

    });

});
</script>

@endsection
