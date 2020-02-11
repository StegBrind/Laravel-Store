import './bootstrap';

import App from './components/App';
import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './routes';
import store from './store';
import VueProgressBar from 'vue-progressbar';

Vue.use(VueRouter);
Vue.use(VueProgressBar, {
    thickness: '3px',
    transition: {
        speed: '0.5s',
        opacity: '0.1s',
        termination: 100
    },
    autoFinish: false
});

Vue.component('app-component', require('./components/App.vue').default);
Vue.component('async-view', require('./views/AsyncView.vue').default);
Vue.component('vue-headful', require('vue-headful').default);
Vue.component('conversation-component', () => import('./components/ConversationComponent.vue'));
Vue.component('product-component', () => import('./components/ProductComponent.vue'));


const app = new Vue({
    router,
    store,
    el: '#app',
    render: (h) => h(App)
});
