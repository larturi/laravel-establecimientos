<template>
    <div class="container my-5">
        <h2>Restaurants</h2>
        <div class="row">
            <div class="col-md-4 mt-4" v-for="restaurant in this.restaurantes" v-bind:key="restaurant.id">
                <div class="card">
                    <img :src="`storage/${restaurant.imagen_principal}`" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title text-primary font-weight-bold">
                            {{ restaurant.nombre }}
                        </h3>
                        <p class="card-text">{{ restaurant.direccion }}</p>
                        <p class="card-text">
                            <span class="font-font-weight-bold">Horario:</span>
                            {{ restaurant.apertura }} - {{ restaurant.cierre }}
                        </p>
                        <router-link :to="{name:'establecimiento', params: {id: restaurant.id} }">
                            <a class="btn btn-primary btn-block" href="">Ver Lugar</a>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    mounted() {
        axios.get('/api/categorias/restaurant')
           .then(respuesta => {
               this.$store.commit("AGREGAR_RESTAURANTES", respuesta.data);
           });
    },
    computed: {
        restaurantes() {
            return this.$store.state.restaurantes;
        }
    },

}
</script>
