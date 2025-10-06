<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        // Día actual o el que venga por query
        $fecha = $request->query('fecha', Carbon::today()->toDateString());

        // Generar horas desde 5am hasta 2am del siguiente día
        $inicio = Carbon::createFromTime(5, 0);  // 5:00 AM
        $fin = Carbon::createFromTime(2, 0)->addDay(); // 2:00 AM del siguiente día

        $horas = [];
        for ($hora = $inicio->copy(); $hora->lte($fin); $hora->addHour()) {
            $horas[] = $hora->format('H:i');
        }

        return view('welcome', compact('fecha', 'horas'));
    }
}
