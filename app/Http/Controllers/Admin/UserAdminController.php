<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    // LISTAR
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();

        return view('admin.users.index', compact('users'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        $roles = ['admin', 'gestor', 'jugador'];
        return view('admin.users.create', compact('roles'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,gestor,jugador',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    // FORM EDITAR
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'gestor', 'jugador'];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role'  => 'required|in:admin,gestor,jugador',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // ELIMINAR
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
