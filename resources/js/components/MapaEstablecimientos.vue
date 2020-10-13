<template>
   <div class="mapa">
        <l-map
        :zoom="zoom"
        :center="center"
        :options="mapOptions"
    >
        <l-tile-layer :url="url" :attribution="attribution" />

        <l-marker>
            <l-tooltip>

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
}
</script>

<style scoped>
    @import 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css';
    .mapa {
        height: 500px;
        width: 100%;
    }
</style>
