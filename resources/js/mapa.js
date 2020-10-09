import { OpenStreetMapProvider } from 'leaflet-geosearch';
const provider = new OpenStreetMapProvider();

document.addEventListener('DOMContentLoaded', () => {

    const markers = new L.featureGroup();

    if(document.querySelector('#mapa')) {

        const lat = document.querySelector('#lat').value === '' ? -34.606975 : document.querySelector('#lat').value;
        const lng = document.querySelector('#lng').value === '' ? -58.445667 : document.querySelector('#lng').value;

        let mapa = L.map('mapa');
        mapa.setView([lat, lng], 16);

        let markers = new L.FeatureGroup().addTo(mapa);


        // markers.addTo(mapa);

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
            buscador.addEventListener('blur', (e) => {
                if(e.target.value.length) {
                    buscarDireccion(e.target.value, mapa);
                }
            }
        );

        reubicarPin(marker, mapa);

    }

    function buscarDireccion(direccion, mapa) {

        const geocodeService = L.esri.Geocoding.geocodeService();

        provider.search({ query: direccion + ' AR' })
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
                        reubicarPin(marker, mapa);
                    });
                }
            })
            .catch(error => {
                console.error(error);
            });

    }

    function llenarInputs(resultado) {
        document.getElementById('direccion').value = resultado.address.Address || '';
        document.getElementById('localidad').value = resultado.address.Neighborhood || '';
        document.getElementById('cp').value = resultado.address.Postal || '';
        document.getElementById('lat').value = resultado.latlng.lat || '';
        document.getElementById('lng').value = resultado.latlng.lng || '';
    }

    function reubicarPin(marker, mapa) {

        const geocodeService = L.esri.Geocoding.geocodeService();

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


