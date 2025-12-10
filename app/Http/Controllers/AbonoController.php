<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Reservacion;
use App\Models\Canchas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbonoController extends Controller
{
    /**
     * LISTADO DE ABONOS
     */
    public function index()
    {
        $abonos = Abono::with(['usuario', 'cancha'])->get();
       return view('admin.abonos.index', compact('abonos'));

    }


    /**
     * FORMULARIO PARA CREAR
     */
    public function create()
    {
        $user = auth()->user();

        $canchas = Canchas::whereHas('club', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })
        ->get();

        $usuarios = \App\Models\User::where('role', 2)->get();
    

        return view('admin.abonos.create', compact('canchas', 'usuarios'));

    }

    public function edit(Abono $abono)
{ $user = auth()->user();

    $canchas = Canchas::whereHas('club', function ($query) use ($user) {
    $query->where('id_user', $user->id); 
        })
        ->get();
    
    $usuarios = \App\Models\User::where('role', 2)->get();

    return view('admin.abonos.edit', compact('abono', 'canchas', 'usuarios'));
}


public function update(Request $request, Abono $abono)
{
    $request->validate([
        'user_id'      => 'required|exists:users,id',
        'cancha_id'    => 'required|exists:canchas,id',
        'dia_semana'   => 'required|string',
        'mes'          => 'required|integer|min:1|max:12',
        'hora_inicio'  => 'required',
        'hora_fin'     => 'required',
        'precio'       => 'required|numeric|min:0',
    ]);

    // Actualizamos el abono
    $abono->update([
        'user_id'     => $request->user_id,
        'cancha_id'   => $request->cancha_id,
        'dia_semana'  => $request->dia_semana,
        'mes'         => $request->mes,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin'    => $request->hora_fin,
        'precio'      => $request->precio,
    ]);

    // Eliminamos reservas anteriores
    Reservacion::where('abono_id', $abono->id)->delete();

    // Volvemos a generarlas
    $this->generarReservasDelMes($abono);

    return redirect()->route('abonos.index')
        ->with('success', 'Abono actualizado correctamente.');
}



    /**
     * GUARDAR ABONO + CREAR RESERVACIONES
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'cancha_id'    => 'required|exists:canchas,id',
            'dia_semana'   => 'required|string',
            'mes'          => 'required|integer|min:1|max:12',
            'hora_inicio'  => 'required',
            'hora_fin'     => 'required',
            'precio'       => 'required|numeric|min:0',
        ]);

        // Crear el abono
        $abono = Abono::create([
            'user_id'     => $request->user_id,
            'cancha_id'   => $request->cancha_id,
            'dia_semana'  => $request->dia_semana,
            'mes'         => $request->mes,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin'    => $request->hora_fin,
            'precio'      => $request->precio,
            'activo'      => true,
        ]);

        // Generar las reservaciones automáticas del mes
        $this->generarReservasDelMes($abono);

        return redirect()->route('abonos.index')
            ->with('success', 'Abono creado y reservas generadas correctamente.');
    }


    /**
     * GENERAR TODAS LAS RESERVAS DEL MES PARA ESTE ABONO
     */
    private function generarReservasDelMes(Abono $abono)
    {
        $mesElegido = $abono->mes;
        $anioActual = now()->year;
        $mesActual  = now()->month;

        // Si el mes elegido ya pasó, generar para el año siguiente
        $anio = ($mesElegido < $mesActual) ? $anioActual + 1 : $anioActual;
        $mes  = $mesElegido;

        // Convertir día a número (Carbon: 0 = domingo, 1 = lunes...)
        $dias = [
            'lunes'     => 1,
            'martes'    => 2,
            'miércoles' => 3,
            'jueves'    => 4,
            'viernes'   => 5,
            'sábado'    => 6,
            'domingo'   => 0,
        ];

        $diaNumero = $dias[strtolower($abono->dia_semana)];

        // Primera fecha del mes/año elegido
        $fecha = Carbon::create($anio, $mes, 1)->startOfMonth();

        // Buscar el primer día específico del mes
        while ($fecha->dayOfWeek !== $diaNumero) {
            $fecha->addDay();
        }

        // Crear reservas semanales
        while ($fecha->month == $mes) {

            // Verificar superposición
            $conflicto = Reservacion::where('cancha_id', $abono->cancha_id)
                ->where('reservacion_date', $fecha->toDateString())
                ->where(function ($q) use ($abono) {
                    $q->whereBetween('hora_inicio', [$abono->hora_inicio, $abono->hora_fin])
                    ->orWhereBetween('hora_final', [$abono->hora_inicio, $abono->hora_fin]);
                })
                ->exists();

            if (!$conflicto) {
                Reservacion::create([
                    'user_id'           => $abono->user_id,
                    'cancha_id'         => $abono->cancha_id,
                    'reservacion_date'  => $fecha->toDateString(),
                    'hora_inicio'       => $abono->hora_inicio,
                    'hora_final'        => $abono->hora_fin,
                    'id_tipo_reservacion' => null,
                    'precio'            => $abono->precio,
                    'status'            => 'programado',
                    'abono_id'          => $abono->id,
                ]);
            }

            $fecha->addWeek();
        }
    }



    /**
     * ELIMINAR ABONO + RESERVAS
     */
    public function destroy(Abono $abono)
    {
        $abono->delete(); // gracias a ON DELETE CASCADE las reservas se borran solas

        return redirect()->back()->with('success', 'Abono eliminado correctamente.');
    }
}
