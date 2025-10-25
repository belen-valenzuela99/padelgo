<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CanchasController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClubController;

//Route::get('/', function () {
    //return view('welcome');
//});

// Redirección dinámica según el rol del usuario
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('jugador.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================== DASHBOARD ADMIN ==================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('canchas', CanchasController::class);
    // Se agrega la ruta dentro del middleware del admin o del jugador, 
    Route::resource('clubes', ClubController::class);

});

// ================== DASHBOARD JUGADOR ==================
Route::middleware(['auth', 'role:jugador'])->prefix('jugador')->group(function () {
    Route::get('/dashboard', function () {
        return view('jugador.dashboard');
    })->name('jugador.dashboard');
});

// ================== PERFIL ==================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [ReservaController::class, 'index'])->name('reservas.index');






require __DIR__.'/auth.php';
