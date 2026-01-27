<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRedSocial extends Model
{
    use HasFactory;

    protected $table = 'club_red_social';

    protected $fillable = [
        'id_club',
        'id_red_social',
    ];

    // Relación con Club
    public function club()
    {
        return $this->belongsTo(Club::class, 'id_club');
    }

    // Relación con RedSocial
    public function redSocial()
    {
        return $this->belongsTo(RedSocial::class, 'id_red_social');
    }
}
