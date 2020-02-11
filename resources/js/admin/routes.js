import VueRouter from "vue-router";

export default new VueRouter({
    mode: 'history',
    base: 'admin',
    routes: [
        { path: '/dashboard', component: require('./views/Dashboard').default },
        { path: '/users', component: require('./views/Users').default },
        { path: '/admins', component: require('./views/Admins').default },
        { path: '/categories', component: require('./views/Categories').default },
    ]
});
