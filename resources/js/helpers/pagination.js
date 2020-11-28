export const PaginationInit = {
    actions: {
        changePage({commit, dispatch}, page) {
            commit('setPage', page)
            dispatch('loadRecords')
        },
        setPerPage({commit, dispatch}, page) {
            commit('setPerPage', page)
            dispatch('changePage', 1)
        },
    },
    state: {
        loading: true,
        totalCount: 0,
        perPage: 10,
        currentPage: 1,
        records: [],
        error: '',
    },
    getters: {
        isLoading(state) {
            return state.loading
        },
        getTotalCount(state) {
            return state.totalCount
        },
        getPerPage(state) {
            return state.perPage
        },
        getCurrentPage(state) {
            return state.currentPage
        },
        getRecords(state) {
            return state.records
        },
        getError(state) {
            return state.error
        },
    },
    mutations: {
        changeLoadingStatus(state, status) {
            state.loading = status
        },
        setPage(state, page) {
            state.currentPage = page
        },
        setPerPage(state, perPage) {
            state.perPage = perPage
        },
        refreshRecords(state, data) {
            state.records = data.data
            state.totalCount = data.total
            state.perPage = data.per_page
        },
        setError(state, error) {
            state.error = error
        },
    }
}

export default {
    getters: {
        getPerPage(state, getters) {
            return getters[this.module + '/getPerPage']
        },
        getTotalCount(state, getters) {
            return getters[this.module + '/getTotalCount']
        },
        getCurrentPage(state, getters) {
            return getters[this.module + '/getCurrentPage']
        }
    },
    methods: {
        changePage (dispatch, payload) {
            return dispatch(this.module + '/changePage', payload)
        },
        setPerPage (dispatch, payload) {
            return dispatch(this.module + '/setPerPage', payload)
        }
    }
}