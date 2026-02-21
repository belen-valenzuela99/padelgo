@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>

    <form action="{{ route('admin.reservacions.preparar') }}" method="POST">
        @csrf


        <div class="mb-3">
            <label for="usuario" class="form-label">Jugador</label>
            <select name="usuario" id="usuario_id" class="form-control" required>
                <option value="">Seleccione un Jugador</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="cancha_id" class="form-label">Cancha</label>
            <select name="cancha_id" id="cancha_id" class="form-control" required>
                <option value="">Seleccione una cancha</option>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Hora de inicio</label>
           <select name="hora" id="hora" class="form-control" required>
                <option value="">Seleccione una hora</option>
            </select>
        </div>

       <div class="mb-3">
            <label class="form-label">Cantidad de horas</label>
            <select name="duracion" id="duracion" class="form-control" required>
                <option value="">Seleccione duración</option>
            </select>
        </div>


        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="programado" selected>Programado</option>
                <option value="cancelado">Cancelado</option>
                <option value="turno perdido">Turno perdido</option>
                <option value="turno completado">Turno completado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Reservación</button>
    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {

    const canchaSelect   = document.getElementById("cancha_id");
    const fechaInput     = document.getElementById("fecha");
    const horaSelect     = document.getElementById("hora");
    const duracionSelect = document.getElementById("duracion");

    let ocupadas = [];

    canchaSelect.addEventListener("change", onCanchaOrFechaChange);
    fechaInput.addEventListener("change", onCanchaOrFechaChange);
    horaSelect.addEventListener("change", actualizarDuracionesDisponibles);
    //duracionSelect.addEventListener("change", construirSelectHoras);

    async function onCanchaOrFechaChange() {
        resetSelects();

        const canchaId = canchaSelect.value;
        const fecha = fechaInput.value;

        if (!canchaId || !fecha) return;

        await cargarHorasOcupadas(canchaId, fecha);
        await cargarDuracionMaxima(canchaId);
        construirSelectHoras();
    }

    function resetSelects() {
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        duracionSelect.innerHTML = '<option value="">Seleccione duración</option>';
    }

    async function cargarHorasOcupadas(canchaId, fecha) {
        try {
            const resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
            ocupadas = await resp.json();
        } catch (err) {
            console.error("Error horas ocupadas:", err);
            ocupadas = [];
        }
    }

    async function cargarDuracionMaxima(canchaId) {
        try {
            const resp = await fetch(`/api/cancha/${canchaId}`);
            const data = await resp.json();

            duracionSelect.innerHTML = '<option value="">Seleccione duración</option>';

            for (let i = 1; i <= data.duracion_maxima; i++) {
                const opt = document.createElement("option");
                opt.value = i;
                opt.dataset.duracion = i;
                opt.textContent = i + " hora(s)";
                duracionSelect.appendChild(opt);
            }

        } catch (err) {
            console.error("Error duración máxima:", err);
        }
    }

    function construirSelectHoras() {
        const dur = getDuracionSeleccionadaHoras() || 1;

        const horas = generarHoras(8, 23);
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

        horas.forEach(hora => {
            const horaInicio = hora + ":00";
            const horaFinal = sumarHoras(hora, dur);

            const ocupada = ocupadas.some(res =>
                !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final)
            );

            const opt = document.createElement("option");
            opt.value = hora;
            opt.textContent = hora + (ocupada ? " (ocupada)" : "");

            if (ocupada) {
                opt.disabled = true;
            }

            horaSelect.appendChild(opt);
        });
    }

    function actualizarDuracionesDisponibles() {
        const horaSel = horaSelect.value;
        if (!horaSel) return;

        const opciones = Array.from(duracionSelect.options).slice(1);

        opciones.forEach(opt => {
            const dur = parseInt(opt.dataset.duracion || "0", 10);
            const horaInicio = horaSel + ":00";
            const horaFinal = sumarHoras(horaSel, dur);

            const solapa = ocupadas.some(res =>
                !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final)
            );

            opt.disabled = solapa;

            if (solapa && duracionSelect.value === opt.value) {
                duracionSelect.value = "";
            }
        });
    }

    function getDuracionSeleccionadaHoras() {
        const sel = duracionSelect.selectedOptions[0];
        if (!sel) return null;
        return parseInt(sel.dataset.duracion || "0", 10);
    }

    function generarHoras(inicio, fin) {
        const arr = [];
        for (let h = inicio; h <= fin; h++) {
            arr.push(h.toString().padStart(2, "0") + ":00");
        }
        return arr;
    }

    function sumarHoras(hora, cantidad) {
        const [h, m] = hora.split(':').map(Number);
        const nueva = new Date(0, 0, 0, h + cantidad, m);
        return nueva.toTimeString().slice(0, 8);
    }

});
</script>

@endsection