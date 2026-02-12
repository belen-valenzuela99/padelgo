<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Club;
use App\Models\Canchas;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | KPIs (CARDS)
        |--------------------------------------------------------------------------
        */
        $totalClubes   = Club::count();
        $totalUsuarios = User::count();

        $usuariosPorRol = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalCanchas = Canchas::where('is_active', true)->count();

        /*
        |--------------------------------------------------------------------------
        | GRAFICOS
        |--------------------------------------------------------------------------
        */

        // Usuarios por rol
        $rolesLabels = $usuariosPorRol->keys();
        $rolesData   = $usuariosPorRol->values();

        // Clubes con más canchas
        $clubesConCanchas = Club::withCount('canchas')
            ->orderByDesc('canchas_count')
            ->limit(5)
            ->get();

        // Usuarios por mes
        $usuariosPorMes = User::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TABLAS
        |--------------------------------------------------------------------------
        */

        $ultimosClubes = Club::with('gestor')
            ->latest()
            ->limit(5)
            ->get();

        $ultimosUsuarios = User::latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ALERTAS
        |--------------------------------------------------------------------------
        */

        $clubesSinGestor = Club::whereNull('id_user')->count();
        $gestoresSinClub = User::where('role', 'gestor')
                            ->whereDoesntHave('clubs')
                            ->count();


        return view('admin.dashboard', compact(
            'totalClubes',
            'totalUsuarios',
            'usuariosPorRol',
            'totalCanchas',
            'rolesLabels',
            'rolesData',
            'clubesConCanchas',
            'usuariosPorMes',
            'ultimosClubes',
            'ultimosUsuarios',
            'clubesSinGestor',
            'gestoresSinClub'
        ));
    }
}
