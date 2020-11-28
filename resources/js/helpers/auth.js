export const initToken = () => {
    const token = localStorage.getItem('user-token')
    if (token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
    }
}

export const checkNotAuthenticated = (to, from, next) => {
    if (!localStorage.getItem('user-token')) {
        next()
        return
    }
    next('/')
}

export const checkAuthenticated = (to, from, next) => {
    if (localStorage.getItem('user-token')) {
        next()
        return
    }
    next('/login')
}