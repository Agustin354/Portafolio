<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        return Producto::paginate(15);
    }

    public function show(Producto $producto)
    {
        return $producto;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'precio' => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
        ]);

        return Producto::create($validated)->fresh();
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|max:100',
            'precio' => 'sometimes|numeric|min:0',
            'stock'  => 'sometimes|integer|min:0',
        ]);

        $producto->update($validated);
        return $producto;
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->noContent();
    }
}
