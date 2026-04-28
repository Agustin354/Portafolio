<?php

$palabras = ['php', 'programacion', 'variable', 'funcion', 'array', 'clase', 'objeto', 'interfaz', 'bucle', 'puntero'];

$dibujos = [
    "  -----\n  |   |\n      |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n  |   |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|   |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n /    |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n / \\  |\n=========",
];

function jugar(array $palabras, array $dibujos): void
{
    $palabra    = $palabras[array_rand($palabras)];
    $adivinadas = [];
    $intentos   = 0;
    $max        = count($dibujos) - 1;

    while ($intentos < $max) {
        $visible = implode(' ', array_map(fn($l) => in_array($l, $adivinadas) ? $l : '_', str_split($palabra)));
        echo "\n{$dibujos[$intentos]}\n$visible\nIntentadas: " . implode(', ', $adivinadas) . "\n";

        if (!str_contains($visible, '_')) { echo "¡Ganaste!\n"; return; }

        echo 'Letra: ';
        $letra = strtolower(trim(fgets(STDIN)));
        if (strlen($letra) !== 1 || !ctype_alpha($letra)) { echo "Una sola letra.\n"; continue; }
        if (in_array($letra, $adivinadas)) { echo "Ya intentada.\n"; continue; }

        $adivinadas[] = $letra;
        if (!str_contains($palabra, $letra)) $intentos++;
    }
    echo "\n{$dibujos[$max]}\n¡Perdiste! La palabra era: $palabra\n";
}

echo "=== Ahorcado ===\n";
do {
    jugar($palabras, $dibujos);
    echo '¿Jugar de nuevo? (s/n): ';
} while (trim(fgets(STDIN)) === 's');
