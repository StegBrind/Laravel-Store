import Vue from 'vue';
import Vuetify from "vuetify";
import VueRouter from 'vue-router';
import router from './routes';
import App from "./components/App";

Vue.use(Vuetify);
Vue.use(VueRouter);

Vue.component('admin-content', require('./components/AdminContent').default);
Vue.component('vue-headful', require('vue-headful').default);
Vue.component('stats-component', require('./components/StatsComponent').default);
Vue.component('notification-component', require('./components/NotificationComponent').default);

const app = new Vue({
    router,
    vuetify: new Vuetify({icons: {iconfont: 'md'}}),
    el: '#admin-app',
    render: (h) => h(App)
});
