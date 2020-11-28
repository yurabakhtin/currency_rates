<template>
    <div>
        <b-nav-form @submit.prevent="searchCurrencies">
            <b-form-input size="sm" class="mr-sm-2" placeholder="Search currency..." v-model="keyword"></b-form-input>
            <b-button size="sm" class="my-2 my-sm-0" type="submit" variant="dark"><b-icon icon="search"></b-icon> Search</b-button>
        </b-nav-form>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
import {computedFormInputs} from '../../helpers/form'

export default {
    methods: mapActions('search', ['searchCurrencies']),
    computed: computedFormInputs('search', 'keyword'),
}
</script>