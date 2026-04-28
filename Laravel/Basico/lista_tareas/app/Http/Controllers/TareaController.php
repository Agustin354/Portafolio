<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::orderByDesc('prioridad')->get();
        return view('tareas.index', compact('tareas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|max:100',
            'prioridad'    => 'required|in:baja,media,alta',
            'fecha_limite' => 'nullable|date|after:today',
        ]);

        Tarea::create($request->only('titulo', 'prioridad', 'descripcion', 'fecha_limite'));
        return back()->with('success', 'Tarea creada.');
    }

    public function completar(Tarea $tarea)
    {
        $tarea->update(['estado' => 'completada', 'completada_en' => now()]);
        return back()->with('success', 'Tarea completada.');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return back()->with('success', 'Tarea eliminada.');
    }
}
