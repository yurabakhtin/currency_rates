import axios from 'axios'
import {initToken} from '../../helpers/auth';

export default {
    namespaced: true,
    actions: {
        login({commit, dispatch}, user) {
            return new Promise((resolve, reject) => {
                axios.post('api/token', user)
                    .then(response => {
                        commit('setToken', response.data.token)
                        commit('setErrors', [])
                        resolve(response)
                    })
                    .catch(error => {
                        const {data} = error.response
                        commit('setErrors', data.error ? [data.error] : data.errors)
                        reject(error)
                    })
            })
        },
        logout({commit, getters}) {
            return new Promise((resolve, reject) => {
                axios.delete('api/token')
                    .then(response => {
                        commit('setToken', '')
                        commit('setErrors', [])
                        resolve(response)
                    })
                    .catch(error => {
                        commit('setToken', '')
                        reject(error)
                    })
            })
        }
    },
    state: {
        token: localStorage.getItem('user-token') || '',
        errors: []
    },
    mutations: {
        setToken(state, token) {
            state.token = token
            if (token) {
                localStorage.setItem('user-token', token)
            } else {
                localStorage.removeItem('user-token')
            }
            initToken()
        },
        setErrors(state, errors) {
            state.errors = errors
        }
    },
    getters: {
        isAuthenticated: state => !!state.token,
        getErrors: state => state.errors,
    },
}