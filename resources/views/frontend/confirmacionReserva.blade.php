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

    const selectorFecha = document.getElementById("selectorFecha");
    const btnPrev = document.getElementById("prevDay");
    const btnNext = document.getElementById("nextDay");
    const fechaActualTexto = document.getElementById("fechaActual");

    inputFecha.value = fechaActual.value;

    let ocupadas = [];

    cargarHorarios();
    actualizarBotonPrev(); // activar lógica al inicio

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
                timeToMinutes(hora + ":00") >= timeToMinutes(res.hora_inicio) &&
                timeToMinutes(hora + ":00") < timeToMinutes(res.hora_final)
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

    function timeToMinutes(t) {
        const parts = t.split(':').map(Number);
        return (parts[0] * 60) + (parts[1] || 0);
    }

    function sumarHorasAMinutos(hora, cantidadHoras) {
        const [h, m] = hora.split(':').map(Number);
        const totalMin = h * 60 + m + cantidadHoras * 60;
        const nh = Math.floor((totalMin % (24 * 60)) / 60);
        const nm = totalMin % 60;
        return `${String(nh).padStart(2,'0')}:${String(nm).padStart(2,'0')}:00`;
    }

    // ---- MODAL ----
    function abrirModal(ev, hora) {
        inputHora.value = hora;

        const rect = ev.target.getBoundingClientRect();
        modal.style.left = rect.left + "px";
        modal.style.top = rect.bottom + 6 + "px";
        modal.style.display = "block";

        Array.from(duracionSelect.options).forEach(opt => {
            opt.disabled = false;
            opt.style.color = "";
            opt.style.backgroundColor = "";
        });

        const opciones = Array.from(duracionSelect.options).slice(1);
        opciones.forEach(opt => {
            const durHoras = parseInt(opt.dataset.duracion || "0", 10);
            if (!durHoras) return;

            const inicioMin = timeToMinutes(hora + ":00");
            const finMin = inicioMin + durHoras * 60;

            const solapa = ocupadas.some(res => {
                const resInicio = timeToMinutes(res.hora_inicio);
                const resFin = timeToMinutes(res.hora_final);
                return (inicioMin < resFin) && (finMin > resInicio);
            });

            if (solapa) {
                opt.disabled = true;
                opt.style.color = "#999";
                opt.style.backgroundColor = "#f8f9fa";
            }
        });

        duracionSelect.value = "";
        inputDuracion.value = "";
    }

    cerrarModal.onclick = () => modal.style.display = "none";

    duracionSelect.onchange = () => {
        let sel = duracionSelect.value;
        if (sel) {
            inputDuracion.value = sel;
            modal.style.display = "none";
        }
    };

    // ---- CAMBIO DE FECHA ----
    btnPrev.onclick = () => cambiarDia(-1);
    btnNext.onclick = () => cambiarDia(1);

    function crearFechaLocal(str) {
        const [y, m, d] = str.split("-").map(Number);
        return new Date(y, m - 1, d); // ← esto crea fecha LOCAL
    }

    function cambiarDia(d) {
        let f = crearFechaLocal(fechaActual.value);
        f.setDate(f.getDate() + d);

        const hoy = new Date();
        hoy.setHours(0,0,0,0);

        if (d === -1 && f < hoy) return;

        let nueva = f.toLocaleDateString("en-CA");
        window.location.href = `?fecha=${nueva}`;
    }



    // ---- CONTROL VISUAL DE BOTÓN "<" ----
    function actualizarBotonPrev() {
        const hoy = new Date();
        hoy.setHours(0,0,0,0);

        const f = crearFechaLocal(fechaActual.value);
        f.setHours(0,0,0,0);
        
        if (f <= hoy) {
            btnPrev.disabled = true;
            btnPrev.classList.add("opacity-50");
            btnPrev.style.cursor = "not-allowed";
        } else {
            btnPrev.disabled = false;
            btnPrev.classList.remove("opacity-50");
            btnPrev.style.cursor = "pointer";
        }
    }


    actualizarBotonPrev();

    // --- Selector de Fecha ---
    selectorFecha.addEventListener("change", () => {
        if (!selectorFecha.value) return;

        window.location.href = `?fecha=${selectorFecha.value}`;
    });


});
</script>



@endsection