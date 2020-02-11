<template>
    <component v-bind:is="this.$store.getters.asyncViewContent"/>
</template>

<script>
    export default {
        created() {
            this.$store.commit('CHANGE_ROUTER_LINK_CLICKED');
            // Finishes loading progress
            this.$Progress.finish();
        }
    }
</script>