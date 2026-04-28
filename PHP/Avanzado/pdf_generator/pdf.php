<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function generarFacturaPDF(array $factura): string
{
    $opciones = new Options();
    $opciones->set('defaultFont', 'Helvetica');
    $opciones->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($opciones);
    $dompdf->loadHtml(renderizarHTML($factura));
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $archivo = "factura_{$factura['numero']}.pdf";
    file_put_contents($archivo, $dompdf->output());
    return $archivo;
}

function renderizarHTML(array $factura): string
{
    $items = array_map(fn($i) =>
        "<tr><td>{$i['descripcion']}</td><td>{$i['cantidad']}</td>
         <td>\${$i['precio']}</td><td>\$" . ($i['cantidad'] * $i['precio']) . "</td></tr>",
        $factura['items']
    );
    $total = array_sum(array_map(fn($i) => $i['cantidad'] * $i['precio'], $factura['items']));

    return "<!DOCTYPE html><html><head><style>
        body { font-family: Helvetica; margin: 40px; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: white; }
        .total { font-weight: bold; font-size: 1.2em; text-align: right; margin-top: 10px; }
    </style></head><body>
        <h1>Factura #{$factura['numero']}</h1>
        <p><strong>Cliente:</strong> {$factura['cliente']}</p>
        <p><strong>Fecha:</strong> {$factura['fecha']}</p>
        <table><tr><th>Descripción</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        " . implode('', $items) . "
        </table>
        <p class='total'>Total: \${$total}</p>
    </body></html>";
}

$factura = [
    'numero'  => '0001',
    'cliente' => 'Empresa SA',
    'fecha'   => date('d/m/Y'),
    'items'   => [
        ['descripcion' => 'Servicio Web', 'cantidad' => 1, 'precio' => 500],
        ['descripcion' => 'Hosting Anual', 'cantidad' => 1, 'precio' => 120],
        ['descripcion' => 'Soporte (hs)', 'cantidad' => 5, 'precio' => 80],
    ],
];

$archivo = generarFacturaPDF($factura);
echo "PDF generado: $archivo\n";
