<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Establecimiento;
use Illuminate\Http\Request;

class APIController extends Controller
{
    // Metodo para obtener todas las categorias
    public function categorias()
    {
        $categorias = Categoria::all();

        return response()->json($categorias);
    }

    // Mostrar los establecimientos de una determinada categotia
    public function categoria(Categoria $categoria)
    {
        $establecimientos = Establecimiento::where('categoria_id', $categoria->id)
             ->with('categoria')
             ->take(3)
             ->get();

        return response()->json($establecimientos);
    }

    // Muestra un establecimiento en especifico
    public function show(Establecimiento $establecimiento)
    {
        return response()->json($establecimiento);
    }

}
