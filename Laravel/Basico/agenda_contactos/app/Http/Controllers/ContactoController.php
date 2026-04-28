<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index(Request $request)
    {
        $contactos = Contacto::when($request->q, fn($q, $busqueda) =>
            $q->where('nombre', 'like', "%$busqueda%")
              ->orWhere('email', 'like', "%$busqueda%")
        )->orderBy('nombre')->paginate(20);

        return view('contactos.index', compact('contactos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|max:100',
            'email'    => 'nullable|email|unique:contactos',
            'telefono' => 'nullable|max:20',
        ]);

        Contacto::create($request->only('nombre', 'email', 'telefono', 'direccion', 'notas'));
        return redirect()->route('contactos.index')->with('success', 'Contacto agregado.');
    }

    public function update(Request $request, Contacto $contacto)
    {
        $request->validate([
            'nombre'   => 'required|max:100',
            'email'    => "nullable|email|unique:contactos,email,{$contacto->id}",
            'telefono' => 'nullable|max:20',
        ]);

        $contacto->update($request->only('nombre', 'email', 'telefono', 'direccion', 'notas'));
        return redirect()->route('contactos.index')->with('success', 'Actualizado.');
    }

    public function destroy(Contacto $contacto)
    {
        $contacto->delete();
        return redirect()->route('contactos.index')->with('success', 'Eliminado.');
    }
}
