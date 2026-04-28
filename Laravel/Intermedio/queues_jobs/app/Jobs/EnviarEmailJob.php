<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 30;
    public int $backoff = 60;

    public function __construct(
        private User   $usuario,
        private string $asunto,
        private string $cuerpo
    ) {}

    public function handle(): void
    {
        Mail::raw($this->cuerpo, function ($msg) {
            $msg->to($this->usuario->email)->subject($this->asunto);
        });
    }

    public function failed(\Throwable $e): void
    {
        logger()->error("Email fallido para {$this->usuario->email}: {$e->getMessage()}");
    }
}
