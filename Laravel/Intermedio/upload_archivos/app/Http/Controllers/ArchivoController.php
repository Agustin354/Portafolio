<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    private array $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

    public function store(Request $request)
    {
        $request->validate([
            'archivo'  => 'required|file|max:5120|mimes:jpg,jpeg,png,webp,pdf',
            'carpeta'  => 'nullable|string|max:50',
        ]);

        $archivo  = $request->file('archivo');
        $carpeta  = $request->carpeta ?? 'general';
        $nombre   = Str::uuid() . '.' . $archivo->getClientOriginalExtension();

        $ruta = $archivo->storeAs("uploads/{$carpeta}", $nombre, 'public');

        return response()->json([
            'nombre'   => $nombre,
            'original' => $archivo->getClientOriginalName(),
            'tamano'   => $archivo->getSize(),
            'url'      => Storage::url($ruta),
        ], 201);
    }

    public function destroy(string $nombre)
    {
        $ruta = "uploads/{$nombre}";
        abort_unless(Storage::disk('public')->exists($ruta), 404);
        Storage::disk('public')->delete($ruta);
        return response()->noContent();
    }

    public function index(Request $request)
    {
        $carpeta  = $request->carpeta ?? 'general';
        $archivos = Storage::disk('public')->files("uploads/{$carpeta}");

        return response()->json(array_map(fn($a) => [
            'nombre' => basename($a),
            'url'    => Storage::url($a),
            'tamano' => Storage::disk('public')->size($a),
        ], $archivos));
    }
}
