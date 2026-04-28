<?php

namespace App\GraphQL\Queries;

use App\Models\Producto;

final class ProductoQuery
{
    public function __invoke($_, array $args): mixed
    {
        if (isset($args['id'])) {
            return Producto::findOrFail($args['id']);
        }

        $query = Producto::query();

        if (isset($args['categoria'])) {
            $query->whereHas('categoria', fn($q) => $q->where('nombre', $args['categoria']));
        }

        if (isset($args['min_precio'])) {
            $query->where('precio', '>=', $args['min_precio']);
        }

        if (isset($args['max_precio'])) {
            $query->where('precio', '<=', $args['max_precio']);
        }

        return $query->disponibles()->paginate($args['limit'] ?? 15);
    }
}
