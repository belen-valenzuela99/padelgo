<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                $clubes = Club::all();
        return view('admin.clubes.index', compact('clubes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clubes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string',
        ]);

        Club::create($request->all());
        return redirect()->route('clubes.index')->with('success', 'Club creado correctamente.');
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
        return view('admin.clubes.edit', compact('club'));
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, Club $club)
{
    $request->validate([
        'nombre' => 'required|unique:clubs,nombre,' . $club->id . '|max:255',
        'direccion' => 'nullable',
    ]);

    $club->update($request->all());

    return redirect()->route('clubes.index')->with('success', 'Club actualizado.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('clubes.index')->with('success', 'Club eliminado.');

    }
}
