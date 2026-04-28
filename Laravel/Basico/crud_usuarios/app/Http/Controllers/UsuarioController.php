<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(15);
        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|min:2|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'in:user,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'user',
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name'  => 'required|min:2|max:100',
            'email' => "required|email|unique:users,email,{$usuario->id}",
            'role'  => 'in:user,admin',
        ]);

        $usuario->update($validated);
        return redirect()->route('usuarios.index')->with('success', 'Actualizado.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Eliminado.');
    }
}
