<template>
    <div>
        <vue-headful :title="title + ' | Admin'"/>
        <p class="grey--text text--lighten-1 font-weight-light display-1">{{ headTitle }}</p>
        <div class="pa-4">
            <slot/>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: '',
            headTitle: ''
        }
    }
</script>
