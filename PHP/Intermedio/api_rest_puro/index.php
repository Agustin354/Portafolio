<?php

header('Content-Type: application/json; charset=utf-8');

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$partes = array_filter(explode('/', trim($ruta, '/')));
$partes = array_values($partes);

// Datos en memoria (reemplazar con base de datos en producción)
$productos = [
    ['id' => 1, 'nombre' => 'Laptop', 'precio' => 1200.0],
    ['id' => 2, 'nombre' => 'Mouse',  'precio' => 25.0],
];

function responder(mixed $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function leer_body(): array
{
    return json_decode(file_get_contents('php://input'), true) ?? [];
}

// Router básico
if ($partes[0] !== 'productos') {
    responder(['error' => 'Ruta no encontrada'], 404);
}

$id = isset($partes[1]) ? (int)$partes[1] : null;

match (true) {
    $metodo === 'GET' && $id === null => responder($productos),

    $metodo === 'GET' => (function () use ($productos, $id) {
        $item = array_values(array_filter($productos, fn($p) => $p['id'] === $id))[0] ?? null;
        $item ? responder($item) : responder(['error' => 'No encontrado'], 404);
    })(),

    $metodo === 'POST' => (function () use (&$productos) {
        $data = leer_body();
        $data['id'] = max(array_column($productos, 'id')) + 1;
        $productos[] = $data;
        responder($data, 201);
    })(),

    $metodo === 'DELETE' && $id !== null => (function () use (&$productos, $id) {
        $nuevos = array_values(array_filter($productos, fn($p) => $p['id'] !== $id));
        count($nuevos) < count($productos)
            ? responder(['mensaje' => 'Eliminado'])
            : responder(['error' => 'No encontrado'], 404);
    })(),

    default => responder(['error' => 'Método no permitido'], 405),
};
