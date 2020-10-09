<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Leer las rutas por el slug en vez de por el id
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relación 1:n para categorias y establecimientos
    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class);
    }

}
