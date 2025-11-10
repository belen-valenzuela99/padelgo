@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>

    <form action="{{ route('reservar.store') }}" method="POST">
        @csrf

            <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">


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
                        {{ $tipo->franja_horaria }} hora(s)- {{ $tipo->precio }}
                    </option>
                @endforeach
            </select>
        </div>

    
            <input type="hidden" name="status" value="programado">
        

        <button type="submit" class="btn btn-primary">Pedir Reservacion</button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const fechaInput = document.getElementById("fecha");
    const horaSelect = document.getElementById("hora");
    const canchaId = "{{ $cancha->id }}";
    const duracionSelect = document.getElementById("id_tipo_reservacion");

    // Limitar fecha entre hoy y una semana
    const hoy = new Date();
    const maxFecha = new Date();
    maxFecha.setDate(hoy.getDate() + 7);
    fechaInput.min = hoy.toISOString().split("T")[0];
    fechaInput.max = maxFecha.toISOString().split("T")[0];

    let ocupadas = [];
    let ultimaSeleccionHora = "";

    fechaInput.addEventListener("change", onFechaChange);
    horaSelect.addEventListener("change", onHoraChange);
    duracionSelect.addEventListener("change", onDuracionChange);

    if (fechaInput.value) onFechaChange();

    async function onFechaChange() {
        // Reinicia ambos selects cuando se cambia el día
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        duracionSelect.value = "";
        habilitarTodasDuraciones();

        ultimaSeleccionHora = "";

        const fecha = fechaInput.value;
        if (!fecha) return;

        try {
            const resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
            if (!resp.ok) {
                console.error("Error al obtener horas ocupadas:", resp.status);
                ocupadas = [];
            } else {
                ocupadas = await resp.json();
            }
        } catch (err) {
            console.error("Fetch error:", err);
            ocupadas = [];
        }

        construirSelectHoras();
    }

    function onHoraChange() {
        ultimaSeleccionHora = horaSelect.value;
        actualizarDuracionesDisponibles();
    }

    function onDuracionChange(e) {
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

    function construirSelectHoras() {
        const horas = generarHoras(8, 23);
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

        const durHorasSeleccion = getDuracionSeleccionadaHoras() || 1;

        horas.forEach(hora => {
            const horaInicio = hora + ":00";
            const horaFinal = sumarHoras(hora, durHorasSeleccion);

            const ocupada = ocupadas.some(res => !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final));

            const option = document.createElement("option");
            option.value = hora;
            option.textContent = hora + (ocupada ? " (ocupada)" : "");
            if (ocupada) {
                option.disabled = true;
                option.style.color = "#888";
            }
            horaSelect.appendChild(option);
        });

        if (ultimaSeleccionHora) {
            const optRest = horaSelect.querySelector(`option[value="${ultimaSeleccionHora}"]`);
            if (optRest && !optRest.disabled) horaSelect.value = ultimaSeleccionHora;
            else horaSelect.value = "";
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

    function habilitarTodasDuraciones() {
        Array.from(duracionSelect.options).forEach(opt => opt.disabled = false);
    }

    function getDuracionSeleccionadaHoras() {
        const sel = duracionSelect.selectedOptions[0];
        if (!sel || !sel.dataset) return null;
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