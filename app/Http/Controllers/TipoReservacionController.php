<?php

namespace App\Http\Controllers;

use App\Models\TipoReservacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TipoReservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiporeservacion = TipoReservacion::all();
        return view('admin.tiporeservacion.index', compact('tiporeservacion'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tiporeservacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 
    public function store(Request $request)
    {
        $request->validate([
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
        ]);
    
        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);
    
        // Si la hora final es igual o menor, asumimos que cruza a la medianoche
        if ($horaFin->lessThanOrEqualTo($horaInicio)) {
            $horaFin->addDay();
    
            // Límite hasta las 03:00 AM del día siguiente
            $horaLimite = $horaInicio->copy()->addDay()->setTime(3, 0);
            if ($horaFin->greaterThan($horaLimite)) {
                return back()
                    ->withErrors(['hora_fin' => 'La hora final no puede superar las 03:00 AM del día siguiente.'])
                    ->withInput();
            }
        }
    
        // Validar duración mínima
        if ($horaFin->lessThanOrEqualTo($horaInicio)) {
            return back()
                ->withErrors(['hora_fin' => 'La hora final debe ser posterior a la hora de inicio.'])
                ->withInput();
        }
    
        TipoReservacion::create([
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'precio' => $request->precio,
        ]);
    
        return redirect()->route('tiporeservacion.index')
            ->with('success', 'Tipo de Reservación creada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(TipoReservacion $tipoReservacion)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoReservacion $tiporeservacion)
    {
        return view('admin.tiporeservacion.edit', compact('tiporeservacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoReservacion $tiporeservacion)
    {
        $request->validate([
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
        ]);

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);

        if ($horaFin->lessThanOrEqualTo($horaInicio)) {
            $horaFin->addDay();

            $horaLimite = $horaInicio->copy()->addDay()->setTime(3, 0);
            if ($horaFin->greaterThan($horaLimite)) {
                return back()
                    ->withErrors(['hora_fin' => 'La hora final no puede superar las 03:00 AM del día siguiente.'])
                    ->withInput();
            }
        }

        if ($horaFin->lessThanOrEqualTo($horaInicio)) {
            return back()
                ->withErrors(['hora_fin' => 'La hora final debe ser posterior a la hora de inicio.'])
                ->withInput();
        }

        $tiporeservacion->update([
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'precio' => $request->precio,
        ]);

        return redirect()->route('tiporeservacion.index')
            ->with('success', 'Tipo de Reservación actualizada.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoReservacion $tiporeservacion)
    {
        $tiporeservacion->delete();
        return redirect()->route('tiporeservacion.index')->with('success', 'Tipo de Reservacion eliminada.');

    }
}
