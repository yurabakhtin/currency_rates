<template>
<div class="w-100">
    <b-alert variant="danger" :show="getError.length">{{getError}}</b-alert>
    <div v-if="isLoading" class="text-center">
        <b-spinner variant="primary" class="m-5"/>
    </div>
    <strong v-else-if="!getTotalCount">No currencies found.</strong>
    <CurrencyList v-else :currencies="getRecords"/>
</div>
</template>

<script>
import CurrencyList from './CurrencyList'
import {mapGetters, mapActions} from 'vuex'

export default {
    components: {
        CurrencyList
    },
    computed: mapGetters('currencies', ['isLoading', 'getRecords', 'getTotalCount', 'getError']),
    methods: mapActions('currencies', ['loadRecords']),
    mounted() {
        this.loadRecords()
    },
}
</script>