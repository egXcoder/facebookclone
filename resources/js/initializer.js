import Store from "./store";

export default {
    initStore(){
        Store.dispatch('user/fetchMe');
    }
}