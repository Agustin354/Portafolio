<?php

function listar(string $dir): void
{
    foreach (scandir($dir) as $item) {
        if (!str_starts_with($item, '.')) echo "$item\n";
    }
}

function crear(string $ruta, string $contenido = ''): void
{
    file_put_contents($ruta, $contenido);
    echo "Creado: $ruta\n";
}

function copiar(string $origen, string $destino): void
{
    copy($origen, $destino);
    echo "Copiado: $origen → $destino\n";
}

function mover(string $origen, string $destino): void
{
    rename($origen, $destino);
    echo "Movido: $origen → $destino\n";
}

function eliminar(string $ruta): void
{
    if (file_exists($ruta)) {
        unlink($ruta);
        echo "Eliminado: $ruta\n";
    } else {
        echo "No encontrado: $ruta\n";
    }
}

function buscar(string $dir, string $patron): void
{
    foreach (glob("$dir/**/$patron", GLOB_BRACE) as $archivo) {
        echo "$archivo\n";
    }
}

echo "=== Gestor de Archivos ===\n";

while (true) {
    echo "\n1. Listar  2. Crear  3. Copiar  4. Mover  5. Eliminar  6. Buscar  7. Salir\n";
    echo "Opción: ";
    $op = trim(fgets(STDIN));

    match ($op) {
        '1' => (function () { echo 'Directorio: '; listar(trim(fgets(STDIN))); })(),
        '2' => (function () { echo 'Ruta: '; $r = trim(fgets(STDIN)); echo 'Contenido: '; crear($r, trim(fgets(STDIN))); })(),
        '3' => (function () { echo 'Origen: '; $o = trim(fgets(STDIN)); echo 'Destino: '; copiar($o, trim(fgets(STDIN))); })(),
        '4' => (function () { echo 'Origen: '; $o = trim(fgets(STDIN)); echo 'Destino: '; mover($o, trim(fgets(STDIN))); })(),
        '5' => (function () { echo 'Ruta: '; eliminar(trim(fgets(STDIN))); })(),
        '6' => (function () { echo 'Directorio: '; $d = trim(fgets(STDIN)); echo 'Patrón: '; buscar($d, trim(fgets(STDIN))); })(),
        '7' => exit(0),
        default => print("Opción inválida\n"),
    };
}
