require('./bootstrap');

import App from "./components/page/App"

import Vue from 'vue';

import router from './router'
import VueRouter from 'vue-router';

window.Vue = Vue;
Vue.use(VueRouter);


import * as VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

import vuetify from './vuetify' // これを追加



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



