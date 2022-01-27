import Router from 'vue-router'
import Dashboard from './components/page/Dashboard'
import Archive from './components/page/Archive'
import RecordPost from './components/page/RecordPost'
import Types from './components/page/Types'
import Customer from './components/page/Customer'
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
    {
      path: '/types',
      name: 'types',
      component: Types
    },
    {
      path: '/customer',
      name: 'customer',
      component: Customer
    },       
  ]
});