import axios from "axios"

export default {
    namespaced: true,
    state: {
        me: {}
    },
    getters: {

    },
    mutations: {
        setMe(state, payload) {
            state.me = payload;
        }
    },
    actions: {
        fetchMe(context) {
            axios.get('/api/user').then((response) => {
                context.commit('setMe', response.data);
            })
        }
    }
}