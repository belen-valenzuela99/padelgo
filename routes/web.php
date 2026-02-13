<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CanchasController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\TipoReservacionController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\CanchaTipoReservacionController;
use App\Http\Controllers\Admin\UserAdminController; 
use App\Http\Controllers\AbonoController;    
use App\Http\Controllers\RedSocialController;
use App\Http\Controllers\ClubRedSocialController;
use App\Http\Controllers\ClubServicioController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JugadorDashboardController;
use App\Models\Canchas;



Route::get('/reservaPrueba', [ReservaController::class, 'index'])
     ->name('reserva.index');

// Redirección dinámica según el rol del usuario
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    elseif ($user->role === 'gestor') {
        return redirect()->route('gestor.dashboard');
    }

    return redirect()->route('jugador.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================== DASHBOARD ADMIN ==================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    //Route::get('/dashboard', function () {
        //return view('admin.dashboard');
   // })->name('admin.dashboard');
    // Se agrega la ruta dentro del middleware del admin o del jugador, 
    // Route::resource('clubes', ClubController::class)->parameters(['clubes' => 'club']);
    Route::resource('redes_sociales', RedSocialController::class)
    ->except(['show'])
    ->names('redes_sociales');
    Route::resource('users', UserAdminController::class)->names('admin.users');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});


// ================== DASHBOARD JUGADOR ==================
Route::middleware(['auth', 'role:jugador'])->prefix('jugador')->group(function () {
      Route::get('/jugador/dashboard', [JugadorDashboardController::class, 'index'])
        ->name('jugador.dashboard');
    // Listado de reservas del jugador
    Route::get('/reservaciones', [FrontendController::class, 'misReservaciones'])
        ->name('jugador.reservaciones.index');
        
   // ================== ================== Route::post('/reservacion-ticket', [FrontendController::class, 'registrarReservacion'])->name('reservar.store');
    Route::get('/confirmar-compra/{reservacion}', [FrontendController::class, 'confirmarCompra'])
    ->name('jugador.reservar.confirmar');
    Route::post('/pre-reservacion', [FrontendController::class, 'prepararReservacion'])
    ->name('jugador.reservar.preparar');
    Route::post('/reservacion-confirmada', [FrontendController::class, 'registrarReservacion'])
    ->name('jugador.reservar.confirmada');



});
Route::patch('/canchas/{id}/activar', [CanchasController::class, 'activar'])
    ->name('canchas.activar');

Route::patch('/canchas/{id}/desactivar', [CanchasController::class, 'desactivar'])
    ->name('canchas.desactivar');


// ================== DASHBOARD GESTOR ==================
Route::middleware(['auth', 'role:gestor'])->prefix('gestor')->group(function () {
    Route::get('/dashboard', function () {
        return view('gestor.dashboard');
    })->name('gestor.dashboard');
    Route::resource('canchas', CanchasController::class);
    Route::resource('tiporeservacion', TipoReservacionController::class);
    Route::resource('reservacions', ReservacionController::class);
    Route::get('/canchas/{id}/tipos', [CanchaTipoReservacionController::class, 'edit'])->name('canchas.tipos.edit');
    Route::put('/canchas/{id}/tipos', [CanchaTipoReservacionController::class, 'update'])->name('canchas.tipos.update');
    Route::resource('abonos', AbonoController::class);
    Route::post('/abonos/confirmar', [AbonoController::class, 'confirmar'])->name('abonos.confirmar');
    Route::get('/', [ReportesController::class, 'dashboard'])->name('reportes.dashboard');
    Route::get('/reservas-por-mes', [ReportesController::class, 'reservasPorMes'])->name('reportes.reservas_mes');
    Route::get('/canchas-mas-reservadas', [ReportesController::class, 'canchasMasReservadas'])->name('reportes.canchas');
    Route::get('/ingresos', [ReportesController::class, 'ingresosTotales'])->name('reportes.ingresos');
    Route::get('/dashboard', [ReportesController::class, 'dashboard'])->name('gestor.dashboard');
    Route::get('/reportes', [ReportesController::class, 'index'])->middleware(['auth'])->name('reportes.index');
    Route::post('/reportes/generar', [ReportesController::class, 'generar'])->middleware(['auth'])->name('reportes.generar');





   //Route::post('club-red-social',[ClubRedSocialController::class, 'store'])->name('club_red_social.store');
    //Route::post('/club-redes/sync', [ClubRedSocialController::class, 'syncRedes'])->name('club_red_social.sync');
    



});

// ================== PERFIL ==================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::get('/', [ReservaController::class, 'index'])->name('reservas.index');

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/club/{id}', [FrontendController::class, 'clubDetalle'])->name('clubDetalle');
Route::get('/reservar/{id}', [FrontendController::class, 'confirmacionReserva'])->name('confirmacionReserva');
// Ajax para consultar horas ocupadas
Route::get('/horas-ocupadas/{cancha}/{fecha}', [FrontendController::class, 'horasOcupadas']);

// ================== GESTOR Y ADMIN ==================
Route::middleware(['auth', 'role:admin,gestor'])->group(function () {
Route::resource('clubes', ClubController::class)->parameters(['clubes' => 'club']);
Route::post('/club-servicios', [ClubServicioController::class, 'store'])->name('club_servicios.store');
Route::post('/club-redes/sync', [ClubRedSocialController::class, 'syncRedes'])->name('club_red_social.sync');
Route::post('club-red-social',[ClubRedSocialController::class, 'store'])->name('club_red_social.store');
});

// ================== FILTRO POR HORARIO Y DIA  ==================
Route::post('/buscar-canchas', [FrontendController::class, 'buscarCanchasDisponibles'])
    ->name('buscar.canchas');

Route::get('/api/cancha/{id}', function ($id) {
    return Canchas::select('duracion_maxima')->findOrFail($id);
});
Route::post('/canchas/{cancha}/crear-horario',
    [CanchaTipoReservacionController::class, 'crearHorario'])->name('canchas.tipos.update');
Route::delete('/canchas/{cancha}/horario/{tipo}', 
    [CanchaTipoReservacionController::class, 'destroyHorario']
)->name('canchas.horarios.destroy');


require __DIR__.'/auth.php';
