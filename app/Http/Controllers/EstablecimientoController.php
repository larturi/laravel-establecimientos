<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\Imagen;
use Intervention\Image\Facades\Image;

class EstablecimientoController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('establecimientos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validacion
        $data = $request->validate([
            'nombre'           => 'required',
            'categoria_id'     => 'required|exists:App\Models\Categoria,id',
            'imagen_principal' => 'required|image|max:1000',
            'direccion'        => 'required',
            'localidad'        => 'required',
            'cp'               => 'required',
            'lat'              => 'required',
            'lng'              => 'required',
            'telefono'         => 'required|numeric',
            'descripcion'      => 'required|min:50',
            'apertura'         => 'date_format:H:i',
            'cierre'           => 'date_format:H:i|after:apertura',
            'uuid'             => 'required|uuid',
        ]);

        // Guardar la imagen
        $ruta_imagen = $request['imagen_principal']->store('principales', 'public');

        // Resize a la imagen
        $img = Image::make("storage/{$ruta_imagen}")->fit(800, 600);
        $img->save();

        // Guardar en BD
        auth()->user()->establecimiento()->create([
            'nombre'           => $data['nombre'],
            'categoria_id'     => $data['categoria_id'],
            'imagen_principal' => $ruta_imagen,
            'direccion'        => $data['direccion'],
            'localidad'        => $data['localidad'],
            'cp'               => $data['cp'],
            'lat'              => $data['lat'],
            'lng'              => $data['lng'],
            'telefono'         => $data['telefono'],
            'descripcion'      => $data['descripcion'],
            'apertura'         => $data['apertura'],
            'cierre'           => $data['cierre'],
            'uuid'             => $data['uuid']
        ]);

        return back()->with('estado', 'La informacion se amlacenó correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Establecimiento $establecimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Establecimiento $establecimiento)
    {
        // Obtener las Categorias
        $categorias = Categoria::all();

        // Obtener el Establecimiento
        $establecimiento = auth()->user()->establecimiento;

        // Para corregir problema en Firefox que agrega segundos
        $establecimiento->apertura = date('H:i', strtotime($establecimiento->apertura));
        $establecimiento->cierre = date('H:i', strtotime($establecimiento->cierre));

        // Obtiene las imagenes para mostrar en el formulario
        $imagenes = Imagen::where('id_establecimiento', $establecimiento->uuid)->get();

        // Redireccion
        return view('establecimientos.edit', compact('categorias', 'establecimiento', 'imagenes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Establecimiento $establecimiento)
    {

        // Policy para asegurar que solo edite el creador del establecimiento
        $this->authorize('update', $establecimiento);

        // Validacion
        $data = $request->validate([
            'nombre'           => 'required',
            'categoria_id'     => 'required|exists:App\Models\Categoria,id',
            'imagen_principal' => 'image|max:1000',
            'direccion'        => 'required',
            'localidad'        => 'required',
            'cp'               => 'required',
            'lat'              => 'required',
            'lng'              => 'required',
            'telefono'         => 'required|numeric',
            'descripcion'      => 'required|min:50',
            'apertura'         => 'date_format:H:i',
            'cierre'           => 'date_format:H:i|after:apertura',
            'uuid'             => 'required|uuid',
        ]);

        $establecimiento->nombre = $data['nombre'];
        $establecimiento->categoria_id = $data['categoria_id'];
        $establecimiento->direccion = $data['direccion'];
        $establecimiento->localidad = $data['localidad'];
        $establecimiento->cp = $data['cp'];
        $establecimiento->lat = $data['lat'];
        $establecimiento->lng = $data['lng'];
        $establecimiento->telefono = $data['telefono'];
        $establecimiento->descripcion = $data['descripcion'];
        $establecimiento->apertura = $data['apertura'];
        $establecimiento->cierre = $data['cierre'];
        $establecimiento->uuid = $data['uuid'];

        // Si el usuario sube una imagen la actualizo
        if(request('imagen_principal')) {
            // Guardar la imagen
            $ruta_imagen = $request['imagen_principal']->store('principales', 'public');

            // Resize a la imagen
            $img = Image::make("storage/{$ruta_imagen}")->fit(800, 600);
            $img->save();

            $establecimiento->imagen_principal = $ruta_imagen;
        }

        $establecimiento->save();

        // Mensaje y redireccion
        return back()->with('estado', 'Los cambios se guardaron correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Establecimiento  $establecimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Establecimiento $establecimiento)
    {
        //
    }
}
