import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        message: '元々の内容',
        inquiries: null,
    },
    mutations: {
        update(state, inquiries) {

        },
        checkLogin(state, userInfo) {
            //vuexに格納
            state.id = userInfo.id
            state.name = userInfo.name
            state.email = userInfo.email
            //state.token = userInfo.token

            //更新時にも残すようにローカルストレージに格納
            localStorage.setItem("id", state.id)
            //localStorage.setItem("token", state.token)
            localStorage.setItem("userName", state.name)
            localStorage.setItem("userEmail", state.email)
        },
    }
}, );
export default store;
