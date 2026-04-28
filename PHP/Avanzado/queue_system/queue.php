<?php

require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ColaMensajes
{
    private $canal;
    private $connection;

    public function __construct(string $host = 'localhost', int $puerto = 5672)
    {
        $this->connection = new AMQPStreamConnection($host, $puerto, 'guest', 'guest');
        $this->canal      = $this->connection->channel();
    }

    public function declarar(string $cola): void
    {
        $this->canal->queue_declare($cola, false, true, false, false);
    }

    public function publicar(string $cola, array $datos): void
    {
        $msg = new AMQPMessage(
            json_encode($datos, JSON_UNESCAPED_UNICODE),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $this->canal->basic_publish($msg, '', $cola);
        echo "[>] Publicado en '$cola': " . json_encode($datos) . "\n";
    }

    public function consumir(string $cola, callable $handler): void
    {
        $this->canal->basic_qos(null, 1, null);
        $this->canal->basic_consume($cola, '', false, false, false, false,
            function ($msg) use ($handler) {
                $datos = json_decode($msg->body, true);
                echo "[<] Recibido: " . $msg->body . "\n";
                $handler($datos);
                $msg->ack();
            }
        );
        echo "Esperando mensajes en '$cola'...\n";
        while ($this->canal->is_consuming()) {
            $this->canal->wait();
        }
    }

    public function __destruct()
    {
        $this->canal->close();
        $this->connection->close();
    }
}

$cola = new ColaMensajes();
$cola->declarar('emails');
$cola->declarar('reportes');

$cola->publicar('emails',   ['to' => 'user@ejemplo.com', 'asunto' => 'Bienvenido', 'cuerpo' => 'Hola!']);
$cola->publicar('reportes', ['mes' => 4, 'anio' => 2026, 'tipo' => 'mensual']);
