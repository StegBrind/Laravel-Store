<template>
    <div>
        <vue-progress-bar/>
        <router-view :key="$route.path"/>
    </div>
</template>

<script>
    export default {
        mounted() {
            // Starts Loading process
            this.$router.beforeEach((to, from, next) => {
                this.$Progress.start();
                this.$Progress.set(20);
                next();
            });
        }
    }
</script>