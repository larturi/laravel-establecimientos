import { OpenStreetMapProvider } from 'leaflet-geosearch';
const provider = new OpenStreetMapProvider();


document.addEventListener('DOMContentLoaded', () => {

    const mapa = L.map('mapa');
    let markers = new L.featureGroup();

    const geocodeService = L.esri.Geocoding.geocodeService();

    if(document.querySelector('#mapa')) {

        const lat = -34.606975;
        const lng = -58.445667;

        mapa.setView([lat, lng], 16);

        markers.addTo(mapa);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapa);

        // Agregar el pin
        let marker = new L.marker([lat, lng], {
            draggable: true,
            autoPan: true,
        }).addTo(mapa);

        markers.addLayer(marker);

        // Buscador de direcciones
        const buscador = document.getElementById('formbuscador');
        buscador.addEventListener('blur', buscarDireccion);

        reubicarPin(marker);

    }

    function buscarDireccion(e) {
        if (e.target.value.length > 6) {
            provider.search({ query: e.target.value + ' AR' })
               .then(resultado => {
                   if (resultado) {

                        // Limpiar pines previos
                        markers.clearLayers();

                        // Reverse geocoding
                        geocodeService.reverse().latlng(resultado[0].bounds[0], 16).run(function(error, resultado) {

                            // Llenar los inputs
                            llenarInputs(resultado);

                            // Centrar el mapa
                            mapa.setView(resultado.latlng);

                            // Agregar el pin
                            let marker = new L.marker(resultado.latlng, {
                                draggable: true,
                                autoPan: true,
                            }).addTo(mapa);

                            // Asignar al contenedor de markers el nuevo pin
                            markers.addLayer(marker);

                            // Mover el Pin
                            reubicarPin(marker);
                        });
                   }
               })
               .catch(error => {
                   console.error(error);
               });
        }
    }

    function llenarInputs(resultado) {
        document.getElementById('direccion').value = resultado.address.Address || '';
        document.getElementById('localidad').value = resultado.address.Neighborhood || '';
        document.getElementById('lat').value = resultado.latlng.lat || '';
        document.getElementById('lng').value = resultado.latlng.lng || '';
    }

    function reubicarPin(marker) {
        marker.on('moveend', function(e) {
            marker = e.target;

            const posicion = marker.getLatLng();

            // Centrar automaticamente cuando suelta el marker
            mapa.panTo(new L.LatLng(posicion.lat, posicion.lng));

            // Reverse geocoding
            geocodeService.reverse().latlng(posicion, 16).run(function(error, resultado) {
                marker.bindPopup(resultado.address.LongLabel);
                marker.openPopup();

                // Llenar los inputs
                llenarInputs(resultado);
            });
        });
    }

});


