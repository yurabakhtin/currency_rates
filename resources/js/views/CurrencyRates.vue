<template>
<div class="w-100">
    <h3>{{getCurrencyData('code')}} {{getCurrencyData('name')}}</h3>
    <b-alert variant="danger" :show="getError.length">{{getError}}</b-alert>
    <b-alert variant="danger" :show="getRatesError.length">{{getRatesError}}</b-alert>
    <FilterRates/>
    <div v-if="isLoading" class="text-center">
        <b-spinner variant="primary" class="m-5"/>
    </div>
    <strong v-else-if="!getCurrencyData('id')">No rates found.</strong>
    <b-table v-else small striped hover bordered head-variant="dark"
             :items="getRecords"
             :fields="fields"
    ></b-table>
    <Pagination module="rates"/>
    <b-button variant="primary" @click="goBack"><b-icon icon="arrow-left-circle-fill"></b-icon> Back</b-button>
</div>
</template>

<script>
import Pagination from '../components/parts/Pagination'
import FilterRates from '../components/parts/FilterRates'
import router from '../router'
import {mapActions, mapGetters} from 'vuex'

export default {
    data() {
        return {
            fields: [
                {
                    key: 'date',
                    sortable: true,
                    label: 'ID'
                },
                {
                    key: 'denomination',
                    sortable: false
                },
                {
                    key: 'value',
                    sortable: true,
                }
            ],
        }
    },
    components: {
        Pagination, FilterRates
    },
    methods: {
        ...mapActions('main', ['changePageTitle']),
        ...mapActions('currencies', ['loadCurrency']),
        goBack() {
            router.push({ name: 'home' })
            this.changePageTitle(this.$store.getters['search/getKeyword'] ? 'search' : 'home')
        }
    },
    computed: {
        ...mapGetters('currencies', ['getCurrencyData', 'getError']),
        ...mapGetters('rates', ['getRecords', 'isLoading']),
        ...mapGetters('rates', {getRatesError: 'getError'}),
        ...mapGetters('search', ['getKeyword'])
    },
    mounted() {
        this.loadCurrency()
        this.changePageTitle('currency')
    },
}
</script>