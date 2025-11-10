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
    public function confirmacionReserva($id)

    {
        $user = auth()->user();
        $cancha = Canchas::findOrFail($id);
        $tipos = TipoReservacion::all();
        return view('frontend.confirmacionReserva', compact('user', 'cancha', 'tipos'));

        }
        
    
    public function registrarReservacion(Request $request)
{
        $user = auth()->user();
        //dd($user);
   // if (!$user || $user->role !== "jugador") {
            //return redirect('/login')->with('error', 'Solo los clientes pueden comprar entradas.');
        //}


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

    $reservacion = Reservacion::create([
        'user_id' => $user->id,
        'cancha_id' => $request->cancha_id,
        'id_tipo_reservacion' => $request->id_tipo_reservacion,
        'reservacion_date' => $fechaReserva,
        'hora_inicio' => $horaInicio,
        'hora_final' => $horaFinal,
        'status' => $request->status ?? 'programado',
    ]);
    $reservacion->load(['user', 'cancha', 'tipoReservacion']);
    return view('frontend.reservaTicket', compact('reservacion'))->with('success', 'Reservación creada correctamente.');
}
 public function horasOcupadas($canchaId,  $fecha){
        $reservas = Reservacion::where('cancha_id', $canchaId)
            ->whereDate('reservacion_date', $fecha)
            ->get(['hora_inicio', 'hora_final']);

        return response()->json($reservas);
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
