<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Canchas;
use App\Models\Club;
use App\Models\Abono;



class ReportesController extends Controller
{

public function reservasPorMes()
{
    $reservasPorMes = Reservacion::select(
            DB::raw('MONTH(reservacion_date) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('status', '!=', 'cancelado')
        ->groupBy('mes')
        ->orderBy('mes')
        ->get()
        ->map(function ($item) {
            $item->mes_nombre = Carbon::create()->month($item->mes)->translatedFormat('F');
            return $item;
        });

    return view('admin.reportes.reservas_por_mes', compact('reservasPorMes'));
}

public function canchasMasReservadas()
{
    $canchas = Canchas::withCount(['reservaciones' => function ($q) {
            $q->where('status', '!=', 'cancelado');
        }])
        ->orderByDesc('reservaciones_count')
        ->take(10)
        ->get();

    return view('admin.reportes.canchas_mas_reservadas', compact('canchas'));
}


public function ingresosTotales()
{
    $ingresos = Reservacion::whereIn('status', ['programado', 'turno completado'])
        ->sum('precio');

    return view('admin.reportes.ingresos_totales', compact('ingresos'));
}

 public function dashboard()
    {
        $userId = auth()->id();

        /**
         * =========================
         * MÉTRICAS PRINCIPALES (CARDS)
         * =========================
         */

        // Total de reservas del gestor
        $totalReservas = Reservacion::whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->count();

        // Ingresos totales (solo reservas válidas)
        $ingresosTotales = Reservacion::whereIn('status', [
                'programado',
                'turno completado'
            ])
            ->whereHas('cancha.club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->sum('precio');

        // Canchas activas del gestor
        $canchasActivas = Canchas::where('is_active', true)
            ->whereHas('club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->count();

        // Total de clubes del gestor
        $totalClubes = Club::where('id_user', $userId)->count();

        /**
         * =========================
         * DATASETS PARA GRÁFICOS
         * =========================
         */

        // Reservas por mes
        $reservasPorMes = Reservacion::selectRaw(
                'MONTH(reservacion_date) as mes, COUNT(*) as total'
            )
            ->whereHas('cancha.club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Ingresos por mes
        $ingresosPorMes = Reservacion::selectRaw(
                'MONTH(reservacion_date) as mes, SUM(precio) as total'
            )
            ->whereIn('status', ['programado', 'turno completado'])
            ->whereHas('cancha.club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Canchas más reservadas
        $canchasMasReservadas = Reservacion::selectRaw(
                'canchas.nombre, COUNT(reservacions.id) as total'
            )
            ->join('canchas', 'reservacions.cancha_id', '=', 'canchas.id')
            ->join('clubs', 'canchas.id_club', '=', 'clubs.id')
            ->where('clubs.id_user', $userId)
            ->groupBy('canchas.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /**
         * =========================
         * ÚLTIMAS RESERVAS
         * =========================
         */
        $ultimasReservas = Reservacion::with('cancha.club', 'user')
            ->whereHas('cancha.club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Reservas pendientes (turnos no completados)
        $reservasPendientes = Reservacion::with([
                'user',
                'cancha.club'
            ])
            ->whereIn('status', ['programado', 'turno perdido'])
            ->whereHas('cancha.club', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->orderBy('reservacion_date')
            ->orderBy('hora_inicio')
            ->get();

        $ingresosPorMes = Reservacion::selectRaw(
        'MONTH(reservacion_date) as mes, SUM(precio) as total'
        )
        ->whereIn('status', ['programado', 'turno completado'])
        ->whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->groupBy('mes')
        ->orderBy('mes')
        ->pluck('total', 'mes');

        $clubs = Club::where('id_user', $userId)->get();

        $canchas = Canchas::whereHas('club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->get();
    
        // Total de abonos activos del gestor
        $totalAbonosActivos = Abono::where('activo', true)
        ->whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->count();

        $ingresosAbonos = Abono::where('activo', true)
        ->whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->sum('precio');

        $abonosPorMes = Abono::selectRaw('mes, COUNT(*) as total')
        ->whereHas('cancha.club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })
        ->groupBy('mes')
        ->orderBy('mes')
        ->pluck('total', 'mes');


        return view('gestor.dashboard', compact(
            'totalReservas',
            'ingresosTotales',
            'canchasActivas',
            'totalClubes',
            'reservasPorMes',
            'ingresosPorMes',
            'canchasMasReservadas',
            'ultimasReservas',
            'reservasPendientes',
            'ingresosPorMes',
            'clubs',
            'canchas',
            'totalAbonosActivos',
            'ingresosAbonos',
            'abonosPorMes'
        ));

}

public function index()
    {
        $userId = auth()->id();

        $clubs = Club::where('id_user', $userId)->get();
        $canchas = Canchas::whereHas('club', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->get();

        return view('gestor.reportes', compact('clubs', 'canchas'));
    }

public function generar(Request $request) 
{
    $tipos = $request->input('tipos', []);

    $clubId   = $request->club_id;
    $canchaId = $request->cancha_id;

    $fechaInicio = $request->fecha_inicio;
    $fechaFin    = $request->fecha_fin;

    $userId = auth()->id(); // gestor logueado

    // Obtener meses seleccionados (1–12) desde las fechas
    $inicio = \Carbon\Carbon::parse($fechaInicio)->startOfMonth();
    $fin    = \Carbon\Carbon::parse($fechaFin)->startOfMonth();

    $mesesSeleccionados = collect();

    while ($inicio <= $fin) {
        $mesesSeleccionados->push($inicio->month);
        $inicio->addMonth();
    }

    $mesesSeleccionados = $mesesSeleccionados->unique()->values();

    $data = [];

    /*
    |--------------------------------------------------------------------------
    | INGRESOS
    |--------------------------------------------------------------------------
    */
    if (in_array('ingresos', $tipos)) {

        $ingresos = Canchas::query()

            ->join('clubs', 'canchas.id_club', '=', 'clubs.id')
            ->where('clubs.id_user', $userId) // 🔒 SOLO clubes del gestor

            ->when($clubId !== 'all', fn($q) =>
                $q->where('canchas.id_club', $clubId)
            )

            ->when($canchaId !== 'all', fn($q) =>
                $q->where('canchas.id', $canchaId)
            )

            ->leftJoin('reservacions', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('canchas.id', '=', 'reservacions.cancha_id')
                     ->whereIn('reservacions.status', ['programado', 'turno completado'])
                     ->whereBetween('reservacions.reservacion_date', [$fechaInicio, $fechaFin]);
            })

            ->selectRaw('
                clubs.nombre as club,
                canchas.nombre as cancha,
                COALESCE(SUM(reservacions.precio), 0) as total
            ')
            ->groupBy('clubs.nombre', 'canchas.nombre')
            ->orderBy('clubs.nombre')
            ->get();

        $data['ingresos'] = [
            'filas' => $ingresos,
            'total_general' => $ingresos->sum('total')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RESERVAS
    |--------------------------------------------------------------------------
    */
    if (in_array('reservas', $tipos)) {

        $reservas = Canchas::query()

            ->join('clubs', 'canchas.id_club', '=', 'clubs.id')
            ->where('clubs.id_user', $userId) // 🔒 SOLO clubes del gestor

            ->when($clubId !== 'all', fn($q) =>
                $q->where('canchas.id_club', $clubId)
            )

            ->when($canchaId !== 'all', fn($q) =>
                $q->where('canchas.id', $canchaId)
            )

            ->leftJoin('reservacions', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('canchas.id', '=', 'reservacions.cancha_id')
                     ->whereBetween('reservacions.reservacion_date', [$fechaInicio, $fechaFin]);
            })

            ->selectRaw('
                clubs.nombre as club,
                canchas.nombre as cancha,
                COUNT(reservacions.id) as total
            ')
            ->groupBy('clubs.nombre', 'canchas.nombre')
            ->orderBy('clubs.nombre')
            ->get();

        $data['reservas'] = [
            'filas' => $reservas,
            'total_general' => $reservas->sum('total')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ABONOS (🔥 FILTRADO POR abonos.mes + activos + gestor)
    |--------------------------------------------------------------------------
    */
    if (in_array('abonos', $tipos)) {

        $abonos = Canchas::query()

            ->join('clubs', 'canchas.id_club', '=', 'clubs.id')
            ->where('clubs.id_user', $userId) // 🔒 SOLO clubes del gestor

            ->when($clubId !== 'all', fn($q) =>
                $q->where('canchas.id_club', $clubId)
            )

            ->when($canchaId !== 'all', fn($q) =>
                $q->where('canchas.id', $canchaId)
            )

            ->leftJoin('abonos', function ($join) use ($mesesSeleccionados) {
                $join->on('canchas.id', '=', 'abonos.cancha_id')
                     ->where('abonos.activo', 1);
            
                if ($mesesSeleccionados->isNotEmpty()) {
                    $join->whereIn('abonos.mes', $mesesSeleccionados);
                }
            })

            ->selectRaw('
                clubs.nombre as club,
                canchas.nombre as cancha,
                COUNT(abonos.id) as total
            ')
            ->groupBy('clubs.nombre', 'canchas.nombre')
            ->orderBy('clubs.nombre')
            ->get();

        $data['abonos'] = [
            'filas' => $abonos,
            'total_general' => $abonos->sum('total')
        ];
    }

    return view('gestor.resultados', compact('data'));
}

}
