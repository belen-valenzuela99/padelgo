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
        
    public function tiposReservacion()
    {
        return $this->belongsToMany(TipoReservacion::class, 'cancha_tipo_reservacion', 'cancha_id', 'tipo_reservacion_id')
                    ->withPivot('precio', 'activo')
                    ->withTimestamps();
    }


    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'cancha_id');
        }


}


