import axios from 'axios'
import {PaginationInit} from '../../helpers/pagination'

export default {
    namespaced: true,
    actions: {
        loadRecords({commit, state, rootState}) {
            const params = {
                page: state.currentPage,
                page_size: state.perPage,
                date_from: rootState.filter.dateFrom,
                date_to: rootState.filter.dateTo,
            }
            commit('changeLoadingStatus', true)
            commit('setError', '')
            axios.get('/api/currency/' + rootState.currencies.currentCurrency.id + '/rate', {params})
                .then(({data}) => {
                    commit('changeLoadingStatus', false)
                    commit('refreshRecords', data)
                })
                .catch((error) => {
                    commit('changeLoadingStatus', false)
                    commit('setError', `Cannot load rates! ERROR: ${error.response.data.errors}`)
                })
        },
        ...PaginationInit.actions
    },
    mutations: PaginationInit.mutations,
    state: PaginationInit.state,
    getters: PaginationInit.getters,
}