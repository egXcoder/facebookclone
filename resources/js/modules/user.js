import Vue from 'vue';
import axios from "axios"
import { bus } from "../app"

export default {
    namespaced: true,
    state: {
        me: {},
        friends: [],
        messagesMap: {},
    },
    getters: {

    },
    mutations: {
        setMe(state, payload) {
            state.me = payload;
        },
        setFriends(state, payload) {
            state.friends = payload;
        },
        listenToMessageSentEvent(state) {
            window.Echo.channel(`Messenger.${state.me.id}`).listen("MessageSent", function ($event) {
                bus.$emit('MessageSent', $event);
            });
        },
        setMessages(state, { user, messages }) {
            Vue.set(state.messagesMap, user.id, messages);
        },
        loadMoreMessages(state, { user, messages }) {
            Vue.set(state.messagesMap, user.id, messages.concat(state.messagesMap[user.id]))
        },
        addMessage(state, { user, message }) {
            if (state.messagesMap[user.id]) {
                Vue.set(state.messagesMap, user.id, state.messagesMap[user.id].concat([message]));
            }
        }
    },
    actions: {
        fetchMe(context) {
            axios.get('/api/user').then((response) => {
                context.commit('setMe', response.data);
                context.commit('listenToMessageSentEvent');
            })
        },
        fetchFriends(context) {
            axios.get('/api/user/friends').then((response) => {
                context.commit('setFriends', response.data.data);
            })
        },
    }
}