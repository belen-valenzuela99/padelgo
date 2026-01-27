<?php


namespace App\Http\Controllers;


use App\Models\RedSocial;
use Illuminate\Http\Request;


class RedSocialController extends Controller
{
public function index()
{
$redes = RedSocial::all();
return view('admin.redes_sociales.index', compact('redes'));
}


public function create()
{
return view('admin.redes_sociales.create');
}


public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'url_red' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $data = $request->only('nombre', 'url_red');

    if ($request->hasFile('img')) {
        $file = $request->file('img');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('img/redes_sociales'), $filename);

        // Guardamos solo la ruta en texto
        $data['img'] = 'img/redes_sociales/' . $filename;
    }

    RedSocial::create($data);

    return redirect()->route('redes_sociales.index')
        ->with('success', 'Red social creada correctamente');
}



public function edit($id)
{
$red = RedSocial::findOrFail($id);
return view('admin.redes_sociales.edit', compact('red'));
}


public function update(Request $request, $id)
{
    $red = RedSocial::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:100',
        'url_red' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $data = $request->only('nombre', 'url_red');

    if ($request->hasFile('img')) {
        $file = $request->file('img');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('img/redes_sociales'), $filename);

        $data['img'] = 'img/redes_sociales/' . $filename;
    }

    $red->update($data);

    return redirect()->route('admin.redes_sociales.index')
        ->with('success', 'Red social actualizada correctamente');
}



public function destroy($id)
{
$red = RedSocial::findOrFail($id);
$red->delete();


return redirect()->route('redes_sociales.index')
->with('success', 'Red social eliminada correctamente');
}
}