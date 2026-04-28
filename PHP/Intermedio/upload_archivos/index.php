<?php

$TIPOS_PERMITIDOS = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
$TAMANO_MAXIMO   = 5 * 1024 * 1024; // 5 MB
$DIRECTORIO      = __DIR__ . '/uploads/';

if (!is_dir($DIRECTORIO)) mkdir($DIRECTORIO, 0755, true);

function responder(mixed $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['error' => 'Método no permitido'], 405);
}

$archivo = $_FILES['archivo'] ?? null;
if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
    responder(['error' => 'Archivo requerido'], 400);
}

$tipo = mime_content_type($archivo['tmp_name']);
if (!in_array($tipo, $TIPOS_PERMITIDOS)) {
    responder(['error' => 'Tipo no permitido'], 422);
}

if ($archivo['size'] > $TAMANO_MAXIMO) {
    responder(['error' => 'Tamaño máximo 5MB'], 422);
}

$extension  = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$nombre     = bin2hex(random_bytes(16)) . '.' . $extension;
$destino    = $DIRECTORIO . $nombre;

if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
    responder(['error' => 'Error al guardar el archivo'], 500);
}

responder([
    'nombre'   => $nombre,
    'original' => $archivo['name'],
    'tamano'   => $archivo['size'],
    'tipo'     => $tipo,
    'url'      => '/uploads/' . $nombre,
], 201);
