<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'direccion',
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
    //public function partidas()
   // {
    //    return $this->hasMany(Partida::class);
    //}

}
