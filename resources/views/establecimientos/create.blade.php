@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>
@endsection

@section('content')

    <div class="container">
        <h1 class="text-center mt-4">Registrar Establecimiento</h1>

        <div class="mt-4 row justify-content-center">
            <form class="col-md-9 col-xs-12 card card-body"
                  action="">

                <fieldset class="border p-4">
                    <legend class="text-primary">Nombre y Categoría</legend>

                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text"
                               id="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Nombre del establecimiento"
                               name="nombre"
                               value="{{ old('nombre') }}">

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Categoria --}}
                    <div class="form-group">
                        <label for="categoria_id">Categoría</label>
                        <select class="form-control @error('categoria_id') is-invalid @enderror"
                                name="categoria_id"
                                id="categoria_id">
                            <option value="" selected disabled>-- Seleccione --</option>

                            @foreach ($categorias as $categoria)
                               <option value="{{ $categoria->id }}"
                                       {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}
                                >
                                  {{ $categoria->nombre }}
                               </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Imagen --}}
                    <label for="categoria_id">Imagen</label>
                    <div class="custom-file">
                        <input type="file"
                               class="custom-file-input"
                               id="imagen_principal"
                               name="imagen_principal"
                               value="{{ old('imagen_principal') }}"
                               lang="es">
                        <label class="custom-file-label" for="imagen_principal">
                            Seleccionar Imagen
                        </label>

                        @error('imagen_principal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                      </div>

                </fieldset>


                <fieldset class="border p-4">
                    <legend class="text-primary">Ubicación</legend>

                    {{-- Ubicación --}}
                    <div class="form-group">
                        <label for="formbuscador">Coloca la ubicación de tu establecimiento</label>
                        <input type="text"
                               id="formbuscador"
                               class="form-control"
                               placeholder="Dirección del establecimiento">

                        <p class="text-secondary mt-4 mb-3 text-center">
                            El asistente colocara una dirección estimada. Mueve el pin hacia el lugar correcto.
                        </p>
                    </div>

                    <div class="form-group">
                        <div id="mapa" style="height: 400px"></div>
                    </div>

                    <p class="informacion">Confirma que los siguientes campos son correctos</p>

                    {{-- Datos de la direccion cargados por la API --}}
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input
                            type="text"
                            id="direccion"
                            class="form-control @error('direccion') is-invalid @enderror"
                            placeholder="Dirección"
                            value="{{ old('direccion') }}">

                            @error('direccion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    {{-- Localidad --}}
                    <div class="form-group">
                        <label for="direccion">Localidad</label>
                        <input
                            type="text"
                            id="localidad"
                            class="form-control @error('localidad') is-invalid @enderror"
                            placeholder="Localidad"
                            value="{{ old('localidad') }}">

                            @error('localidad')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                    <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">

                </fieldset>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin="">
    </script>
@endsection
