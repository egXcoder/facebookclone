import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Home from './components/main/Home.vue';

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Home
        }
    ]
})