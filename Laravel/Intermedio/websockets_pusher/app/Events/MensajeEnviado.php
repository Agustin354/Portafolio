<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MensajeEnviado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public string $sala,
        public string $usuario,
        public string $mensaje,
        public string $hora
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel("chat.{$this->sala}");
    }

    public function broadcastAs(): string
    {
        return 'mensaje.nuevo';
    }

    public function broadcastWith(): array
    {
        return [
            'usuario' => $this->usuario,
            'mensaje' => $this->mensaje,
            'hora'    => $this->hora,
        ];
    }
}
