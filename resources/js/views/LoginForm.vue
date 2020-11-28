<template>
<div class="login-form">
    <b-card header="Log in">
        <b-card-text>
            <b-alert variant="danger" show v-for="error in getErrors" :key="error.id">{{error}}</b-alert>
            <b-form @submit.prevent="login">
                <b-form-group label="Email address:" label-for="email">
                    <b-form-input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        placeholder="Enter email"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Password:" label-for="password">
                    <b-form-input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        placeholder="Enter password"
                    ></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="primary">Log in</b-button>
            </b-form>
        </b-card-text>
    </b-card>
</div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    computed: mapGetters('auth', ['getErrors']),
    methods: {
        login: function () {
            const {email, password} = this
            this.$store.dispatch('auth/login', {email, password}).then(() => {
                this.$router.push('currencies')
            })
        }
    },
}
</script>

<style scoped>
.login-form {
    margin: auto;
}
</style>