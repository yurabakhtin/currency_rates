import router from '../../router'

export default {
    namespaced: true,
    actions: {
        searchCurrencies({commit, dispatch}) {
            commit('currencies/setPage', 1, {root: true})
            dispatch('currencies/loadRecords', null, {root: true})
            router.push({ name: 'home' })
                .catch(() => dispatch('main/changePageTitle', 'search', {root: true}))
        }
    },
    state: {
        keyword: '',
    },
    getters: {
        getKeyword(state) {
            return state.keyword
        }
    },
    mutations: {
        setKeyword(state, keyword) {
            state.keyword = keyword
        }
    },
}