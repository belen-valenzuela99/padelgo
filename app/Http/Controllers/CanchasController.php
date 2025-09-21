<?php

namespace App\Http\Controllers;

use App\Models\Canchas;
use Illuminate\Http\Request;

class CanchasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $canchas = Canchas::all();
        return view('admin.canchas.index', compact('canchas'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.canchas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string',
        ]);

        Canchas::create($request->all());
        return redirect()->route('canchas.index')->with('success', 'Cancha creada correctamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Canchas $canchas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Canchas $cancha)
    {
                return view('admin.canchas.edit', compact('cancha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Canchas $cancha)
    {
        $request->validate([
            'nombre' => 'required|unique:canchas,nombre,'.$cancha->id.'|max:255',
            'ubicacion' => 'nullable',
        ]);

        $cancha->update($request->all());

        return redirect()->route('canchas.index')->with('success', 'Cancha actualizada.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Canchas $cancha)
    {
        $cancha->delete();
        return redirect()->route('canchas.index')->with('success', 'Cancha eliminada.');

    }
}
