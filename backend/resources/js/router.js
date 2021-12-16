import Router from 'vue-router'

import Home from './components/page/Home'

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/home',
      name: 'home',
      component: Home
      },    
  ]
});