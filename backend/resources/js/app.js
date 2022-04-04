require('./bootstrap');

import App from "./components/page/App"

import Vue from 'vue';


import router from './router'
import VueRouter from 'vue-router';

window.Vue = Vue;
Vue.use(VueRouter);

import * as VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

import vuetify from './vuetify' 

new Vue({
  vuetify,
}).$mount('#app')

import store from "./store"

const app = new Vue({
  el: '#app',
  render: h => h(App),
  store,
  router,
  vuetify, 
  components: {
      App
  },    
});



