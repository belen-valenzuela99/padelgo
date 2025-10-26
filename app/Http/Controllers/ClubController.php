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
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);        

        $data = $request->all();

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/clubs'), $filename);
            $data['img'] = 'img/clubs/' . $filename; // ruta relativa desde public
        }
        

        Club::create($data);

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
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);
        

        $data = $request->all();

        if ($request->hasFile('img')) {
            // Borrar la imagen anterior si existe
            if ($club->img && file_exists(public_path($club->img))) {
                unlink(public_path($club->img));
            }
        
            // Guardar la nueva imagen en public/img/clubs
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/clubs'), $filename);
            $data['img'] = 'img/clubs/' . $filename; // ruta relativa desde public
        }
        

        $club->update($data);

        return redirect()->route('clubes.index')->with('success', 'Club actualizado.');
    }


    /**
     * Remove the specified resource from storage.
     */
public function destroy(Club $club)
    {
        // Borrar la imagen si existe
        if ($club->img && file_exists(public_path($club->img))) {
            unlink(public_path($club->img));
        }

        // Borrar el registro de la base de datos
        $club->delete();

        return redirect()->route('clubes.index')->with('success', 'Club eliminado correctamente.');
    }

}
