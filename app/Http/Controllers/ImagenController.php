<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Leer la imagen
        $ruta_imagen = $request->file('file')->store('establecimientos', 'public');

        // Resize a la imagen
        $image = Image::make(public_path("/storage/{$ruta_imagen}"))->fit(800, 450);
        $image->save();

        // Almacenar con modelo
        $imagenDB = new Imagen;
        $imagenDB->id_establecimiento = $request['uuid'];
        $imagenDB->ruta_imagen = $ruta_imagen;
        $imagenDB->save();

        // Respuesta
        $respuesta = [
            'archivo' => $ruta_imagen
        ];

        return response()->json($respuesta);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $imagen = $request->get('imagen');

        if (File::exists('storage/' . $imagen)) {
            File::delete('storage/' . $imagen);
        }

        $respuesta = [
            'mensaje' => 'Imagen borrada',
            'imagen' => $imagen
        ];

        // Borro de la BD
        Imagen::where('ruta_imagen', '=', $imagen)->delete();

        return response()->json($respuesta);
    }
}
