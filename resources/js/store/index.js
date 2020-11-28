import Vue from 'vue'
import Vuex from 'vuex'
import main from './modules/main'
import auth from './modules/auth'
import currencies from './modules/currencies'
import rates from './modules/rates'
import search from './modules/search'
import filter from './modules/filter'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {main, auth, currencies, rates, search, filter},
})