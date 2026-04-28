<?php

$TASAS = ['USD' => 1.0, 'ARS' => 900.0, 'EUR' => 0.92, 'BRL' => 5.0, 'CLP' => 950.0, 'MXN' => 17.5];

function convertir(float $monto, string $origen, string $destino, array $tasas): float
{
    return ($monto / $tasas[strtoupper($origen)]) * $tasas[strtoupper($destino)];
}

echo "=== Conversor de Monedas ===\nMonedas: " . implode(', ', array_keys($TASAS)) . "\n";

while (true) {
    echo "\nConvertir (ej: 100 ARS USD) o 'salir': ";
    $entrada = trim(fgets(STDIN));
    if ($entrada === 'salir') break;

    $partes = explode(' ', $entrada);
    if (count($partes) === 3) {
        [$monto, $origen, $destino] = $partes;
        if (!isset($TASAS[strtoupper($origen)], $TASAS[strtoupper($destino)])) {
            echo "Moneda no soportada.\n";
            continue;
        }
        $resultado = convertir((float)$monto, $origen, $destino, $TASAS);
        printf("%.2f %s = %.2f %s\n", $monto, strtoupper($origen), $resultado, strtoupper($destino));
    } else {
        echo "Formato inválido.\n";
    }
}
