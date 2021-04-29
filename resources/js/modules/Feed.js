import axios from "axios"

export default {
    namespaced: true,
    state: {
        posts: []
    },
    mutations: {
        setPosts(state, payload) {
            state.posts = payload;
        },
        pushPost(state, payload) {
            state.posts.push(payload);
        },
        unshiftPost(state, payload) {
            state.posts.unshift(payload);
        }
    },
    actions: {
        fetchPosts(context) {
            axios.get("api/posts/feed").then((response) => {
                context.commit('setPosts', response.data.data);
            });
        }
    }
}