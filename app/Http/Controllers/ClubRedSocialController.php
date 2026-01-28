<?php
namespace App\Http\Controllers;

use App\Models\ClubRedSocial;
use App\Models\RedSocial;
use Illuminate\Http\Request;

class ClubRedSocialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_club' => 'required|exists:clubs,id',
            'redes' => 'array',
            'redes.*' => 'nullable|string|max:255',
        ]);

        foreach ($request->redes as $idRedSocial => $url) {
            if ($url) {
                ClubRedSocial::updateOrCreate(
                    [
                        'id_club' => $request->id_club,
                        'id_red_social' => $idRedSocial,
                    ],
                    [
                        'url_red' => $url,
                    ]
                );
            }
        }

        return back()->with('success', 'Redes sociales guardadas correctamente');
    }

    public function syncRedes(Request $request)
{
    $clubId = $request->id_club;
    $redesRequest = $request->input('redes', []); // solo las marcadas

    // Obtener todas las redes sociales existentes
    $todasLasRedes = RedSocial::pluck('id');

    foreach ($todasLasRedes as $redId) {

        if (array_key_exists($redId, $redesRequest)) {

            // Crear o actualizar si está marcada
            ClubRedSocial::updateOrCreate(
                [
                    'id_club' => $clubId,
                    'id_red_social' => $redId,
                ],
                [
                    'url_red' => $redesRequest[$redId],
                ]
            );

        } else {
            // Si NO está marcada → eliminar relación
            ClubRedSocial::where('id_club', $clubId)
                ->where('id_red_social', $redId)
                ->delete();
        }
    }

    return redirect()->back()->with('success', 'Redes sociales actualizadas correctamente.');
}
}
