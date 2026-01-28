<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedSocial extends Model
{
    use HasFactory;

    protected $table = 'redes_sociales';

    protected $fillable = [
        'nombre',
        //'url_red',
        'img',
    ];

    // Relación muchos a muchos con Club
    public function clubes()
    {
        return $this->belongsToMany(
            Club::class,
            'club_red_social',
            'id_red_social',
            'id_club'
        );
    }
}
