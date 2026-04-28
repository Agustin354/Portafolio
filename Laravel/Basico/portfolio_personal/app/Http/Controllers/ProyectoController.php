<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::publicados()->orderBy('orden')->with('tecnologias')->get();
        return view('proyectos.index', compact('proyectos'));
    }

    public function show(Proyecto $proyecto)
    {
        abort_unless($proyecto->publicado, 404);
        return view('proyectos.show', compact('proyecto'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'       => 'required|min:3|max:100',
            'descripcion'  => 'required',
            'url'          => 'nullable|url',
            'tecnologias'  => 'array',
        ]);

        $proyecto = Proyecto::create($validated);
        $proyecto->tecnologias()->sync($request->tecnologias ?? []);

        if ($request->hasFile('imagen')) {
            $proyecto->addMediaFromRequest('imagen')->toMediaCollection('imagenes');
        }

        return redirect()->route('proyectos.show', $proyecto)->with('success', 'Proyecto creado.');
    }
}
