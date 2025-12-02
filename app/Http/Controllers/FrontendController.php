<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Canchas;
use App\Models\Reservacion;
use App\Models\TipoReservacion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;



class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubes = Club::all();
        return view('frontend.index', compact('clubes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function clubDetalle($id)
    {
        $club = Club::with("canchas")->findOrFail($id);
        $canchas = $club->canchas;
        return view('frontend.club', compact('club', 'canchas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function confirmacionReserva($id, Request $request)
    {
        $user = auth()->user();
        $cancha = Canchas::findOrFail($id);
        $tipos = TipoReservacion::all();
        $club = $cancha->club;

        // Fecha seleccionada o día de hoy
        $fecha = $request->query('fecha', Carbon::today()->toDateString());

        // Generar horas desde 8am a 23pm
        $inicio = Carbon::createFromTime(8, 0);
        $fin = Carbon::createFromTime(23, 0);

        $horas = [];
        for ($h = $inicio->copy(); $h->lte($fin); $h->addHour()) {
            $horas[] = $h->format('H:i');
        }

        return view('frontend.confirmacionReserva', compact(
            'user', 'cancha', 'tipos', 'club', 'fecha', 'horas'
        ));
    }
        
    
public function registrarReservacion(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'fecha' => 'required|date',
        'hora_inicio' => 'required',
        'hora_final' => 'required',
        'cancha_id' => 'required|exists:canchas,id',
        'id_tipo_reservacion' => 'required|integer',
    ]);

    $reservacion = Reservacion::create([
        'user_id' => $user->id,
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
        'reservacion_date' => $request->fecha,
        'hora_inicio' => $request->hora_inicio,
        'hora_final' => $request->hora_final,
        'status' => 'programado',
    ]);

    return view('frontend.reservaTicket', compact('reservacion'))
        ->with('success', 'Reservación creada correctamente.');
}

 public function horasOcupadas($canchaId,  $fecha){
        $reservas = Reservacion::where('cancha_id', $canchaId)
            ->whereDate('reservacion_date', $fecha)
            ->get(['hora_inicio', 'hora_final']);

        return response()->json($reservas);
    }
    
    public function misReservaciones()
{
    $userId = auth()->id();

    // Reservaciones solo del jugador autenticado
    $reservaciones = Reservacion::with(['cancha', 'tipoReservacion'])
        ->where('user_id', $userId)
        ->orderBy('reservacion_date', 'desc')
        ->get();

    return view('jugador.reservaciones.index', compact('reservaciones'));
}
public function prepararReservacion(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora' => 'required',
        'cancha_id' => 'required|exists:canchas,id',
        'id_tipo_reservacion' => 'required|integer',
    ]);

    $fechaReserva = Carbon::parse($request->fecha)->format('Y-m-d');
    $horaInicio = Carbon::parse($request->hora)->format('H:i:s');

    $duracion = TipoReservacion::find($request->id_tipo_reservacion);

    $contenido_duracion = (int) $duracion->franja_horaria;
    $horaFinal = Carbon::parse($horaInicio)->addHours($contenido_duracion)->format('H:i:s');

    // Armamos un "objeto" temporal
    $preReserva = (object)[
        'fecha' => $fechaReserva,
        'hora_inicio' => $horaInicio,
        'hora_final' => $horaFinal,
        'cancha' => Canchas::find($request->cancha_id),
        'tipoReservacion' => $duracion,
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
    ];

    return view('frontend.confirmarCompra', compact('preReserva'));
}


    
    /**
     * Display the specified resource.
     */
    public function show(Club $club)
    {
        //
    }


    public function edit(Club $club)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Club $club)
    {

        }
        

        


    /**
     * Remove the specified resource from storage.
     */
public function destroy(Club $club)
    {
        
    }

}
