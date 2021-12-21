import Router from 'vue-router'
import Dashboard from './components/page/Dashboard'

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
  ]
});