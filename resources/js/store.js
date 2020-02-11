import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        asyncViewContent: null,
        routerLinkClicked: false
    },
    getters: {
        asyncViewContent: state => {
            return state.asyncViewContent;
        },
        routerLinkClicked: state => {
            return state.routerLinkClicked;
        }
    },
    mutations: {
        SET_ASYNC_VIEW_CONTENT: (state, payload) => {
            state.asyncViewContent = payload;
        },
        CHANGE_ROUTER_LINK_CLICKED: state => {
            state.routerLinkClicked = !state.routerLinkClicked;
        }
    }
});