@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Nueva Reservación</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
        <option 
            value="{{ $cancha->id }}"
            data-tipos='@json($cancha->tiposReservacion)'
            data-duracion="{{ $cancha->duracion_maxima ?? 1 }}"
        >
            {{ $cancha->nombre }}
        </option>
    @endforeach
</select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date"
            name="fecha"
            id="fecha"
            class="form-control"
            min="{{ date('Y-m-d') }}"
            value="{{ old('fecha') }}"
            required>
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

    let tipos = [];
    let duracionMaxima = 1;
    let ocupadas = [];

    canchaSelect.addEventListener("change", cargarTodo);
    fechaInput.addEventListener("change", cargarTodo);
    horaSelect.addEventListener("change", actualizarDuracionesDisponibles);

    async function cargarTodo() {
        resetSelects();

        const canchaId = canchaSelect.value;
        const fecha = fechaInput.value;
        if (!canchaId || !fecha) return;

        const selected = canchaSelect.selectedOptions[0];
        tipos = JSON.parse(selected.dataset.tipos || "[]");
        duracionMaxima = parseInt(selected.dataset.duracion || 1);

        await cargarHorasOcupadas(canchaId, fecha);
        construirHoras();
        construirDuraciones();
    }

    function resetSelects() {
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        duracionSelect.innerHTML = '<option value="">Seleccione duración</option>';
    }

    async function cargarHorasOcupadas(canchaId, fecha) {
        try {
            const resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
            ocupadas = await resp.json();
        } catch (e) {
            ocupadas = [];
        }
    }

    function construirHoras() {
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

        const ahora = new Date();
        const hoyStr =
            ahora.getFullYear() + '-' +
            String(ahora.getMonth() + 1).padStart(2, '0') + '-' +
            String(ahora.getDate()).padStart(2, '0');

        const esHoy = fechaInput.value === hoyStr;
        const ahoraMin = ahora.getHours() * 60 + ahora.getMinutes();

        tipos.forEach(tipo => {

            let ini = convertirAMinutos(tipo.hora_inicio);
            let fin = convertirAMinutos(tipo.hora_fin);

            const cruzaMedianoche = fin <= ini;
            if (cruzaMedianoche) fin += 1440;

            for (let m = ini; m < fin; m += 60) {

                let minutosOpcion = m;
                let esDiaSiguiente = false;

                if (cruzaMedianoche && m >= 1440) {
                    minutosOpcion = m - 1440;
                    esDiaSiguiente = true;
                }

                const hora = minutosAHora(minutosOpcion);

                let horaPasada = false;
                if (esHoy && !esDiaSiguiente) {
                    if (convertirAMinutos(hora) <= ahoraMin) {
                        horaPasada = true;
                    }
                }

                const horaFinalMin = sumarHoras(hora, 1);

                const ocupada = ocupadas.some(res =>
                    !(horaFinalMin <= res.hora_inicio || hora >= res.hora_final)
                );

                const opt = document.createElement("option");
                opt.value = hora.slice(0,5);
                opt.textContent = hora.slice(0,5);

                if (horaPasada) opt.textContent += " (ya pasó)";
                if (ocupada) opt.textContent += " (ocupada)";

                if (horaPasada || ocupada) opt.disabled = true;

                horaSelect.appendChild(opt);
            }
        });
    }

    function construirDuraciones() {
        duracionSelect.innerHTML = '<option value="">Seleccione duración</option>';
        for (let i = 1; i <= duracionMaxima; i++) {
            const opt = document.createElement("option");
            opt.value = i;
            opt.textContent = i + " hora(s)";
            duracionSelect.appendChild(opt);
        }
    }

    function actualizarDuracionesDisponibles() {
        const horaSel = horaSelect.value;
        if (!horaSel) return;

        const horaInicio = horaSel + ":00";

        let tipoActual = null;

        tipos.forEach(tipo => {
            let ini = convertirAMinutos(tipo.hora_inicio);
            let fin = convertirAMinutos(tipo.hora_fin);

            if (fin <= ini) fin += 1440;

            let hSel = convertirAMinutos(horaInicio);
            if (hSel < ini) hSel += 1440;

            if (hSel >= ini && hSel < fin) {
                tipoActual = { ini, fin };
            }
        });

        const opciones = Array.from(duracionSelect.options).slice(1);

        opciones.forEach(opt => {
            const dur = parseInt(opt.value, 10);

            let hIni = convertirAMinutos(horaInicio);
            let hFin = hIni + (dur * 60);

            if (tipoActual && hIni < tipoActual.ini) {
                hIni += 1440;
                hFin += 1440;
            }

            const superaRango = tipoActual ? (hFin > tipoActual.fin) : true;

            const solapa = ocupadas.some(res => {
                let rIni = convertirAMinutos(res.hora_inicio);
                let rFin = convertirAMinutos(res.hora_final);
                if (rFin <= rIni) rFin += 1440;

                let sIni = convertirAMinutos(horaInicio);
                let sFin = convertirAMinutos(sumarHoras(horaInicio, dur));
                if (sFin <= sIni) sFin += 1440;

                return Math.max(sIni, rIni) < Math.min(sFin, rFin);
            });

            const invalida = superaRango || solapa;
            opt.disabled = invalida;

            if (invalida && duracionSelect.value === opt.value) {
                duracionSelect.value = "";
            }
        });
    }

    function convertirAMinutos(hora) {
        const [h, m] = hora.split(':').map(Number);
        return h * 60 + m;
    }

    function minutosAHora(minutos) {
        minutos = minutos % 1440;
        const h = Math.floor(minutos / 60).toString().padStart(2, '0');
        const m = (minutos % 60).toString().padStart(2, '0');
        return `${h}:${m}:00`;
    }

    function sumarHoras(hora, cantidad) {
        const [h, m] = hora.split(':').map(Number);
        const d = new Date(0, 0, 0, h + cantidad, m);
        return d.toTimeString().slice(0, 8);
    }

});
</script>

@endsection