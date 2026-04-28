<?php

require 'vendor/autoload.php';

use Predis\Client;

class Cache
{
    private Client $redis;
    private string $prefijo;

    public function __construct(string $prefijo = 'cache', string $host = 'localhost')
    {
        $this->redis   = new Client(['host' => $host]);
        $this->prefijo = $prefijo;
    }

    public function get(string $clave): mixed
    {
        $valor = $this->redis->get("{$this->prefijo}:{$clave}");
        return $valor ? json_decode($valor, true) : null;
    }

    public function set(string $clave, mixed $valor, int $ttl = 60): void
    {
        $this->redis->setex("{$this->prefijo}:{$clave}", $ttl, json_encode($valor, JSON_UNESCAPED_UNICODE));
    }

    public function forget(string $clave): void
    {
        $this->redis->del("{$this->prefijo}:{$clave}");
    }

    public function recordar(string $clave, int $ttl, callable $callback): mixed
    {
        $cached = $this->get($clave);
        if ($cached !== null) {
            echo "[HIT]  $clave\n";
            return $cached;
        }
        echo "[MISS] $clave\n";
        $valor = $callback();
        $this->set($clave, $valor, $ttl);
        return $valor;
    }

    public function invalidarPatron(string $patron): int
    {
        $claves = $this->redis->keys("{$this->prefijo}:{$patron}");
        if ($claves) $this->redis->del($claves);
        return count($claves);
    }
}

$cache = new Cache('portfolio');

$producto = $cache->recordar('producto:1', 30, function () {
    sleep(1);
    return ['id' => 1, 'nombre' => 'Laptop', 'precio' => 1200.0];
});
echo "Producto: " . json_encode($producto) . "\n";

$producto = $cache->recordar('producto:1', 30, function () {
    return ['id' => 1, 'nombre' => 'Laptop', 'precio' => 1200.0];
});
echo "Producto (cache): " . json_encode($producto) . "\n";

$eliminadas = $cache->invalidarPatron('producto:*');
echo "Invalidadas: $eliminadas claves\n";
