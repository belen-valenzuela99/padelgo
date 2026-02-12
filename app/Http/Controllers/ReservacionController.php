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

        // Filtrar reservaciones donde:
        // reservacion.cancha.club.id_user == gestor (usuario logueado)
        $reservacions = Reservacion::whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->get();
        return view('admin.reservacions.index', compact('reservacions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = auth()->id();

        $canchas = Canchas::whereHas('club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->get();

        $tipos = TipoReservacion::orderBy('hora_inicio')->get();

        $usuarios = \App\Models\User::where('role', 2)->get();

        return view('admin.reservacions.create', compact('canchas', 'tipos', 'usuarios'));
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
}
