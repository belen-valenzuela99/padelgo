<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canchas extends Model
{
    use HasFactory, SoftDeletes;

    // Campos que se pueden asignar masivamente
protected $fillable = [
    'nombre',
    'descripcion',
    'id_club',
    'duracion_maxima',
    'is_active',
];


    // Relación ejemplo: una categoría puede tener muchas partidas
    public function club()
    {
        return $this->belongsTo(Club::class, "id_club");
    }
        
}


