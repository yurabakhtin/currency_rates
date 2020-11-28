import router from '../../router'
import {titles} from '../../config'

export default {
    namespaced: true,
    actions: {
        changePageTitle({commit}, title) {
            commit('setPageTitle', title)
            document.title = this.getters['main/getPageTitle'] + ' - ' + window.site_config.app_name
        },
        routeHome({commit, dispatch}) {
            commit('search/setKeyword', '', {root: true})
            commit('currencies/setPage', 1, {root: true})
            dispatch('currencies/loadRecords', null, {root: true})
            router.push({ name: 'home' })
                .catch(() => dispatch('changePageTitle', 'home'))
        }
    },
    state: {
        pageTitle: 'home',
        titles
    },
    mutations: {
        setPageTitle(state, title) {
            state.pageTitle = title
        }
    },
    getters: {
        getPageTitle(state) {
            return state.titles[state.pageTitle] ? state.titles[state.pageTitle] : state.pageTitle;
        },
    },
}