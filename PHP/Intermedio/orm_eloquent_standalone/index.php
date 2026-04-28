<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

$db = new DB();
$db->addConnection([
    'driver'   => 'sqlite',
    'database' => __DIR__ . '/portfolio.db',
]);
$db->setAsGlobal();
$db->bootEloquent();

DB::schema()->create('productos', function ($tabla) {
    $tabla->id();
    $tabla->string('nombre');
    $tabla->decimal('precio', 10, 2);
    $tabla->integer('stock')->default(0);
    $tabla->string('categoria')->nullable();
    $tabla->timestamps();
});

class Producto extends Model
{
    protected $fillable = ['nombre', 'precio', 'stock', 'categoria'];

    public function scopeDisponibles($q) { return $q->where('stock', '>', 0); }
    public function scopePorCategoria($q, $cat) { return $q->where('categoria', $cat); }
}

echo "=== ORM Eloquent Standalone ===\n";
Producto::create(['nombre' => 'Laptop', 'precio' => 1200.0, 'stock' => 5, 'categoria' => 'electronica']);
Producto::create(['nombre' => 'Mouse',  'precio' => 25.0,   'stock' => 0, 'categoria' => 'electronica']);
Producto::create(['nombre' => 'Libro PHP', 'precio' => 35.0, 'stock' => 10, 'categoria' => 'libros']);

echo "\nTodos los productos:\n";
Producto::all()->each(fn($p) => printf("  [%d] %s $%.2f stock:%d\n", $p->id, $p->nombre, $p->precio, $p->stock));

echo "\nDisponibles:\n";
Producto::disponibles()->each(fn($p) => print("  {$p->nombre}\n"));

echo "\nElectrónica:\n";
Producto::porCategoria('electronica')->each(fn($p) => print("  {$p->nombre}\n"));
