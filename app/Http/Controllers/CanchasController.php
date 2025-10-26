<?php

namespace App\Http\Controllers;

use App\Models\Canchas;
use App\Models\Club;
use Illuminate\Http\Request;

class CanchasController extends Controller
{

    public function index()
    {
        
        $canchas = Canchas::with('club')->get();
        return view('admin.canchas.index', compact('canchas'));

    }

    
    public function create()
    {    
        $clubes=Club::all();
        return view('admin.canchas.create', compact('clubes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_club' => 'required|exists:clubs,id',

        ]);

        Canchas::create($request->all());
        return redirect()->route('canchas.index')->with('success', 'Cancha creada correctamente.');

    }

    public function show(Canchas $canchas)
    {
        //
    }


    public function edit(Canchas $cancha)

    {   
        $clubes=Club::all();
        return view('admin.canchas.edit', compact('cancha','clubes'));
    }

    
    public function update(Request $request, Canchas $cancha)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'id_club' => 'required|exists:clubs,id',
        ]);

        $cancha->update($request->all());

        return redirect()->route('canchas.index')->with('success', 'Cancha actualizada.');

    }

    public function destroy(Canchas $cancha)
    {
        $cancha->delete();
        return redirect()->route('canchas.index')->with('success', 'Cancha eliminada.');

    }
}
