<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservacion;
use Carbon\Carbon;

class ActualizarEstadoReservaciones extends Command
{
    protected $signature = 'reservaciones:actualizar-estado';
    protected $description = 'Actualiza automáticamente el estado de las reservaciones completadas';

    public function handle()
    {
        $ahora = Carbon::now();

        // Cambiar estado a "turno completado"
        Reservacion::where('status', 'programado')
            ->whereRaw("CONCAT(reservacion_date, ' ', hora_final) <= ?", [$ahora])
            ->update(['status' => 'turno completado']);

        $this->info("Estados actualizados correctamente.");
    }
}
