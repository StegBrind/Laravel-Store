import VueRouter from "vue-router";
import axios from 'axios';
import store from './store';

const AsyncView = require('./views/AsyncView.vue').default;

export default new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: AsyncView, beforeEnter: preload('/api/index/view') },
        { path: '/login', component: AsyncView, beforeEnter: preload() },
        { path: '/category/:id', component: AsyncView, beforeEnter: preload() },
        { path: '/'}
    ]
});


function preload(apiUrl = undefined)
{
    return (to, from, next) => {
        if (store.getters.routerLinkClicked) { // prevent the user from multiple clicking on the router-link
            next();
            return;
        }

        store.commit('CHANGE_ROUTER_LINK_CLICKED');

        if (apiUrl == undefined) {
            apiUrl = '/api' + to.path + '/view';
        }

        axios.get(apiUrl)
             .then(response => {
                 store.commit('SET_ASYNC_VIEW_CONTENT', { template: response.data });
                 next();
             });
    };
}
