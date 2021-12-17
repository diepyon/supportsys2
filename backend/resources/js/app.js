require('./bootstrap');

import App from "./components/page/App"

import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './router'



import vuetify from './vuetify' // これを追加

window.Vue = Vue;
Vue.use(VueRouter);

new Vue({
  vuetify,
}).$mount('#app')


const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),//なにこれ
    
    vuetify, 

    components: {
        App
    },       
});



