export default {
    namespaced: true,
    actions: {
        filterRates({commit, dispatch}) {
            commit('rates/setPage', 1, {root: true})
            dispatch('rates/loadRecords', null, {root: true})
        },
        resetRates({commit, dispatch}) {
            commit('setDateFrom', '')
            commit('setDateTo', '')
            dispatch('filterRates')
        }
    },
    state: {
        dateFrom: '',
        dateTo: '',
    },
    getters: {
        getDateFrom(state) {
            return {
                dateFrom: state.dateFrom,
                dateTo: state.dateTo,
            }
        }
    },
    mutations: {
        setDateFrom(state, dateFrom) {
            state.dateFrom = dateFrom
        },
        setDateTo(state, dateTo) {
            state.dateTo = dateTo
        },
    },
}