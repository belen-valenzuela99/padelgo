<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';

    protected $fillable = [
        'user_id',
        'cancha_id',
        'dia_semana',
        'mes',
        'hora_inicio',
        'hora_fin',
        'precio',
        'activo',
    ];

    // --- RELACIONES ---

    // Un abono pertenece a un jugador/usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un abono pertenece a una cancha
    public function cancha()
    {
        return $this->belongsTo(Canchas::class, 'cancha_id');
    }

    // Relación indirecta: el abono pertenece a un club a través de la cancha
    public function club()
    {
        return $this->hasOneThrough(
            Club::class,     // Modelo final
            Canchas::class,   // Modelo intermedio
            'id',            // FK en canchas → ID de cancha
            'id',            // FK en clubs → ID del club
            'cancha_id',     // FK en abonos → cancha
            'club_id'        // FK en canchas → club
        );
    }

    // Scope para obtener abonos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
