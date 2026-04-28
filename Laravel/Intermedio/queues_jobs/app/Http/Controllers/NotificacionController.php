<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarEmailJob;
use App\Models\User;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function enviar(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'asunto'     => 'required|max:100',
            'cuerpo'     => 'required',
            'delay'      => 'nullable|integer|min:0',
        ]);

        $usuario = User::findOrFail($request->usuario_id);
        $delay   = $request->delay ?? 0;

        EnviarEmailJob::dispatch($usuario, $request->asunto, $request->cuerpo)
            ->delay(now()->addSeconds($delay))
            ->onQueue('emails');

        return response()->json(['mensaje' => "Email encolado para {$usuario->email}"]);
    }

    public function masivo(Request $request)
    {
        $request->validate(['asunto' => 'required', 'cuerpo' => 'required']);

        $count = 0;
        User::chunk(100, function ($usuarios) use ($request, &$count) {
            foreach ($usuarios as $usuario) {
                EnviarEmailJob::dispatch($usuario, $request->asunto, $request->cuerpo)
                    ->onQueue('emails');
                $count++;
            }
        });

        return response()->json(['mensaje' => "$count emails encolados"]);
    }
}
