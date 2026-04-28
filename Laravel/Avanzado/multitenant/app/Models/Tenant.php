<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['nombre', 'subdominio', 'activo', 'plan'];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
