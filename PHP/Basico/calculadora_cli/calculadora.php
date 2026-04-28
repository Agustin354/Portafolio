<?php

function calcular(float $a, string $op, float $b): string|float
{
    return match ($op) {
        '+' => $a + $b,
        '-' => $a - $b,
        '*' => $a * $b,
        '/' => $b != 0 ? $a / $b : 'Error: división por cero',
        default => 'Operador no válido',
    };
}

echo "=== Calculadora CLI ===\n";

while (true) {
    echo "Operación (ej: 5 + 3) o 'salir': ";
    $entrada = trim(fgets(STDIN));

    if ($entrada === 'salir') break;

    $partes = explode(' ', $entrada);
    if (count($partes) === 3) {
        [$a, $op, $b] = $partes;
        echo "Resultado: " . calcular((float)$a, $op, (float)$b) . "\n";
    } else {
        echo "Formato inválido. Usá: número operador número\n";
    }
}
