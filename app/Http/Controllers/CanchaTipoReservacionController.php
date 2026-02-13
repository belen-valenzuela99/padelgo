<?php

namespace App\Http\Controllers;

use App\Models\Canchas;
use App\Models\TipoReservacion;
use Illuminate\Http\Request;

class CanchaTipoReservacionController extends Controller
{
    public function edit($canchaId)
        {
            $cancha = Canchas::with('tiposReservacion')
                ->findOrFail($canchaId);

            $tipos = $cancha->tiposReservacion()
                ->orderBy('hora_inicio')
                ->get();

            return view('admin.canchas.asignarTipos', compact('cancha', 'tipos'));
        }


    public function update(Request $request, $canchaId)
{
    $cancha = Canchas::findOrFail($canchaId);

    $tipos = $cancha->tiposReservacion;

    // Validación de solapamiento SOLO de activos
    $activos = collect();

    foreach ($tipos as $tipo) {
        $info = $request->input("tipos.{$tipo->id}");

        if (isset($info['activo'])) {
            $activos->push([
                'id' => $tipo->id,
                'hora_inicio' => $tipo->hora_inicio,
                'hora_fin' => $tipo->hora_fin,
            ]);
        }
    }

    $errores = [];

    for ($i = 0; $i < $activos->count(); $i++) {
        for ($j = $i + 1; $j < $activos->count(); $j++) {

            $aInicio = \Carbon\Carbon::createFromFormat('H:i:s', $activos[$i]['hora_inicio']);
            $aFin    = \Carbon\Carbon::createFromFormat('H:i:s', $activos[$i]['hora_fin']);
            $bInicio = \Carbon\Carbon::createFromFormat('H:i:s', $activos[$j]['hora_inicio']);
            $bFin    = \Carbon\Carbon::createFromFormat('H:i:s', $activos[$j]['hora_fin']);

            if ($aFin->lessThanOrEqualTo($aInicio)) $aFin->addDay();
            if ($bFin->lessThanOrEqualTo($bInicio)) $bFin->addDay();

            if ($aInicio->lt($bFin) && $bInicio->lt($aFin)) {
                $errores[] = "Las franjas {$activos[$i]['hora_inicio']} - {$activos[$i]['hora_fin']} y {$activos[$j]['hora_inicio']} - {$activos[$j]['hora_fin']} se superponen.";
            }
        }
    }

    if (!empty($errores)) {
        return back()->withErrors($errores);
    }

    // Guardar cambios
    foreach ($tipos as $tipo) {

        $info = $request->input("tipos.{$tipo->id}");

        $tipo->update([
            'precio' => $info['precio'] ?? $tipo->precio,
            'activo' => isset($info['activo']) ? 1 : 0,
        ]);
    }

    return back()->with('success', 'Horarios actualizados correctamente.');
}


   public function crearHorario(Request $request, $canchaId)
{
    $request->validate([
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
        'precio' => 'required|numeric|min:0'
    ]);

    // Validar solapamiento
    $existe = TipoReservacion::where('cancha_id', $canchaId)
        ->where(function ($query) use ($request) {
            $query->where('hora_inicio', '<', $request->hora_fin)
                  ->where('hora_fin', '>', $request->hora_inicio);
        })
        ->exists();

    if ($existe) {
        return response()->json([
            'success' => false,
            'message' => 'El horario se superpone con otro existente.'
        ], 400);
    }

    $tipo = TipoReservacion::create([
        'cancha_id' => $canchaId,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'precio' => $request->precio,
        'activo' => 0
    ]);

    return response()->json([
        'success' => true,
        'tipo' => $tipo
    ]);
}

public function destroyHorario($canchaId, $tipoId)
{
    $tipo = TipoReservacion::where('cancha_id', $canchaId)
        ->where('id', $tipoId)
        ->first();

    if (!$tipo) {
        return response()->json([
            'success' => false,
            'message' => 'Horario no encontrado.'
        ], 404);
    }

    $tipo->delete();

    return response()->json([
        'success' => true
    ]);
}


}
