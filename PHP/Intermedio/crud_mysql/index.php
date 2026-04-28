<?php

require 'db.php';

$pdo = conectar();
$pdo->exec("CREATE TABLE IF NOT EXISTS productos (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock  INT DEFAULT 0
)");

function listar(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM productos')->fetchAll();
}

function crear(PDO $pdo, string $nombre, float $precio, int $stock): int
{
    $stmt = $pdo->prepare('INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)');
    $stmt->execute([$nombre, $precio, $stock]);
    return (int)$pdo->lastInsertId();
}

function actualizar(PDO $pdo, int $id, string $nombre, float $precio, int $stock): bool
{
    $stmt = $pdo->prepare('UPDATE productos SET nombre=?, precio=?, stock=? WHERE id=?');
    $stmt->execute([$nombre, $precio, $stock, $id]);
    return $stmt->rowCount() > 0;
}

function eliminar(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare('DELETE FROM productos WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

// Demo CLI
echo "=== CRUD MySQL ===\n";
while (true) {
    echo "\n1. Listar  2. Crear  3. Actualizar  4. Eliminar  5. Salir\nOpción: ";
    $op = trim(fgets(STDIN));

    match ($op) {
        '1' => array_map(fn($r) => print(implode(' | ', $r) . "\n"), listar($pdo)),
        '2' => (function () use ($pdo) {
            echo 'Nombre: '; $n = trim(fgets(STDIN));
            echo 'Precio: '; $p = (float)trim(fgets(STDIN));
            echo 'Stock: ';  $s = (int)trim(fgets(STDIN));
            $id = crear($pdo, $n, $p, $s);
            echo "Creado con ID: $id\n";
        })(),
        '3' => (function () use ($pdo) {
            echo 'ID: ';     $id = (int)trim(fgets(STDIN));
            echo 'Nombre: '; $n  = trim(fgets(STDIN));
            echo 'Precio: '; $p  = (float)trim(fgets(STDIN));
            echo 'Stock: ';  $s  = (int)trim(fgets(STDIN));
            actualizar($pdo, $id, $n, $p, $s) ? print("Actualizado.\n") : print("No encontrado.\n");
        })(),
        '4' => (function () use ($pdo) {
            echo 'ID: ';
            $id = (int)trim(fgets(STDIN));
            eliminar($pdo, $id) ? print("Eliminado.\n") : print("No encontrado.\n");
        })(),
        '5' => exit(0),
        default => print("Opción inválida\n"),
    };
}
