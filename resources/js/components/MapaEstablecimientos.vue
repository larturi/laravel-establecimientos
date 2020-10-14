<template>
   <div class="mapa">
        <l-map
        :zoom="zoom"
        :center="center"
        :options="mapOptions"
    >
        <l-tile-layer :url="url" :attribution="attribution" />

        <l-marker
            v-for="establecimiento in establecimientos"
            v-bind:key="establecimiento.id"
            :lat-lng="obtenerCoordenadas(establecimiento)"
            :icon="iconoEstablecimiento(establecimiento)"
        >
            <l-tooltip>
                <div>
                    {{ establecimiento.nombre }} - {{ establecimiento.categoria.nombre }}
                </div>
            </l-tooltip>
        </l-marker>

    </l-map>
   </div>
</template>

<script>
import { latLng } from 'leaflet';
import { LMap, LTileLayer, LMarker, LTooltip, LIcon } from 'vue2-leaflet';

export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LTooltip,
        LIcon
    },
    data() {
        return {
            zoom: 13,
            center: latLng(-34.606975, -58.445667),
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            attribution:
                '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            currentZoom: 11.5,
            currentCenter: latLng(-34.606975, -58.445667),
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: true
        };
    },
    created() {
        axios.get('api/establecimientos')
            .then(respuesta => {
                this.$store.commit('AGREGAR_ESTABLECIMIENTOS', respuesta.data)
            });
    },
    computed: {
        establecimientos() {
            return this.$store.getters.getEstablecimientos;
        }
    },
    methods: {
        obtenerCoordenadas(establecimiento) {
            return {
                lat: establecimiento.lat,
                lng: establecimiento.lng,
            }
        },
        iconoEstablecimiento(establecimiento) {
            const { slug } = establecimiento.categoria;
            return L.icon({
                iconUrl: `images/iconos/${slug}.png`,
                iconSize: [40, 50]
            });
        }
    },
    watch: {
       "$store.state.categoria": function() {
           axios.get('/api/' + this.$store.getters.getCategoria)
              .then(respuesta => {
                  this.$store.commit('AGREGAR_ESTABLECIMIENTOS', respuesta.data);
              });
       }
    }
}
</script>

<style scoped>
    @import 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css';
    .mapa {
        height: 500px;
        width: 100%;
    }
</style>
