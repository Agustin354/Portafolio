<?php

$archivo = 'notas.json';

function cargar(string $archivo): array
{
    return file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
}

function guardar(string $archivo, array $notas): void
{
    file_put_contents($archivo, json_encode($notas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$notas = cargar($archivo);
echo "=== Gestor de Notas ===\n";

while (true) {
    echo "\n1. Nueva  2. Ver todas  3. Buscar  4. Eliminar  5. Salir\nOpción: ";
    match (trim(fgets(STDIN))) {
        '1' => (function () use (&$notas, $archivo) {
            echo 'Título: ';    $titulo    = trim(fgets(STDIN));
            echo 'Contenido: '; $contenido = trim(fgets(STDIN));
            $notas[] = ['id' => count($notas) + 1, 'titulo' => $titulo,
                        'contenido' => $contenido, 'fecha' => date('Y-m-d')];
            guardar($archivo, $notas);
            echo "Nota guardada.\n";
        })(),
        '2' => array_walk($notas, fn($n) => printf("[%d] %s (%s)\n  %s\n\n", $n['id'], $n['titulo'], $n['fecha'], $n['contenido'])),
        '3' => (function () use ($notas) {
            echo 'Buscar: '; $q = trim(fgets(STDIN));
            foreach (array_filter($notas, fn($n) => str_contains($n['titulo'], $q) || str_contains($n['contenido'], $q)) as $n) {
                printf("[%d] %s: %s\n", $n['id'], $n['titulo'], $n['contenido']);
            }
        })(),
        '4' => (function () use (&$notas, $archivo) {
            echo 'ID: '; $id = (int)trim(fgets(STDIN));
            $notas = array_values(array_filter($notas, fn($n) => $n['id'] !== $id));
            guardar($archivo, $notas);
            echo "Eliminada.\n";
        })(),
        '5' => exit(0),
        default => print("Opción inválida\n"),
    };
}
