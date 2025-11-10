@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>

    <form action="{{ route('reservacions.store') }}" method="POST">
        @csrf

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
            <label for="id_tipo_reservacion" class="form-label">Duración</label>
            <select name="id_tipo_reservacion" id="id_tipo_reservacion" class="form-control" required>
                <option value="">Seleccione una duración</option>
                @foreach ($tipos as $tipo)
                  <option value="{{ $tipo->id }}" data-duracion="{{ $tipo->franja_horaria }}">
                        {{ $tipo->franja_horaria }} hora(s)
                    </option>
                @endforeach
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
    const canchaSelect = document.getElementById("cancha_id");
    const fechaInput = document.getElementById("fecha");
    const horaSelect = document.getElementById("hora");
    const duracionSelect = document.getElementById("id_tipo_reservacion");

    let ocupadas = [];
    let ultimaSeleccionHora = "";

    canchaSelect.addEventListener("change", onCanchaChange);
    fechaInput.addEventListener("change", onFechaChange);
    horaSelect.addEventListener("change", onHoraChange);
    duracionSelect.addEventListener("change", onDuracionChange);

    async function onCanchaChange() {
        resetSelects();
        const canchaId = canchaSelect.value;
        const fecha = fechaInput.value;
        if (!canchaId || !fecha) return;
        await cargarHorasOcupadas(canchaId, fecha);
        construirSelectHoras();
    }

    async function onFechaChange() {
        resetSelects();
        const canchaId = canchaSelect.value;
        const fecha = fechaInput.value;
        if (!canchaId || !fecha) return;
        await cargarHorasOcupadas(canchaId, fecha);
        construirSelectHoras();
    }

    function resetSelects() {
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        duracionSelect.value = "";
        Array.from(duracionSelect.options).forEach(opt => opt.disabled = false);
        ultimaSeleccionHora = "";
    }

    async function cargarHorasOcupadas(canchaId, fecha) {
        try {
            const resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
            if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
            ocupadas = await resp.json();
        } catch (err) {
            console.error("Error al cargar horas ocupadas:", err);
            ocupadas = [];
        }
    }

    function construirSelectHoras() {
        const horas = generarHoras(8, 23);
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        const dur = getDuracionSeleccionadaHoras() || 1;

        horas.forEach(hora => {
            const horaInicio = hora + ":00";
            const horaFinal = sumarHoras(hora, dur);
            const ocupada = ocupadas.some(res => !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final));

            const opt = document.createElement("option");
            opt.value = hora;
            opt.textContent = hora + (ocupada ? " (ocupada)" : "");
            if (ocupada) {
                opt.disabled = true;
                opt.style.color = "#888";
            }
            horaSelect.appendChild(opt);
        });
    }

    function onHoraChange() {
        ultimaSeleccionHora = horaSelect.value;
        actualizarDuracionesDisponibles();
    }

    function onDuracionChange() {
        const opt = duracionSelect.selectedOptions[0];
        if (!opt) return;

        if (opt.disabled) {
            duracionSelect.value = "";
            return;
        }

        const durHoras = parseInt(opt.dataset.duracion || "0", 10);
        const horaSel = horaSelect.value;
        if (horaSel) {
            const horaInicio = horaSel + ":00";
            const horaFinal = sumarHoras(horaSel, durHoras);
            const solapa = ocupadas.some(res => !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final));
            if (solapa) {
                horaSelect.value = "";
                ultimaSeleccionHora = "";
            }
        }
    }

    function actualizarDuracionesDisponibles() {
        const horaSel = horaSelect.value;
        const opciones = Array.from(duracionSelect.options).slice(1);

        opciones.forEach(opt => {
            const dur = parseInt(opt.dataset.duracion || "0", 10);
            if (!horaSel) {
                opt.disabled = false;
            } else {
                const horaInicio = horaSel + ":00";
                const horaFinal = sumarHoras(horaSel, dur);
                const solapa = ocupadas.some(res => !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final));
                opt.disabled = solapa;
            }

            if (opt.disabled && duracionSelect.value === opt.value) {
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