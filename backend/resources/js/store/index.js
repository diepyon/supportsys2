import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        message: '元々の内容',
        inquiries:null,
    },
    mutations: {
        update(state,inquiries) { 

        },
    }
}, );
export default store;
