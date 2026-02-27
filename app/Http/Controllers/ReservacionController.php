<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Canchas;
use App\Models\TipoReservacion;
use App\Models\User;

class ReservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $userId = auth()->id();

    $reservacions = Reservacion::whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->orderByDesc('id') // último ID primero
        ->get();

    return view('admin.reservacions.index', compact('reservacions'));
}


    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $userId = auth()->id();

    $canchas = Canchas::with(['tiposReservacion' => function($q) {
            $q->where('activo', true)
              ->orderBy('hora_inicio');
        }])
        ->whereHas('club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->get();

    $usuarios = \App\Models\User::where('role', 2)->get();

    return view('admin.reservacions.create', compact('canchas', 'usuarios'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora' => 'required',
        'cancha_id' => 'required|exists:canchas,id',
        'duracion' => 'required|integer|min:1',
        'status' => 'nullable|in:programado,cancelado,turno perdido,turno completado',
        'usuario' => 'required',
    ]);

    $fechaReserva = Carbon::parse($request->fecha)->format('Y-m-d');
    $horaInicio   = Carbon::parse($request->hora)->format('H:i:s');
    $duracion     = (int) $request->duracion;

    $horaFinal = Carbon::parse($horaInicio)
        ->addHours($duracion)
        ->format('H:i:s');

    $cancha = Canchas::findOrFail($request->cancha_id);
    $usuario = User::findOrFail($request->usuario);

    /*
    |--------------------------------------------------------------------------
    | Buscar franja horaria correcta
    |--------------------------------------------------------------------------
    */
    $tipos = TipoReservacion::orderBy('hora_inicio')->get();
    $tipoSeleccionado = null;

    $horaCarbon = Carbon::createFromFormat('H:i:s', $horaInicio);

    foreach ($tipos as $tipo) {
        $inicioTipo = Carbon::parse($tipo->hora_inicio);
        $finTipo    = Carbon::parse($tipo->hora_fin);

        if ($horaCarbon->gte($inicioTipo) && $horaCarbon->lt($finTipo)) {
            $tipoSeleccionado = $tipo;
            break;
        }
    }

    if (!$tipoSeleccionado) {
        $tipoSeleccionado = $tipos->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular precio
    |--------------------------------------------------------------------------
    */
    $precioTotal = $tipoSeleccionado->precio * $duracion;

    /*
    |--------------------------------------------------------------------------
    | Crear reservación
    |--------------------------------------------------------------------------
    */
    Reservacion::create([
        'user_id' => $usuario->id,
        'cancha_id' => $cancha->id,
        'id_tipo_reservacion' => $tipoSeleccionado->id,
        'reservacion_date' => $fechaReserva,
        'hora_inicio' => $horaInicio,
        'hora_final' => $horaFinal,
        'precio' => $precioTotal,
        'status' => $request->status ?? 'programado',
    ]);

    return redirect()
        ->route('reservacions.index')
        ->with('success', 'Reservación creada correctamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(Reservacion $reservacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservacion $reservacion)
    {
    $canchas = Canchas::all();
    $tipos = TipoReservacion::all();

    return view('admin.reservacions.edit', compact('reservacion', 'canchas', 'tipos'));
}


    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id)
{
    $reservacion = Reservacion::findOrFail($id);

    $request->validate([
        'status' => 'required|in:programado,cancelado,turno perdido,turno completado',
    ]);

    $reservacion->update([
        'status' => $request->status,
    ]);

    return redirect()->route('reservacions.index')
        ->with('success', 'Estado actualizado correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservacion $reservacion)
    {
        $reservacion->delete();
        return redirect()->route('reservacions.index')->with('success', 'Reservacion eliminada.');
    }

    public function prepararReservacionAdmin(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora' => 'required',
        'cancha_id' => 'required|exists:canchas,id',
        'duracion' => 'required|integer|min:1',
        'usuario' => 'required|exists:users,id',
    ]);

    $fechaReserva = Carbon::parse($request->fecha)->format('Y-m-d');
    $horaInicio   = Carbon::parse($request->hora)->format('H:i:s');
    $duracion     = (int) $request->duracion;

    $horaFinal = Carbon::parse($horaInicio)
        ->addHours($duracion)
        ->format('H:i:s');

    $cancha  = Canchas::with('club')->findOrFail($request->cancha_id);
    $usuario = User::findOrFail($request->usuario);

    /*
    |---------------------------------------
    | Buscar tipo correcto por horario
    |---------------------------------------
    */
    $tipos = TipoReservacion::where('cancha_id', $cancha->id)
        ->where('activo', true)
        ->orderBy('hora_inicio')
        ->get();

    $horaCarbon = Carbon::createFromFormat('H:i:s', $horaInicio);
    $tipoSeleccionado = null;

    foreach ($tipos as $tipo) {

    $inicioTipo = Carbon::createFromFormat('H:i:s', $tipo->hora_inicio);
    $finTipo    = Carbon::createFromFormat('H:i:s', $tipo->hora_fin);

    // Caso normal (no cruza medianoche)
    if ($inicioTipo->lt($finTipo)) {

        if ($horaCarbon->gte($inicioTipo) && $horaCarbon->lt($finTipo)) {
            $tipoSeleccionado = $tipo;
            break;
        }

    } 
    // Caso cruza medianoche
    else {

        if (
            $horaCarbon->gte($inicioTipo) ||
            $horaCarbon->lt($finTipo)
        ) {
            $tipoSeleccionado = $tipo;
            break;
        }
    }
}

// --- Manejo de horas fuera de rango ---
if (!$tipoSeleccionado) {

    $primerTipo = $tipos->first();
    $ultimoTipo = $tipos->last();

    if ($horaCarbon->lt(Carbon::parse($primerTipo->hora_inicio))) {
        $tipoSeleccionado = $primerTipo;

    } elseif ($horaCarbon->gte(Carbon::parse($ultimoTipo->hora_fin))) {
        $tipoSeleccionado = $ultimoTipo;

    } else {
        $tipoSeleccionado = $primerTipo;
    }
}

    $precioTotal = $tipoSeleccionado->precio * $duracion;

    // Objeto tipo preReserva
    $preReserva = (object)[
        'fecha'               => $fechaReserva,
        'hora_inicio'         => $horaInicio,
        'hora_final'          => $horaFinal,
        'duracion'            => $duracion,
        'precio_por_hora'     => $tipoSeleccionado->precio,
        'total'               => $precioTotal,
        'cancha'              => $cancha,
        'usuario'             => $usuario,
        'id_tipo_reservacion' => $tipoSeleccionado->id,
        'cancha_id'           => $cancha->id,
    ];

    return view('admin.reservacions.confirmar-pago', compact('preReserva'));
}

public function storeFinal(Request $request)
{
    $reservacion = Reservacion::create([
        'user_id' => $request->user_id,
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
        'reservacion_date' => $request->fecha,
        'hora_inicio' => $request->hora_inicio,
        'hora_final' => $request->hora_final,
        'precio' => $request->precio,
        'status' => 'programado',
    ]);

    return view('admin.reservacions.ticketReserva', compact('reservacion'))
        ->with('success', 'Reservación creada correctamente.');
} 
}
