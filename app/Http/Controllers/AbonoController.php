<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Reservacion;
use App\Models\Canchas;
use Carbon\Carbon;
use App\Models\TipoReservacion;
use App\Models\User;

use Illuminate\Http\Request;

class AbonoController extends Controller
{
    /**
     * LISTADO DE ABONOS
     */
    public function index()
    {
        $abonos = Abono::with(['usuario', 'cancha']) ->orderByDesc('id')->get();
       return view('admin.abonos.index', compact('abonos'));

    }


    /**
     * FORMULARIO PARA CREAR
     */
    public function create()
{
    $user = auth()->user();

    $canchas = Canchas::with(['tiposReservacion' => function($q) {
            $q->where('activo', true)
              ->orderBy('hora_inicio');
        }])
        ->whereHas('club', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })
        ->get();

    $usuarios = \App\Models\User::where('role', 2)->get();

    return view('admin.abonos.create', compact('canchas', 'usuarios'));
}


    public function edit(Abono $abono)
{ $user = auth()->user();

    $canchas = Canchas::whereHas('club', function ($query) use ($user) {
    $query->where('id_user', $user->id); 
        })
        ->get();
    
    $usuarios = \App\Models\User::where('role', 2)->get();

    return view('admin.abonos.edit', compact('abono', 'canchas', 'usuarios'));
}


public function update(Request $request, Abono $abono)
{
    $request->validate([
        'user_id'      => 'required|exists:users,id',
        'cancha_id'    => 'required|exists:canchas,id',
        'dia_semana'   => 'required|string',
        'mes'          => 'required|integer|min:1|max:12',
        'hora_inicio'  => 'required',
        'hora_fin'     => 'required',
        'precio'       => 'required|numeric|min:0',
    ]);

    // Actualizamos el abono
    $abono->update([
        'user_id'     => $request->user_id,
        'cancha_id'   => $request->cancha_id,
        'dia_semana'  => $request->dia_semana,
        'mes'         => $request->mes,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin'    => $request->hora_fin,
        'precio'      => $request->precio,
    ]);

    // Eliminamos reservas anteriores
    Reservacion::where('abono_id', $abono->id)->delete();

    // Volvemos a generarlas
    $this->generarReservasDelMes($abono);

    return redirect()->route('abonos.index')
        ->with('success', 'Abono actualizado correctamente.');
}



    /**
     * GUARDAR ABONO + CREAR RESERVACIONES
     */
    public function store(Request $request)
{
    $request->validate([
        'user_id'      => 'required|exists:users,id',
        'cancha_id'    => 'required|exists:canchas,id',
        'dia_semana'   => 'required|string',
        'mes'          => 'required|integer|min:1|max:12',
        'hora_inicio'  => 'required',
        'hora_fin'     => 'required',
        'precio'       => 'required|numeric|min:0',
    ]);

    // Obtener fechas disponibles y no disponibles
    $resultado = $this->analizarFechasAbono($request);

    // Si hay fechas no disponibles → mostrar confirmación
    if (count($resultado['no_disponibles']) > 0) {
        return view('admin.abonos.confirmar', [
            'data'            => $request->all(),
            'disponibles'     => $resultado['disponibles'],
            'no_disponibles'  => $resultado['no_disponibles'],
        ]);
    }

    // Si todo está disponible, crear directamente
    return $this->crearAbonoConReservas($request, $resultado['disponibles']);
}
private function analizarFechasAbono(Request $request)
{
    $dias = [
        'lunes' => 1,
        'martes' => 2,
        'miércoles' => 3,
        'jueves' => 4,
        'viernes' => 5,
        'sábado' => 6,
        'domingo' => 0,
    ];

    $anio = now()->year;
    $fecha = Carbon::create($anio, $request->mes, 1)->startOfMonth();

    while ($fecha->dayOfWeek !== $dias[$request->dia_semana]) {
        $fecha->addDay();
    }

    $disponibles = [];
    $noDisponibles = [];

   while ($fecha->month == $request->mes) {

    $inicioAbono = Carbon::parse($fecha->toDateString() . ' ' . $request->hora_inicio);
    $finAbono    = Carbon::parse($fecha->toDateString() . ' ' . $request->hora_fin);

    // ⏭ Cruza medianoche
    if ($request->hora_fin <= $request->hora_inicio) {
        $finAbono->addDay();
    }

    $conflicto = Reservacion::where('cancha_id', $request->cancha_id)
        ->where('reservacion_date', $fecha->toDateString())
        ->get()
        ->some(function ($reserva) use ($inicioAbono, $finAbono) {

            $inicioReserva = Carbon::parse(
                $reserva->reservacion_date . ' ' . $reserva->hora_inicio
            );

            $finReserva = Carbon::parse(
                $reserva->reservacion_date . ' ' . $reserva->hora_final
            );

            // ⏭ Cruza medianoche
            if ($reserva->hora_final <= $reserva->hora_inicio) {
                $finReserva->addDay();
            }

            //  detección real de solapamiento
            return $inicioReserva < $finAbono && $finReserva > $inicioAbono;
        });

    if ($conflicto) {
        $noDisponibles[] = $fecha->toDateString();
    } else {
        $disponibles[] = $fecha->toDateString();
    }

    $fecha->addWeek();
}


    return [
        'disponibles' => $disponibles,
        'no_disponibles' => $noDisponibles,
    ];
}

