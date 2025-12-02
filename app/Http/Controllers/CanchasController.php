<?php

namespace App\Http\Controllers;

use App\Models\Canchas;
use App\Models\Club;
use Illuminate\Http\Request;

class CanchasController extends Controller
{

    public function index()
    {
        
        $user = auth()->user();
        
        $canchas = Canchas::with('club')
        ->whereHas('club', function ($query) use ($user) {
            $query->where('id_user', $user->id); // solo clubes del gestor
        })
        ->get();
        return view('admin.canchas.index', compact('canchas'));

    }

    
    public function create()
    {    
        $user = auth()->user();

        $clubes = Club::where('id_user', $user->id)->get();
        return view('admin.canchas.create', compact('clubes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'id_club' => 'required|exists:clubs,id',
            'duracion_maxima' => 'required|string|max:255',

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
        $user = auth()->user();

        $clubes = Club::where('id_user', $user->id)->get();
        return view('admin.canchas.edit', compact('cancha','clubes'));
    }

    
    public function update(Request $request, Canchas $cancha)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'required|string|max:255',
            'id_club' => 'required|exists:clubs,id',
            'duracion_maxima' => 'required|string|max:255',
        ]);

        $cancha->update($request->all());

        return redirect()->route('canchas.index')->with('success', 'Cancha actualizada.');

    }

    public function destroy(Canchas $cancha)
    {
        $cancha->delete();
        return redirect()->route('canchas.index')->with('success', 'Cancha eliminada.');

    }
public function activar($id)
{
    $cancha = Canchas::findOrFail($id);
    $cancha->is_active = true;
    $cancha->save();

    return back()->with('success', 'La cancha fue publicada.');
}

public function desactivar($id)
{
    $cancha = Canchas::findOrFail($id);
    $cancha->is_active = false;
    $cancha->save();

    return back()->with('success', 'La cancha fue despublicada.');
}


}
