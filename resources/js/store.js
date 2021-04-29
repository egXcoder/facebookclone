import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import User from './modules/User';
import Feed from './modules/Feed';

export default new Vuex.Store({
    state: {

    },
    mutations: {

    },
    modules: {
        User,
        Feed
    }
})