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
        'hora_inicio',
        'hora_final',
        'id_tipo_reservacion',
        'status',
        
    ];
    public const STATUS = [
        'programado',
        'cancelado',
        'turno perdido',
        'turno completado',
    ];



}