public function confirmar(Request $request)
{
    $resultado = $this->analizarFechasAbono($request);

    return $this->crearAbonoConReservas($request, $resultado['disponibles']);
}
private function crearAbonoConReservas($request, $fechas)
{
    // 🔹 Calcular duración real en horas
    $duracion = Carbon::parse($request->hora_inicio)
        ->diffInHours(Carbon::parse($request->hora_fin));

    // 🔹 Obtener tipo activo de la cancha
    $tipo = TipoReservacion::where('cancha_id', $request->cancha_id)
        ->where('activo', true)
        ->first();

    if (!$tipo) {
        return back()->with('error', 'No hay tipo de reservación activo para esta cancha.');
    }

    // 🔹 Precio por día
    $precioPorDia = $tipo->precio * $duracion;

    // 🔹 Total mensual del abono
    $totalAbono = $precioPorDia * count($fechas);

    // 🔹 Crear abono (guarda total mensual)
    $abono = Abono::create([
        'user_id' => $request->user_id,
        'cancha_id' => $request->cancha_id,
        'dia_semana' => $request->dia_semana,
        'mes' => $request->mes,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'precio' => $totalAbono, // 🔥 total mensual correcto
        'activo' => true,
    ]);

    // 🔹 Crear reservaciones individuales (precio por día)
    foreach ($fechas as $fecha) {
        Reservacion::create([
            'user_id' => $request->user_id,
            'cancha_id' => $request->cancha_id,
            'reservacion_date' => $fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_final' => $request->hora_fin,
            'precio' => $precioPorDia, // 🔥 precio individual
            'status' => 'programado',
            'abono_id' => $abono->id,
        ]);
    }

    return redirect()->route('abonos.index')
        ->with('success', 'Abono creado con fechas disponibles.');
}



    /**
     * GENERAR TODAS LAS RESERVAS DEL MES PARA ESTE ABONO
     */
    private function generarReservasDelMes(Abono $abono)
    {
        $mesElegido = $abono->mes;
        $anioActual = now()->year;
        $mesActual  = now()->month;

        // Si el mes elegido ya pasó, generar para el año siguiente
        $anio = ($mesElegido < $mesActual) ? $anioActual + 1 : $anioActual;
        $mes  = $mesElegido;

        // Convertir día a número (Carbon: 0 = domingo, 1 = lunes...)
        $dias = [
            'lunes'     => 1,
            'martes'    => 2,
            'miércoles' => 3,
            'jueves'    => 4,
            'viernes'   => 5,
            'sábado'    => 6,
            'domingo'   => 0,
        ];

        $diaNumero = $dias[strtolower($abono->dia_semana)];

        // Primera fecha del mes/año elegido
        $fecha = Carbon::create($anio, $mes, 1)->startOfMonth();

        // Buscar el primer día específico del mes
        while ($fecha->dayOfWeek !== $diaNumero) {
            $fecha->addDay();
        }

        // Crear reservas semanales
        while ($fecha->month == $mes) {

          $inicioAbono = Carbon::parse($fecha->toDateString() . ' ' . $abono->hora_inicio);
$finAbono    = Carbon::parse($fecha->toDateString() . ' ' . $abono->hora_fin);

if ($abono->hora_fin <= $abono->hora_inicio) {
    $finAbono->addDay();
}

$conflicto = Reservacion::where('cancha_id', $abono->cancha_id)
    ->where('reservacion_date', $fecha->toDateString())
    ->get()
    ->some(function ($reserva) use ($inicioAbono, $finAbono) {

        $inicioReserva = Carbon::parse(
            $reserva->reservacion_date . ' ' . $reserva->hora_inicio
        );

        $finReserva = Carbon::parse(
            $reserva->reservacion_date . ' ' . $reserva->hora_final
        );

        if ($reserva->hora_final <= $reserva->hora_inicio) {
            $finReserva->addDay();
        }

        return $inicioReserva < $finAbono && $finReserva > $inicioAbono;
    });

        }
    }


    /**
     * ELIMINAR ABONO + RESERVAS
     */
    public function destroy(Abono $abono)
    {
        $abono->delete(); // gracias a ON DELETE CASCADE las reservas se borran solas

        return redirect()->back()->with('success', 'Abono eliminado correctamente.');
    }

    public function prepararAbono(Request $request)
{
    $request->validate([
        'user_id'      => 'required|exists:users,id',
        'cancha_id'    => 'required|exists:canchas,id',
        'dia_semana'   => 'required|string',
        'mes'          => 'required|integer|min:1|max:12',
        'hora_inicio'  => 'required',
        'hora_fin'     => 'required',
        'precio'       => 'required|numeric|min:0',
    ]);

    // 🔎 Analizar disponibilidad
    $resultado = $this->analizarFechasAbono($request);

    if (count($resultado['no_disponibles']) > 0) {

        return view('admin.abonos.confirmar', [
            'data'            => $request->all(),
            'disponibles'     => $resultado['disponibles'],
            'no_disponibles'  => $resultado['no_disponibles'],
        ]);
    }

    // Buscar relaciones
    $cancha  = Canchas::with('club')->findOrFail($request->cancha_id);
    $usuario = User::findOrFail($request->user_id);

    // Crear objeto tipo preReserva
    $preAbono = (object)[
        'usuario'      => $usuario,
        'cancha'       => $cancha,
        'club'         => $cancha->club,
        'mes'          => $request->mes,
        'dia_semana'   => $request->dia_semana,
        'hora_inicio'  => $request->hora_inicio,
        'hora_fin'     => $request->hora_fin,
        'precio'       => $request->precio,
        'fechas'       => $resultado['disponibles']
    ];

    return view('admin.abonos.confirmar-pago', compact('preAbono'));
}

public function storeFinal(Request $request)
{
    // Volver a analizar disponibilidad por seguridad
    $resultado = $this->analizarFechasAbono($request);

    if (count($resultado['no_disponibles']) > 0) {
        return back()->with('error', 'Algunas fechas ya no están disponibles.');
    }

    return $this->crearAbonoConReservas($request, $resultado['disponibles']);
}
}
