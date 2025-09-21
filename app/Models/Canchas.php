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
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
    /*public function partidas()
    {
        return $this->hasMany(Partida::class);
    }*/
        
}


