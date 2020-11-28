import Vue from 'vue'
import VueRouter from 'vue-router'
import {checkAuthenticated, checkNotAuthenticated} from '../helpers/auth'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/Home'),
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginForm'),
            beforeEnter: checkNotAuthenticated,
        },
        {
            path: '/currencies',
            name: 'currencies',
            component: () => import('../views/CurrencyIndex'),
            beforeEnter: checkAuthenticated,
        },
        {
            path: '/currency/:currencyId',
            name: 'currency',
            component: () => import('../views/CurrencyRates'),
            beforeEnter: checkAuthenticated,
        }
    ]
})

export default router