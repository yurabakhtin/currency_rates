<template>
<div>
    <b-navbar toggleable="lg" type="dark" variant="success">
        <b-container>
            <b-navbar-brand @click.prevent="routeHome" to="/">
                <b-icon icon="cash"></b-icon>
                Currency Rates
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse v-if="isAuthenticated" id="nav-collapse" is-nav>
                <b-navbar-nav class="mr-auto">
                    <SearchForm/>
                </b-navbar-nav>
            </b-collapse>

            <b-navbar-nav>
                <b-nav-item v-if="isAuthenticated" @click.prevent="logout" to="/logout"><b-icon icon="door-open-fill"></b-icon> Log out</b-nav-item>
                <b-nav-item v-else to="/login"><b-icon icon="key-fill"></b-icon> Log in</b-nav-item>
            </b-navbar-nav>
        </b-container>
    </b-navbar>
</div>
</template>

<script>
import SearchForm from '../parts/SearchForm'
import {mapActions, mapGetters} from 'vuex'

export default {
    components: {
        SearchForm
    },
    methods: {
        ...mapActions('main', ['routeHome']),
        logout: function () {
            this.$store.dispatch('auth/logout')
                .then(() => {
                    this.$router.push('/login')
                })
        }
    },
    computed: mapGetters('auth', ['isAuthenticated']),
}
</script>


