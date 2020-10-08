@extends('layouts.app')

@section('styles')
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>

    <!-- Esri Leaflet Geocoder -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"
    />

    {{-- Dropzone --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" integrity="sha512-3g+prZHHfmnvE1HBLwUnVuunaPOob7dpksI7/v6UnF/rnKGwHf/GdEq9K7iEN7qTtW+S0iivTcGpeTBqqB04wA==" crossorigin="anonymous" />
@endsection

@section('content')

    <div class="container">
        <h1 class="text-center mt-4">Registrar Establecimiento</h1>

        <div class="mt-4 row justify-content-center">
            <form class="col-md-9 col-xs-12 card card-body"
                  action="{{ route('establecimiento.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <fieldset class="border p-4 mt-3">
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
                    <div class="form-group">
                        <label for="imagen_principal">Imagen Principal</label>
                        <input
                            style="height: 43px;"
                            id="imagen_principal"
                            type="file"
                            class="form-control @error('imagen_principal') is-invalid @enderror "
                            name="imagen_principal"
                            value="{{ old('imagen_principal') }}"
                        >

                        @error('imagen_principal')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend class="text-primary">Ubicación</legend>

                    {{-- Ubicación --}}
                    <div class="form-group">
                        <label for="formbuscador">Coloca la ubicación de tu establecimiento</label>
                        <input type="text"
                               id="formbuscador"
                               class="form-control"
                               placeholder="Dirección del establecimiento">

                        <p class="text-secondary mt-4 mb-3 text-center">
                            El asistente colocara una dirección estimada o mueve el pin hacia el lugar correcto para mayor precisión.
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
                            name="direccion"
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
                            name="localidad"
                            class="form-control @error('localidad') is-invalid @enderror"
                            placeholder="Localidad"
                            value="{{ old('localidad') }}">

                            @error('localidad')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    {{-- Código Postal --}}
                    <div class="form-group">
                        <label for="direccion">Código Postal</label>
                        <input
                            type="text"
                            id="cp"
                            name="cp"
                            class="form-control @error('cp') is-invalid @enderror"
                            placeholder="Código Postal"
                            value="{{ old('cp') }}">

                            @error('cp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                    <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">

                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend  class="text-primary">Información Establecimiento: </legend>
                        <div class="form-group">
                            <label for="nombre">Teléfono</label>
                            <input
                                type="tel"
                                class="form-control @error('telefono')  is-invalid  @enderror"
                                id="telefono"
                                placeholder="Teléfono Establecimiento"
                                name="telefono"
                                value="{{ old('telefono') }}"
                            >

                                @error('telefono')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombre">Descripción</label>
                            <textarea
                                class="form-control  @error('descripcion')  is-invalid  @enderror"
                                name="descripcion"
                            >{{ old('descripcion') }}</textarea>

                                @error('descripcion')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombre">Hora Apertura:</label>
                            <input
                                type="time"
                                class="form-control @error('apertura')  is-invalid  @enderror"
                                id="apertura"
                                name="apertura"
                                value="{{ old('apertura') }}"
                            >
                            @error('apertura')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombre">Hora Cierre:</label>
                            <input
                                type="time"
                                class="form-control @error('cierre')  is-invalid  @enderror"
                                id="cierre"
                                name="cierre"
                                value="{{ old('cierre') }}"
                            >
                            @error('cierre')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend  class="text-primary">Imágenes del establecimiento: </legend>
                        <div class="form-group">
                            <label for="imagenes">Imágenes</label>
                            <div id="dropzone" class="dropzone form-control">

                            </div>
                        </div>
                </fieldset>

                <input type="hidden" id="uuid" name="uuid" value="{{ Str::uuid()->toString() }}">

                <input type="submit" class="btn btn-primary mt-3 d-block" value="Registrar Establecimiento">

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Leaflet from CDN -->
    <script defer src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin="">
    </script>

     <!-- Load Esri Leaflet from CDN -->
     <script defer src="https://unpkg.com/esri-leaflet"></script>
     <script defer src="https://unpkg.com/esri-leaflet-geocoder"></script>

     {{-- Dropzone --}}
     <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.js" integrity="sha512-8l10HpXwk93V4i9Sm38Y1F3H4KJlarwdLndY9S5v+hSAODWMx3QcAVECA23NTMKPtDOi53VFfhIuSsBjjfNGnA==" crossorigin="anonymous" defer></script>

@endsection
