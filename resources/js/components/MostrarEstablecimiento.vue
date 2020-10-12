<template>
    <div>
        <div class="container my-4">
            <h1 class="text-center mb-5">{{ establecimiento.nombre }}</h1>

            <div class="row align-items-start">
                <div class="col-md-8">
                    <img :src="`../storage/${establecimiento.imagen_principal}`"
                         alt="Imagen establecimiento"
                         class="img-fluid">

                    <p class="mt-4">{{ establecimiento.descripcion }}</p>
                </div>

                <aside class="col-md-4">
                    <div>
                        <mapa-ubicacion></mapa-ubicacion>
                    </div>

                    <div class="p-4 bg-primary">
                        <h2 class="text-center text-white mt-2 mb-4">Mas información</h2>

                        <p class="text-white mt-1">
                            <span class="font-weight-bold">Ubicación: </span>
                            {{ establecimiento.direccion }}
                        </p>

                        <p class="text-white mt-1">
                            <span class="font-weight-bold">Localidad: </span>
                            {{ establecimiento.localidad }} ({{ establecimiento.cp }})
                        </p>

                        <p class="text-white mt-1">
                            <span class="font-weight-bold">Horario: </span>
                            {{ establecimiento.apertura }} - {{ establecimiento.cierre }}
                        </p>

                        <p class="text-white mt-1">
                            <span class="font-weight-bold">Telefono: </span>
                            {{ establecimiento.telefono }}
                        </p>
                    </div>
                </aside>

            </div>

        </div>
    </div>
</template>

<script>
import MapaUbicacion from './MapaUbicacion';

export default {
    components: {
      MapaUbicacion
    },
    mounted() {
        const { id } = this.$route.params;

        axios.get('/api/establecimientos/' + id)
           .then(respuesta => {
               this.$store.commit('AGREGAR_ESTABLECIMIENTO', respuesta.data);
            });
    },
    computed: {
        establecimiento() {
            return this.$store.getters.getEstablecimiento;
        }
    }
}
</script>
