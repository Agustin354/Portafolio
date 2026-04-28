<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GatewayController extends Controller
{
    private array $servicios = [
        'productos' => 'http://productos-service:5001',
        'pedidos'   => 'http://pedidos-service:5002',
        'usuarios'  => 'http://usuarios-service:5003',
    ];

    public function proxy(Request $request, string $servicio, string $path = '')
    {
        abort_unless(isset($this->servicios[$servicio]), 404, 'Servicio no encontrado');

        $url     = "{$this->servicios[$servicio]}/{$path}";
        $metodo  = strtolower($request->method());
        $cacheKey = "gateway:{$servicio}:{$path}";

        if ($metodo === 'get') {
            $respuesta = Cache::remember($cacheKey, 60, fn() =>
                Http::withToken($request->bearerToken())
                    ->get($url, $request->query())
                    ->json()
            );
            return response()->json($respuesta);
        }

        Cache::forget($cacheKey);
        $respuesta = Http::withToken($request->bearerToken())
            ->$metodo($url, $request->all());

        return response()->json($respuesta->json(), $respuesta->status());
    }
}
