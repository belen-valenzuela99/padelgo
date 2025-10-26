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
        'img',
        'direccion',
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
    public function canchas()
    {
        return $this->hasMany(Canchas::class, "id_club");
    }
        
}
