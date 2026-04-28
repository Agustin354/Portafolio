<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServer implements MessageComponentInterface
{
    private \SplObjectStorage $clientes;
    private array $nombres = [];

    public function __construct()
    {
        $this->clientes = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clientes->attach($conn);
        $nombre = "Usuario_{$conn->resourceId}";
        $this->nombres[$conn->resourceId] = $nombre;
        echo "[+] $nombre conectado. Total: {$this->clientes->count()}\n";
        $this->broadcast(json_encode(['tipo' => 'sistema', 'msg' => "$nombre entró al chat"]), $conn);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $datos  = json_decode($msg, true);
        $nombre = $this->nombres[$from->resourceId];
        $payload = json_encode(['tipo' => 'mensaje', 'de' => $nombre, 'msg' => $datos['msg'] ?? '']);
        echo "$nombre: {$datos['msg']}\n";
        $this->broadcast($payload, $from);
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $nombre = $this->nombres[$conn->resourceId] ?? 'Desconocido';
        $this->clientes->detach($conn);
        unset($this->nombres[$conn->resourceId]);
        $this->broadcast(json_encode(['tipo' => 'sistema', 'msg' => "$nombre salió del chat"]));
        echo "[-] $nombre desconectado.\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function broadcast(string $msg, ?ConnectionInterface $excepto = null): void
    {
        foreach ($this->clientes as $cliente) {
            if ($cliente !== $excepto) $cliente->send($msg);
        }
    }
}

$server = IoServer::factory(new HttpServer(new WsServer(new ChatServer())), 8080);
echo "Servidor WebSocket en ws://localhost:8080\n";
$server->run();
