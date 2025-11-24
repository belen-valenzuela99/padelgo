@extends('layouts.main')

@section('content')

<style>
    .horarios {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding: 8px;
        white-space: nowrap;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fafafa;
    }

    .hora-box {
        min-width: 60px;
        padding: 6px 4px;
        font-size: 12px;
        border: 1px solid #0d6efd;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s;
        text-align: center;
        background:white;
    }

    .hora-box:hover {
        background: #0d6efd;
        color: white;
    }

    .hora-ocupada {
        border-color: #dc3545 !important;
        color: #dc3545 !important;
        cursor: not-allowed !important;
        background: #ffe5e5 !important;
    }

    .hora-ocupada:hover {
        background: #ffe5e5 !important;
        color: #dc3545 !important;
    }

    #miniModal {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        width: 180px;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
        display: none;
        z-index: 999;
    }

    #miniModal select {
        font-size: 13px;
    }
</style>

<div class="container py-3">

    <h4 class="fw-bold">{{ $club->nombre }} - {{ $cancha->nombre }}</h4>
    <p class="text-muted" style="font-size: 13px;">Dirección: {{ $club->direccion }}</p>

    {{-- FECHA --}}
    <div class="d-flex justify-content-center align-items-center gap-3 mb-3">

        <button id="prevDay" class="btn btn-secondary btn-sm"><</button>

        <div class="d-flex align-items-center">
            <h5 id="fechaActual" class="my-0 me-2">
            </h5>

            {{-- Seleccionar fecha manual --}}
            <input 
                type="date" 
                id="selectorFecha" 
                class="form-control form-control-sm" 
                style="width: 150px"
                value="{{ $fecha }}"
                min="{{ \Carbon\Carbon::today()->toDateString() }}"
            >
        </div>

        <button id="nextDay" class="btn btn-secondary btn-sm">></button>
    </div>


    <input type="hidden" id="fecha" value="{{ $fecha }}">

    {{-- HORARIOS --}}
    <h6 class="mb-2">Selecciona un horario:</h6>

    <div id="contenedorHorarios" class="horarios">
        <div class="text-muted">Cargando horarios...</div>
    </div>

    {{-- MINI MODAL --}}
    <div id="miniModal">
        <h6 class="fw-bold mb-2" style="font-size: 13px;">Duración:</h6>

        <select id="duracionSelect" class="form-select form-select-sm mb-2">
            <option value="">Seleccione</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->id }}" data-duracion="{{ $tipo->franja_horaria }}">
                    {{ $tipo->franja_horaria }} h - ${{ $tipo->precio }}
                </option>
            @endforeach
        </select>

        <button id="cerrarModal" class="btn btn-danger btn-sm w-100">Cerrar</button>
    </div>

    {{-- FORMULARIO REAL --}}
    <form action="{{ route('reservar.store') }}" method="POST" id="formReserva" class="mt-3">
        @csrf
        <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">
        <input type="hidden" name="fecha" id="inputFecha">
        <input type="hidden" name="hora" id="inputHora">
        <input type="hidden" name="id_tipo_reservacion" id="inputDuracion">
        <input type="hidden" name="status" value="programado">

        <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg w-100">
            Confirmar Reserva
        </button>
    </form>

</div>



