import axios from 'axios'
import router from '../../router'
import {PaginationInit} from '../../helpers/pagination'

export default {
    namespaced: true,
    actions: {
        loadRecords({commit, state, rootState}) {
            const params = {
                page: state.currentPage,
                page_size: state.perPage,
                search: rootState.search.keyword,
            }
            commit('changeLoadingStatus', true)
            commit('setError', '')
            axios.get('/api/currency', {params})
                .then(({data}) => {
                    commit('changeLoadingStatus', false)
                    commit('refreshRecords', data)
                })
                .catch((error) => {
                    commit('changeLoadingStatus', false)
                    commit('setError', `Cannot load currencies! ERROR: ${error.response.data.message}`)
                })
        },
        showCurrency({}, {id}) {
            router.push({ name: 'currency', params: {currencyId: id} })
        },
        loadCurrency({commit, dispatch}) {
            commit('setCurrentCurrency', {})
            commit('changeLoadingStatus', true)
            const currencyId = router.currentRoute.params.currencyId
            axios.get('/api/currency/' + currencyId)
                .then(({data}) => {
                    commit('changeLoadingStatus', false)
                    commit('setCurrentCurrency', data)
                    dispatch('rates/loadRecords', null, {root: true})
                })
                .catch((error) => {
                    commit('changeLoadingStatus', false)
                    commit('setError', `Cannot load currency by ID=${currencyId}! ERROR: ${error.response.data.message}`)
                })
        },
        ...PaginationInit.actions
    },
    mutations: {
        setCurrentCurrency(state, data) {
            state.currentCurrency = data
        },
        ...PaginationInit.mutations
    },
    state: {
        currentCurrency: {},
        ...PaginationInit.state
    },
    getters: {
        getCurrencyData: (state) => (column) => {
            return typeof state.currentCurrency[column] == undefined ? '' : state.currentCurrency[column]
        },
        ...PaginationInit.getters
    },
}