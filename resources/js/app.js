/**
 * Initialize JavaScript for frontend SPA
 */

require('./bootstrap');

import Vue from 'vue'
import {BootstrapVue, PaginationPlugin, SpinnerPlugin, IconsPlugin} from 'bootstrap-vue'
import App from './components/App'
import router from './router'
import store from './store'
import {initToken} from './helpers/auth'

initToken();

Vue.use(BootstrapVue)
Vue.use(PaginationPlugin)
Vue.use(SpinnerPlugin)
Vue.use(IconsPlugin)

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
