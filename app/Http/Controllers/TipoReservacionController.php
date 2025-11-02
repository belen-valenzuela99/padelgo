<?php

namespace App\Http\Controllers;

use App\Models\TipoReservacion;
use Illuminate\Http\Request;

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
            'franja_horaria' => 'required|string|max:255',
            'precio' => 'nullable|string',
        ]);

        TipoReservacion::create($request->all());
        return redirect()->route('tiporeservacion.index')->with('success', 'Tipo de Reservacion creada correctamente.');

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
            'franja_horaria' => 'string|max:255',
            'precio' => 'nullable|integer',
        ]);

        $tiporeservacion->update($request->all());

        return redirect()->route('tiporeservacion.index')->with('success', 'Tipo de Reservacion actualizada.');

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
