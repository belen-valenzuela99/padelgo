<?php

namespace App\Http\Controllers;
use Carbon\Carbon;


use App\Models\Reservacion;
use Illuminate\Http\Request;
use App\Models\Canchas;
use App\Models\TipoReservacion;
 
class ReservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservacions = Reservacion::all();
         return view('admin.reservacions.index', compact('reservacions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $canchas=Canchas::all();
        $tipos = TipoReservacion::all();
        return view('admin.reservacions.create', compact('canchas', 'tipos'));
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
        'id_tipo_reservacion' => 'required|integer',
        'status' => 'nullable|in:programado,cancelado,turno perdido,turno completado',
    ]);

    $fechaReserva = Carbon::parse($request->fecha)->format('Y-m-d');
    $horaInicio = Carbon::parse($request->hora)->format('H:i:s');
    $id_tipo_reservacion = $request->id_tipo_reservacion;
    
    $duracion = TipoReservacion::find($request->id_tipo_reservacion);
    $contenido_duracion = (int) $duracion->franja_horaria;

    $horaFinal = Carbon::parse($horaInicio)->addHours($contenido_duracion)->format('H:i:s');

    Reservacion::create([
        'user_id' => auth()->id(),
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
        'reservacion_date' => $fechaReserva,
        'hora_inicio' => $horaInicio,
        'hora_final' => $horaFinal,
        'status' => $request->status ?? 'programado',
    ]);

    return redirect()->route('reservacions.index')->with('success', 'Reservación creada correctamente.');
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
        'fecha' => 'required|date',
        'hora' => 'required',
        'cancha_id' => 'required|exists:canchas,id',
        'id_tipo_reservacion' => 'required|integer',
        'status' => 'nullable|in:programado,cancelado,turno perdido,turno completado',
    ]);

    $fechaReserva = Carbon::parse($request->fecha)->format('Y-m-d');
    $horaInicio = Carbon::parse($request->hora)->format('H:i:s');
    
    $duracion = TipoReservacion::find($request->id_tipo_reservacion);
    $contenido_duracion = (int) $duracion->franja_horaria;

    $horaFinal = Carbon::parse($horaInicio)->addHours($contenido_duracion)->format('H:i:s');
    $reservacion->update([
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
        'reservacion_date' => $fechaReserva,
        'hora_inicio' => $horaInicio,
        'hora_final' => $horaFinal,
        'status' => $request->status ?? 'programado',
    ]);

    return redirect()->route('reservacions.index')->with('success', 'Reservación actualizada correctamente.');
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
