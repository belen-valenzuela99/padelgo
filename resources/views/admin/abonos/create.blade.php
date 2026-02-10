@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Crear Abono</h3>

    <form action="{{ route('abonos.store') }}" method="POST" id="abonoForm">
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
                <select name="cancha_id" class="form-control" required>
                    <option value="">Seleccionar cancha</option>
                    @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
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
                <input type="time" name="hora_inicio" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">Hasta</label>
                <input type="time" name="hora_fin" class="form-control" required>
            </div>
        </div>

        {{-- ================= CALENDARIO ================= --}}
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Fechas del abono</h6>
                <div id="calendario" class="row g-1 text-center"></div>
                <small class="text-muted">Se resaltan automáticamente los días del abono</small>
            </div>
        </div>

        {{-- ================= PRECIO ================= --}}
        <div class="mt-3">
            <label class="form-label fw-bold">Precio</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>

        {{-- ================= RESUMEN ================= --}}
        <div class="alert alert-info mt-4" id="resumen">
            <strong>Resumen:</strong>
            <div id="resumenTexto">Completa los datos para ver el resumen.</div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary">Guardar Abono</button>
            <a href="{{ route('abonos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

{{-- ================= JS ================= --}}
<script>
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
    const mes = mesSelect.value - 1;
    const diaObjetivo = dias[diaSelect.value];

    const totalDias = new Date(year, mes + 1, 0).getDate();

    let fechas = [];

    for (let d = 1; d <= totalDias; d++) {
        const fecha = new Date(year, mes, d);
        const div = document.createElement('div');
        div.className = 'col-1 border p-2';

        if (fecha.getDay() === diaObjetivo) {
            div.classList.add('bg-success', 'text-white');
            fechas.push(d);
        }

        div.innerText = d;
        calendario.appendChild(div);
    }

    resumen.innerHTML = `
        Día: <b>${diaSelect.value}</b><br>
        Mes: <b>${mesSelect.options[mesSelect.selectedIndex].text}</b><br>
        Fechas: <b>${fechas.join(', ')}</b>
    `;
}

mesSelect.addEventListener('change', generarCalendario);
diaSelect.addEventListener('change', generarCalendario);
</script>
@endsection
