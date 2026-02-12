<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JugadorDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */
        $totalReservas = Reservacion::where('user_id', $userId)->count();

        $reservasActivas = Reservacion::where('user_id', $userId)
            ->where('status', 'programado')
            ->count();

        $turnosCompletados = Reservacion::where('user_id', $userId)
            ->where('status', 'turno completado')
            ->count();

        $dineroGastado = Reservacion::where('user_id', $userId)
            ->whereIn('status', ['programado', 'turno completado'])
            ->sum('precio');

        /*
        |--------------------------------------------------------------------------
        | TABLAS
        |--------------------------------------------------------------------------
        */

        // Historial de reservas
        $historialReservas = Reservacion::with(['cancha.club', 'tipoReservacion'])
            ->where('user_id', $userId)
            ->orderByDesc('reservacion_date')
            ->get();

        // Próximas reservas
        $proximasReservas = Reservacion::with(['cancha.club'])
            ->where('user_id', $userId)
            ->where('status', 'programado')
            ->whereDate('reservacion_date', '>=', Carbon::today())
            ->orderBy('reservacion_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GRÁFICOS
        |--------------------------------------------------------------------------
        */

        // Gastos por mes
        $gastosPorMes = Reservacion::selectRaw(
                'YEAR(reservacion_date) as anio,
                 MONTH(reservacion_date) as mes,
                 SUM(precio) as total'
            )
            ->where('user_id', $userId)
            ->whereIn('status', ['programado', 'turno completado'])
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        // Reservas por club
        $reservasPorClub = Reservacion::selectRaw(
                'clubs.nombre as club, COUNT(reservacions.id) as total'
            )
            ->join('canchas', 'reservacions.cancha_id', '=', 'canchas.id')
            ->join('clubs', 'canchas.id_club', '=', 'clubs.id')
            ->where('reservacions.user_id', $userId)
            ->groupBy('clubs.nombre')
            ->get();

        return view('jugador.dashboard', compact(
            'totalReservas',
            'reservasActivas',
            'turnosCompletados',
            'dineroGastado',
            'historialReservas',
            'proximasReservas',
            'gastosPorMes',
            'reservasPorClub'
        ));
    }
}
