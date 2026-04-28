<?php

require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json; charset=utf-8');

$SECRET = 'clave_secreta_cambiar_en_produccion';
$ALGO   = 'HS256';

$usuarios = [
    ['id' => 1, 'email' => 'admin@ejemplo.com', 'password' => password_hash('1234', PASSWORD_BCRYPT), 'rol' => 'admin'],
    ['id' => 2, 'email' => 'user@ejemplo.com',  'password' => password_hash('abcd', PASSWORD_BCRYPT), 'rol' => 'user'],
];

function responder(mixed $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

function generar_token(array $usuario, string $secret, string $algo): string
{
    $payload = [
        'iss' => 'portfolio-api',
        'iat' => time(),
        'exp' => time() + 3600,
        'sub' => $usuario['id'],
        'rol' => $usuario['rol'],
    ];
    return JWT::encode($payload, $secret, $algo);
}

function verificar_token(string $secret, string $algo): object
{
    $headers = apache_request_headers();
    $auth    = $headers['Authorization'] ?? '';
    if (!str_starts_with($auth, 'Bearer ')) {
        responder(['error' => 'Token requerido'], 401);
    }
    try {
        return JWT::decode(substr($auth, 7), new Key($secret, $algo));
    } catch (Exception) {
        responder(['error' => 'Token inválido o expirado'], 401);
    }
}

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$body   = json_decode(file_get_contents('php://input'), true) ?? [];

match (true) {
    $ruta === '/login' && $metodo === 'POST' => (function () use ($usuarios, $body, $SECRET, $ALGO) {
        $usuario = array_values(array_filter($usuarios, fn($u) => $u['email'] === ($body['email'] ?? '')))[0] ?? null;
        if (!$usuario || !password_verify($body['password'] ?? '', $usuario['password'])) {
            responder(['error' => 'Credenciales inválidas'], 401);
        }
        responder(['token' => generar_token($usuario, $SECRET, $ALGO)]);
    })(),

    $ruta === '/perfil' && $metodo === 'GET' => (function () use ($SECRET, $ALGO) {
        $payload = verificar_token($SECRET, $ALGO);
        responder(['usuario_id' => $payload->sub, 'rol' => $payload->rol]);
    })(),

    default => responder(['error' => 'Ruta no encontrada'], 404),
};
