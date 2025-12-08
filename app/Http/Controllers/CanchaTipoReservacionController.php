<?php

namespace App\Http\Controllers;

use App\Models\Canchas;
use App\Models\TipoReservacion;
use Illuminate\Http\Request;

class CanchaTipoReservacionController extends Controller
{
    public function edit($canchaId)
    {
        $cancha = Canchas::findOrFail($canchaId);
        $tipos = TipoReservacion::orderBy('hora_inicio')->get();

        // Relación actual (para marcar los seleccionados)
        $tiposAsignados = $cancha->tiposReservacion->pluck('pivot')->mapWithKeys(fn($pivot) => [
            $pivot->tipo_reservacion_id => [
                'precio' => $pivot->precio,
                'activo' => $pivot->activo,
            ]
        ])->toArray();

        return view('admin.canchas.asignarTipos', compact('cancha', 'tipos', 'tiposAsignados'));
    }

    public function update(Request $request, $canchaId)
    {
        $cancha = Canchas::findOrFail($canchaId);
        $tipos = TipoReservacion::all();

        // --- 1. OBTENER SOLO LOS TIPOS ACTIVADOS EN EL FORMULARIO ---
        $tiposSeleccionados = collect();

        foreach ($tipos as $tipo) {
            $info = $request->input("tipos.{$tipo->id}", null);

            if (isset($info['activo'])) {
                $tiposSeleccionados->push([
                    'id' => $tipo->id,
                    'hora_inicio' => $tipo->hora_inicio,
                    'hora_fin' => $tipo->hora_fin,
                ]);
            }
        }

        // --- 2. VALIDAR QUE NO SE SOLAPEN LOS HORARIOS ---
        $errores = [];

        for ($i = 0; $i < $tiposSeleccionados->count(); $i++) {
            for ($j = $i + 1; $j < $tiposSeleccionados->count(); $j++) {
                $a = $tiposSeleccionados[$i];
                $b = $tiposSeleccionados[$j];

                // Convertir a Carbon para comparar horas
                $aInicio = \Carbon\Carbon::createFromFormat('H:i:s', $a['hora_inicio']);
                $aFin = \Carbon\Carbon::createFromFormat('H:i:s', $a['hora_fin']);
                $bInicio = \Carbon\Carbon::createFromFormat('H:i:s', $b['hora_inicio']);
                $bFin = \Carbon\Carbon::createFromFormat('H:i:s', $b['hora_fin']);

                // Si alguna franja pasa de medianoche, ajustamos la hora final (+1 día)
                if ($aFin->lessThanOrEqualTo($aInicio)) $aFin->addDay();
                if ($bFin->lessThanOrEqualTo($bInicio)) $bFin->addDay();

                // Detectar solapamiento
                $seSolapan = $aInicio->lt($bFin) && $bInicio->lt($aFin);

                if ($seSolapan) {
                    $errores[] = "Las franjas horarias {$a['hora_inicio']} - {$a['hora_fin']} y {$b['hora_inicio']} - {$b['hora_fin']} se superponen.";
                }
            }
        }

        if (!empty($errores)) {
            return back()->withErrors($errores)->withInput();
        }

        // --- 3. SI TODO ESTÁ BIEN, GUARDAMOS ---
        foreach ($tipos as $tipo) {
            $info = $request->input("tipos.{$tipo->id}", null);

            $cancha->tiposReservacion()->syncWithoutDetaching([
                $tipo->id => [
                    'precio' => $info['precio'] ?? $tipo->precio,
                    'activo' => isset($info['activo']) ? 1 : 0,
                ]
            ]);
        }

        return redirect()->route('canchas.index')->with('success', 'Tipos de reservación asignados correctamente.');
    }



}
