import Router from 'vue-router'
import Dashboard from './components/page/Dashboard'
import Archive from './components/page/Archive'
import RecordPost from './components/page/RecordPost'

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
    {
      path: '/recordpost',
      name: 'recordpost',
      component: RecordPost
    },         
  ]
});