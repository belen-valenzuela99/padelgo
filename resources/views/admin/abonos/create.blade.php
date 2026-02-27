@extends('layouts.main')

@section('content') 
<style>
.calendario-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.calendario-grid div {
    border: 1px solid #dee2e6;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendario-header {
    font-weight: bold;
    background-color: #f8f9fa;
}

.dia-activo {
    background-color: #198754;
    color: white;
}

.precio-card {
    background: linear-gradient(135deg, #e9f5ee, #f8f9fa);
    border-radius: 15px;
}

.precio-card h1 {
    font-size: 2.5rem;
    letter-spacing: 1px;
}
</style>


<div class="container mt-4">
    <h3 class="mb-3">Crear Abono</h3>

    <form action="{{ route('admin.abonos.preparar') }}" method="POST" id="abonoForm">
        @csrf

        {{-- ================= USUARIO ================= --}}
         <div class="row">
            <div class="col-6">
                 <div class="card mb-3">
            <div class="card-body">
                <label class="form-label fw-bold">Jugador</label>
                <input type="text" class="form-control mb-2" id="buscadorUsuario" placeholder="Buscar por nombre o email">

                <select name="user_id" id="userSelect" class="form-control" required>
                    <option value="">Seleccionar jugador</option>
                    @foreach($usuarios as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
            </div>
 

        {{-- ================= CANCHA ================= --}}
        <div class="col-6">
        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label fw-bold">Cancha</label>
                <select name="cancha_id" id="cancha_id" class="form-control" required>
                    <option value="">Seleccionar cancha</option>
                    @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}"
                            data-duracion="{{ $cancha->duracion_maxima }}"
                            data-tipos='@json($cancha->tiposReservacion)'>
                            {{ $cancha->club->nombre }} - {{ $cancha->nombre }}
                        </option>
                    @endforeach
                </select>

            </div>
        </div>
          </div>
          </div>
        {{-- ================= CONFIGURACIÓN ================= --}}
        <div class="row">
            <div class="col-md-4">
                <label class="form-label fw-bold">Mes</label>
                <select name="mes" id="mesSelect" class="form-control" required>
                    <option value="">Mes</option>
                    @foreach([
                        1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
                        5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
                        9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
                    ] as $num => $mes)
                        <option value="{{ $num }}">{{ $mes }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Día</label>
                <select name="dia_semana" id="diaSelect" class="form-control" required>
                    <option value="">Día</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miércoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sábado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
            </div>

                        <div class="col-md-2">
                <label class="form-label fw-bold">Desde</label>
                <select name="hora_inicio" id="hora_inicio" class="form-control" required>
                    <option value="">Seleccione hora</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">Duración</label>
                <select id="duracion" class="form-control" required>
                    <option value="">Seleccione duración</option>
                </select>
            </div>

            <input type="hidden" name="hora_fin" id="hora_fin">

        </div>

        {{-- ================= CALENDARIO ================= --}}
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Fechas del abono</h6>
                <div id="calendario" class="calendario-grid"></div>
                <small class="text-muted">Se resaltan automáticamente los días del abono</small>
            </div>
        </div>

        {{-- ================= PRECIO ================= --}}
        <div class="mt-3">
            <label class="form-label fw-bold">Precio</label>
            <!-- Input oculto que sigue enviando el valor -->
            <input type="hidden" name="precio" id="precio">

            <div class="card mt-4 shadow-sm border-0 precio-card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total del Abono</h6>
                    <h1 class="fw-bold text-success mb-0" id="precioVisual">
                        $ 0
                    </h1>
                </div>
</div>

        </div>

        {{-- ================= RESUMEN ================= --}}
        <div class="alert alert-info mt-4" id="resumen">
            <strong>Resumen:</strong>
            <div id="resumenTexto">Completa los datos para ver el resumen.</div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary" id="btnGuardarAbono">Guardar Abono</button>
            <a href="{{ route('abonos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

{{-- ================= JS ================= --}}
<script>
    let cantidadFechas = 0;

const usuarios = [...document.querySelectorAll('#userSelect option')];
const buscador = document.getElementById('buscadorUsuario');
const userSelect = document.getElementById('userSelect');

buscador.addEventListener('input', () => {
    const texto = buscador.value.toLowerCase();
    userSelect.innerHTML = '<option value="">Seleccionar jugador</option>';

    usuarios.forEach(opt => {
        if (opt.text.toLowerCase().includes(texto)) {
            userSelect.appendChild(opt.cloneNode(true));
        }
    });
});

const calendario = document.getElementById('calendario');
const mesSelect = document.getElementById('mesSelect');
const diaSelect = document.getElementById('diaSelect');
const resumen = document.getElementById('resumenTexto');

const dias = {
    domingo: 0, lunes: 1, martes: 2, miércoles: 3,
    jueves: 4, viernes: 5, sábado: 6
};

function generarCalendario() {

    calendario.innerHTML = '';

    if (!mesSelect.value || !diaSelect.value) return;

    const year = new Date().getFullYear();
    const mes = parseInt(mesSelect.value) - 1;
    const diaObjetivo = dias[diaSelect.value];

    const totalDias = new Date(year, mes + 1, 0).getDate();

    let fechas = [];
    cantidadFechas = 0;

    const diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

    // 🔹 Headers
    diasSemana.forEach(d => {
        const div = document.createElement('div');
        div.className = 'calendario-header';
        div.innerText = d;
        calendario.appendChild(div);
    });

    const primerDiaMes = new Date(year, mes, 1).getDay();

    // 🔹 Espacios vacíos
    for (let i = 0; i < primerDiaMes; i++) {
        const div = document.createElement('div');
        calendario.appendChild(div);
    }

    // 🔹 Días reales
    const hoy = new Date();
    hoy.setHours(0,0,0,0);

    for (let d = 1; d <= totalDias; d++) {

        const fecha = new Date(year, mes, d);
        fecha.setHours(0,0,0,0);

        const div = document.createElement('div');

        const esMesActual = (mes === hoy.getMonth());
        const esDiaPasado = fecha < hoy;

        // Si es mes actual y el día ya pasó, no lo activamos
        if (
            fecha.getDay() === diaObjetivo &&
            (!esMesActual || !esDiaPasado)
        ) {
            div.classList.add('dia-activo');
            fechas.push(d);
        }

        // Opcional: marcar visualmente días pasados en gris
        if (esMesActual && esDiaPasado) {
            div.style.opacity = "0.3";
        }

        div.innerText = d;
        calendario.appendChild(div);
    }

    cantidadFechas = fechas.length;
    const boton = document.getElementById('btnGuardarAbono');

    if (cantidadFechas === 0) {

        boton.disabled = true;
        boton.classList.add('btn-secondary');
        boton.classList.remove('btn-primary');
        horaInicioSelect.disabled = true;
        duracionSelect.disabled = true;
        resumen.innerHTML = `
            <span class="text-danger">
                No quedan fechas disponibles en este mes para ese día.
            </span>
        `;

        // Resetear precio
        precioInput.value = 0;
        document.getElementById("precioVisual").innerText = "$ 0";

    } else {
        horaInicioSelect.disabled = false;
        duracionSelect.disabled = false;
        boton.disabled = false;
        boton.classList.add('btn-primary');
        boton.classList.remove('btn-secondary');
    }
    resumen.innerHTML = `
        Día: <b>${diaSelect.value}</b><br>
        Mes: <b>${mesSelect.options[mesSelect.selectedIndex].text}</b><br>
        Fechas: <b>${fechas.join(', ')}</b><br>
        Total días del abono: <b>${cantidadFechas}</b>
    `;

    actualizarPrecioYFin();
}




mesSelect.addEventListener('change', generarCalendario);
diaSelect.addEventListener('change', generarCalendario);

const canchaSelect = document.getElementById("cancha_id");
const horaInicioSelect = document.getElementById("hora_inicio");
const duracionSelect = document.getElementById("duracion");
const precioInput = document.getElementById("precio");
const horaFinInput = document.getElementById("hora_fin");

let tipos = [];
let duracionMaxima = 1;

canchaSelect.addEventListener("change", function() {

    const selected = this.selectedOptions[0];
    if (!selected) return;

    tipos = JSON.parse(selected.dataset.tipos || "[]");
    duracionMaxima = parseInt(selected.dataset.duracion || 1);


    construirHoras();
    construirDuraciones();

    //  RESET COMPLETO
    horaInicioSelect.value = "";
    duracionSelect.value = "";
    horaFinInput.value = "";

    precioInput.value = 0;
    document.getElementById("precioVisual").innerText = "$ 0";
});


function construirHoras() {
    horaInicioSelect.innerHTML = '<option value="">Seleccione hora</option>';

    tipos.forEach(tipo => {

        let inicio = convertirAMinutos(tipo.hora_inicio);
        let fin = convertirAMinutos(tipo.hora_fin);

        // Si cruza medianoche
        if (fin <= inicio) {
            fin += 24 * 60;
        }

        for (let m = inicio; m < fin; m += 60) {

            const horaFormateada = minutosAHora(m);

            const opt = document.createElement("option");
            opt.value = horaFormateada;
            opt.textContent = horaFormateada;
            opt.dataset.precio = tipo.precio;

            horaInicioSelect.appendChild(opt);
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

horaInicioSelect.addEventListener("change", actualizarPrecioYFin);
duracionSelect.addEventListener("change", actualizarPrecioYFin);

function actualizarPrecioYFin() {

    const horaInicio = horaInicioSelect.value;
    const duracion = parseInt(duracionSelect.value || 0);

    if (!horaInicio || !duracion) return;

    const selectedOption = horaInicioSelect.selectedOptions[0];
    const precioBase = parseFloat(selectedOption.dataset.precio || 0);

    const precioPorDia = precioBase * duracion;
    const precioTotalAbono = precioPorDia * cantidadFechas;

    precioInput.value = precioTotalAbono;

    document.getElementById("precioVisual").innerText =
    formatearMoneda(precioTotalAbono);



    const horaFinal = sumarHoras(horaInicio, duracion);
    horaFinInput.value = horaFinal;
}

function sumarHoras(hora, cantidad) {
    const [h, m] = hora.split(':').map(Number);
    const nueva = new Date(0, 0, 0, h + cantidad, m);
    return nueva.toTimeString().slice(0, 8);
}
function convertirAMinutos(hora) {
    const [h, m] = hora.split(':').map(Number);
    return h * 60 + m;
}

function minutosAHora(minutos) {
    minutos = minutos % (24 * 60);

    const h = Math.floor(minutos / 60).toString().padStart(2, '0');
    const m = (minutos % 60).toString().padStart(2, '0');

    return `${h}:${m}:00`;
}

function formatearMoneda(valor) {
    return "$ " + new Intl.NumberFormat('es-CO').format(valor);
}


</script>
@endsection
