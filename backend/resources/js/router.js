import Router from 'vue-router'
import Dashboard from './components/page/Dashboard'
import Archive from './components/page/Archive'

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/archive',
      name: 'archive',
      component: Archive
    },    
  ]
});