{{-- JAVASCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const canchaId = "{{ $cancha->id }}";
    const fechaActual = document.getElementById("fecha");
    const contenedorHorarios = document.getElementById("contenedorHorarios");

    const modal = document.getElementById("miniModal");
    const duracionSelect = document.getElementById("duracionSelect");
    const cerrarModal = document.getElementById("cerrarModal");

    const inputFecha = document.getElementById("inputFecha");
    const inputHora = document.getElementById("inputHora");
    const inputDuracion = document.getElementById("inputDuracion");

    inputFecha.value = fechaActual.value;

    // variable global dentro del script para poder consultarla al abrir modal
    let ocupadas = [];

    cargarHorarios();

    // ---- CARGAR HORAS OCUPADAS ----
    async function cargarHorarios() {
        contenedorHorarios.innerHTML = "<div class='text-muted'>Cargando...</div>";

        let fecha = fechaActual.value;
        let resp = await fetch(`/horas-ocupadas/${canchaId}/${fecha}`);
        if (!resp.ok) {
            ocupadas = [];
        } else {
            ocupadas = await resp.json();
        }

        construirHorarios(ocupadas);
    }

    // ---- ARMAR HORARIOS ----
    function construirHorarios(ocupadasArr) {
        contenedorHorarios.innerHTML = "";

        const horas = generarHoras(8, 23);

        horas.forEach(hora => {
            let ocupado = ocupadasArr.some(res =>
                // si la hora (inicio) está dentro de un intervalo ocupado
                timeToMinutes(hora + ":00") >= timeToMinutes(res.hora_inicio)
                && timeToMinutes(hora + ":00") < timeToMinutes(res.hora_final)
            );

            let box = document.createElement("div");
            box.className = "hora-box";
            box.innerText = hora;

            if (ocupado) {
                box.classList.add("hora-ocupada");
            } else {
                box.onclick = (e) => abrirModal(e, hora);
            }

            contenedorHorarios.appendChild(box);
        });
    }

    function generarHoras(inicio, fin) {
        let arr = [];
        for (let h = inicio; h <= fin; h++) {
            arr.push(h.toString().padStart(2, "0") + ":00");
        }
        return arr;
    }

    // Helper: convierte "HH:MM:SS" o "HH:MM" a minutos desde la medianoche
    function timeToMinutes(t) {
        const parts = t.split(':').map(Number);
        const h = parts[0] || 0;
        const m = parts[1] || 0;
        return h * 60 + m;
    }

    // suma cantidad (horas) a una hora "HH:MM" y devuelve "HH:MM:SS"
    function sumarHorasAMinutos(hora, cantidadHoras) {
        const [h, m] = hora.split(':').map(Number);
        const totalMin = h * 60 + m + cantidadHoras * 60;
        const nh = Math.floor((totalMin % (24 * 60)) / 60);
        const nm = totalMin % 60;
        return String(nh).padStart(2,'0') + ":" + String(nm).padStart(2,'0') + ":00";
    }

    // ---- MODAL ----
    // Al abrir el modal, además de posicionarlo, deshabilitamos las duraciones que solapan
    function abrirModal(ev, hora) {
        inputHora.value = hora;

        const rect = ev.target.getBoundingClientRect();
        modal.style.left = rect.left + "px";
        modal.style.top = rect.bottom + 6 + "px";
        modal.style.display = "block";

        // Habilitar todas primero
        Array.from(duracionSelect.options).forEach(opt => {
            opt.disabled = false;
            opt.style.color = "";
            opt.style.backgroundColor = "";
        });

        // Para cada opción de duración (salteamos el primer option que es placeholder)
        const opciones = Array.from(duracionSelect.options).slice(1);
        opciones.forEach(opt => {
            const durHoras = parseInt(opt.dataset.duracion || "0", 10);
            if (!durHoras || durHoras <= 0) return;

            // calcular inicio y fin en minutos
            const inicioMin = timeToMinutes(hora + ":00");
            const finMin = inicioMin + durHoras * 60;

            // si existe alguna reserva ocupada que solape => deshabilitar
            const solapa = ocupadas.some(res => {
                const resInicio = timeToMinutes(res.hora_inicio);
                const resFin = timeToMinutes(res.hora_final);
                // overlap check: start < resFin && end > resInicio
                return (inicioMin < resFin) && (finMin > resInicio);
            });

            if (solapa) {
                opt.disabled = true;
                opt.style.color = "#999";
                opt.style.backgroundColor = "#f8f9fa";
            } else {
                opt.disabled = false;
                opt.style.color = "";
                opt.style.backgroundColor = "";
            }
        });

        // limpiar selección previa
        duracionSelect.value = "";
        inputDuracion.value = "";
    }

    cerrarModal.onclick = () => modal.style.display = "none";

    // Al seleccionar una duración válida, guardamos y cerramos modal
    duracionSelect.onchange = () => {
        let sel = duracionSelect.value;
        if (sel) {
            inputDuracion.value = sel;
            modal.style.display = "none";
        }
    };

    // ---- CAMBIO DE FECHA ----
    document.getElementById("prevDay").onclick = () => cambiarDia(-1);
    document.getElementById("nextDay").onclick = () => cambiarDia(1);

    function cambiarDia(d) {
        let f = new Date(fechaActual.value);
        f.setDate(f.getDate() + d);
        let nueva = f.toISOString().split("T")[0];
        window.location.href = `?fecha=${nueva}`;
    }

    // --- Selector de Fecha ---
    const selectorFecha = document.getElementById("selectorFecha");
    const fechaActualTexto = document.getElementById("fechaActual");

    // Cuando se elige una fecha manualmente
    selectorFecha.addEventListener("change", () => {
        if (!selectorFecha.value) return;

       

        // Redirigir para recargar horarios
        window.location.href = `?fecha=${selectorFecha.value}`;
    });

});
</script>


@endsection