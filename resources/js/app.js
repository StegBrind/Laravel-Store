require('./bootstrap');

import Vue from 'vue';

Vue.component('conversation-component', require('./components/ConversationComponent.vue').default);
Vue.component('product-component', require('./components/ProductComponent.vue').default);

const app = new Vue({
    el: '#vue-app',
});