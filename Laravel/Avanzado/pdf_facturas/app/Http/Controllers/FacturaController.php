<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class FacturaController extends Controller
{
    public function descargar(Pedido $pedido): Response
    {
        $this->authorize('view', $pedido);

        $pdf = Pdf::loadView('facturas.pdf', [
            'pedido'   => $pedido->load('itemsPedido.producto', 'usuario'),
            'numero'   => str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
            'fecha'    => $pedido->created_at->format('d/m/Y'),
        ]);

        return $pdf->download("factura_{$pedido->id}.pdf");
    }

    public function preview(Pedido $pedido): Response
    {
        $this->authorize('view', $pedido);

        return Pdf::loadView('facturas.pdf', [
            'pedido' => $pedido->load('itemsPedido.producto', 'usuario'),
            'numero' => str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
            'fecha'  => $pedido->created_at->format('d/m/Y'),
        ])->stream("factura_{$pedido->id}.pdf");
    }
}
