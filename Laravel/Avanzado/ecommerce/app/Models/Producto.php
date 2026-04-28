<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria_id'];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function itemsPedido()
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function reducirStock(int $cantidad): void
    {
        if ($this->stock < $cantidad) {
            throw new \RuntimeException("Stock insuficiente para {$this->nombre}");
        }
        $this->decrement('stock', $cantidad);
    }
}
