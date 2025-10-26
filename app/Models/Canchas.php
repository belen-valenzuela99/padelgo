<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canchas extends Model
{
    
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'ubicacion',
        'id_club',
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
    public function club()
    {
        return $this->belongsTo(Club::class, "id_club");
    }
        
}


