import Router from 'vue-router'
import Dashboard from './components/page/Dashboard'
import Archive from './components/page/Archive'
import RecordPost from './components/page/RecordPost'
import Types from './components/page/Types'
import Customer from './components/page/Customer'
import OperatorRegister from './components/page/OperatorRegister'
import Login from './components/page/Login'
export default new Router({
    mode: 'history',
    routes: [{
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
            path: '/archive?page=:pageid',
            name: 'paginatearchive',
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
        {
            path: '/operatorregister',
            name: 'operatorregister',
            component: OperatorRegister
        },        
        {
            path: '/login',
            name: 'login',
            component: Login
        },         
    ]
});
