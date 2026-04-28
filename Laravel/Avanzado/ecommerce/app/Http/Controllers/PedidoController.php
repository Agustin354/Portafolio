<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = auth()->user()
            ->pedidos()
            ->with(['itemsPedido.producto'])
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.producto_id'    => 'required|exists:productos,id',
            'items.*.cantidad'       => 'required|integer|min:1',
        ]);

        $pedido = DB::transaction(function () use ($request) {
            $pedido = auth()->user()->pedidos()->create(['estado' => 'pendiente', 'total' => 0]);
            $total  = 0;

            foreach ($request->items as $item) {
                $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);
                $producto->reducirStock($item['cantidad']);

                $subtotal = $producto->precio * $item['cantidad'];
                $total   += $subtotal;

                $pedido->itemsPedido()->create([
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => $subtotal,
                ]);
            }

            $pedido->update(['total' => $total]);
            return $pedido;
        });

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido creado.');
    }

    public function show(Pedido $pedido)
    {
        $this->authorize('view', $pedido);
        return view('pedidos.show', ['pedido' => $pedido->load('itemsPedido.producto')]);
    }
}
