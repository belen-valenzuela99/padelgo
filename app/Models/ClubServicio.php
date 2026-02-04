<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubServicio extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'club_servicios';

    // Campos asignables masivamente
    protected $fillable = [
        'id_club',
        'nombre_servicio',
    ];

    /**
     * Relación: el servicio pertenece a un club
     */
    public function club()
    {
        return $this->belongsTo(Club::class, 'id_club');
    }
}
