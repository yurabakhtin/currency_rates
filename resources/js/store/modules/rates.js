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
                    commit('setChartData', {...data, label: rootState.currencies.currentCurrency.code})
                })
                .catch((error) => {
                    commit('changeLoadingStatus', false)
                    commit('setError', `Cannot load rates! ERROR: ${error.response.data.errors}`)
                })
        },
        ...PaginationInit.actions
    },
    mutations: {
        setChartData(state, {data, label}) {
            state.chartData = {
                labels: data.map(rate => rate.date).reverse(),
                datasets: [{
                    label,
                    backgroundColor: '#38c172',
                    data: data.map(rate => rate.value).reverse(),
                }]
            }
        },
        ...PaginationInit.mutations
    },
    state: {
        chartData: {},
        ...PaginationInit.state,
    },
    getters: {
        getChartData(state) {
            return state.chartData
        },
        ...PaginationInit.getters,
    },
}