import axios from "axios"

export default {
    namespaced: true,
    state: {
        me: {},
        friends:[],
    },
    getters: {

    },
    mutations: {
        setMe(state, payload) {
            state.me = payload;
        },
        setFriends(state,payload){
            state.friends = payload;
        }
    },
    actions: {
        fetchMe(context) {
            axios.get('/api/user').then((response) => {
                context.commit('setMe', response.data);
            })
        },
        fetchFriends(context) {
            axios.get('/api/user/friends').then((response) => {
                context.commit('setFriends', response.data.data);
            })
        }
    }
}