<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_user',
        'descripcion',
        'nombre',
        'img',
        'direccion',
    ];

    // Relación ejemplo: una categoría puede tener muchas partidas
public function canchas()
{
    return $this->hasMany(Canchas::class, 'id_club')->where('is_active', true);
}

        
    public function gestor()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
        
}
