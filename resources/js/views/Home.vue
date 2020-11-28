<template>
    <CurrencyIndex v-if="isAuthenticated"/>
    <LoginForm v-else/>
</template>

<script>
import LoginForm from '../views/LoginForm'
import CurrencyIndex from '../views/CurrencyIndex'
import {mapGetters} from 'vuex'

export default {
    components: {
        LoginForm, CurrencyIndex
    },
    computed: mapGetters('auth', ['isAuthenticated']),
}
</script>