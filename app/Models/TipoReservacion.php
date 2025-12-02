<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReservacion extends Model
{
    use HasFactory;
    protected $table = 'tipo_reservacion';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'precio',
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
    //public function partidas()
    //{
    //    return $this->hasMany(Partida::class);
    //}

}
