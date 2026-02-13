<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReservacion extends Model
{
    use HasFactory;

    protected $table = 'tipo_reservacion';

    protected $fillable = [
        'cancha_id',
        'hora_inicio',
        'hora_fin',
        'precio',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación: pertenece a una cancha
    public function cancha()
    {
        return $this->belongsTo(Canchas::class, 'cancha_id');
    }

    // Relación opcional si quieres validar eliminaciones
    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_tipo_reservacion');
    }
}

