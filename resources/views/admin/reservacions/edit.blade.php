@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Editar Reservación</h2>

    <form action="{{ route('reservacions.update', $reservacion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="id" class="form-label">Id</label>
            <input type="number" name="id" id="id" value="{{ $reservacion->id }}" class="form-control" readonly>

        </div>

        <div class="mb-3">
            <label for="cancha_id" class="form-label">Cancha</label>
            <select name="cancha_id" id="cancha_id" class="form-control" required>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}" {{ $reservacion->cancha_id == $cancha->id ? 'selected' : '' }}>
                        {{ $cancha->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ $reservacion->reservacion_date }}" class="form-control" required>
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
                @foreach ($tipos as $tipo)
                   <option value="{{ $tipo->id }}" 
                        data-duracion="{{ $tipo->franja_horaria }}"
                        {{ $reservacion->id_tipo_reservacion == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->franja_horaria }} hora(s)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control">
                @foreach (\App\Models\Reservacion::STATUS as $estado)
                    <option value="{{ $estado }}" {{ $reservacion->status == $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Reservación</button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const canchaSelect = document.getElementById("cancha_id");
    const fechaInput = document.getElementById("fecha");
    const horaSelect = document.getElementById("hora");
    const duracionSelect = document.getElementById("id_tipo_reservacion");

    let ocupadas = [];
    // convertir duracion a número
    const reservaActual = {
        cancha: "{{ $reservacion->cancha_id }}",
        fecha: "{{ $reservacion->reservacion_date }}",
        hora: "{{ substr($reservacion->hora_inicio, 0, 5) }}",
        duracion: parseInt("{{ \App\Models\TipoReservacion::find($reservacion->id_tipo_reservacion)->franja_horaria }}", 10) || 1
    };

    canchaSelect.addEventListener("change", cargarHoras);
    fechaInput.addEventListener("change", cargarHoras);
    horaSelect.addEventListener("change", () => {
        actualizarDuracionesDisponibles();
    });
    duracionSelect.addEventListener("change", () => {
        // si la duracion elegida ya no cabe en la hora seleccionada, limpiar hora
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
            }
        }
    });

    // Al cargar la página, precargar horas (si cancha y fecha están presentes)
    cargarHoras();

    async function cargarHoras() {
        const canchaId = canchaSelect.value;
        const fecha = fechaInput.value;
        if (!canchaId || !fecha) {
            horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
            return;
        }

        try {
            const resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
            if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
            ocupadas = await resp.json();
        } catch (err) {
            console.error("Error al cargar horas ocupadas:", err);
            ocupadas = [];
        }

        // Excluir la propia reserva actual de la lista de ocupadas (para permitir mantenerla)
        const reservaHoraInicio = reservaActual.hora ? reservaActual.hora + ":00" : null;
        const reservaHoraFinal = reservaActual.hora ? sumarHoras(reservaActual.hora, reservaActual.duracion) : null;

        if (reservaHoraInicio && reservaHoraFinal && fecha === reservaActual.fecha && Number(canchaId) === Number(reservaActual.cancha)) {
            ocupadas = ocupadas.filter(r => !(r.hora_inicio === reservaHoraInicio && r.hora_final === reservaHoraFinal));
        }

        construirSelectHoras();
        actualizarDuracionesDisponibles();
    }

    function construirSelectHoras() {
        const horas = generarHoras(8, 23);
        const dur = getDuracionSeleccionadaHoras() || reservaActual.duracion || 1;

        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

        horas.forEach(hora => {
            const horaInicio = hora + ":00";
            const horaFinal = sumarHoras(hora, dur);

            // Ignoramos el rango de la reservación actual
            const ocupada = ocupadas.some(res => {
                // si es la misma reserva (por id o rango exacto), la ignoramos
                if (
                    res.hora_inicio === "{{ $reservacion->hora_inicio }}" &&
                    res.hora_final === "{{ $reservacion->hora_final }}" &&
                    res.cancha_id == "{{ $reservacion->cancha_id }}"
                ) {
                    return false;
                }

                // comprobamos si se superpone
                const solapa = !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final);
                return solapa;
            });

            const opt = document.createElement("option");
            opt.value = hora;
            opt.textContent = hora + (ocupada ? " (ocupada)" : "");
            if (ocupada && hora !== reservaActual.hora) {
                opt.disabled = true;
                opt.style.color = "#888";
            }
            horaSelect.appendChild(opt);
        });

        // Seleccionar hora actual si sigue siendo válida
        horaSelect.value = reservaActual.hora;
    }

    function actualizarDuracionesDisponibles() {
        const horaSel = horaSelect.value;
        const opciones = Array.from(duracionSelect.options).slice(1); // saltamos placeholder

        opciones.forEach(opt => {
            const dur = parseInt(opt.dataset.duracion || "0", 10);
            if (!horaSel) {
                opt.disabled = false;
            } else {
                const horaInicio = horaSel + ":00";
                const horaFinal = sumarHoras(horaSel, dur);
                const solapa = ocupadas.some(res => !(horaFinal <= res.hora_inicio || horaInicio >= res.hora_final));
                // Si la solapa es true => no cabe => deshabilitar
                opt.disabled = solapa;
            }

            // Si una opción quedó deshabilitada y estaba seleccionada, limpiarla
            if (opt.disabled && duracionSelect.value === opt.value) {
                duracionSelect.value = "";
            }
        });
    }

    function getDuracionSeleccionadaHoras() {
        const sel = duracionSelect.selectedOptions[0];
        return sel ? parseInt(sel.dataset.duracion || "0", 10) : null;
    }

    function generarHoras(inicio, fin) {
        const arr = [];
        for (let h = inicio; h <= fin; h++) {
            arr.push(h.toString().padStart(2, "0") + ":00");
        }
        return arr;
    }

    // suma horas a "HH:MM" y devuelve "HH:MM:SS"
    function sumarHoras(hora, cantidad) {
        const [h, m] = hora.split(':').map(Number);
        const nueva = new Date(0, 0, 0, h + Number(cantidad), m);
        return nueva.toTimeString().slice(0, 8);
    }
});
</script>
@endsection