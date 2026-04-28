<?php

namespace App\Http\Controllers;

use App\Events\MensajeEnviado;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'sala'    => 'required|string|max:50',
            'mensaje' => 'required|string|max:500',
        ]);

        MensajeEnviado::dispatch(
            $request->sala,
            auth()->user()->name,
            $request->mensaje,
            now()->format('H:i')
        );

        return response()->noContent();
    }
}
