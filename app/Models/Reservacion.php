<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'cancha_id',
        'reservacion_date',
        'id_tipo_reservacion',
        'status'
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas

}