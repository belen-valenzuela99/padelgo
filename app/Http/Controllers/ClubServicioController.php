<?php

namespace App\Http\Controllers;

use App\Models\ClubServicio;
use Illuminate\Http\Request;

class ClubServicioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|exists:clubs,id',
            'servicios' => 'array',
            'servicios.*' => 'string|max:255',
        ]);

        $idClub = $request->id_club;
        $serviciosSeleccionados = $request->servicios ?? [];

        /*
        |--------------------------------------------------------------------------
        | 1. Eliminar servicios que ya no estén seleccionados
        |--------------------------------------------------------------------------
        */
        ClubServicio::where('id_club', $idClub)
            ->whereNotIn('nombre_servicio', $serviciosSeleccionados)
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | 2. Insertar los nuevos servicios seleccionados
        |--------------------------------------------------------------------------
        */
        foreach ($serviciosSeleccionados as $servicio) {
            ClubServicio::firstOrCreate([
                'id_club' => $idClub,
                'nombre_servicio' => $servicio,
            ]);
        }

        return back()->with('success', 'Servicios del club actualizados correctamente');
    }
}
