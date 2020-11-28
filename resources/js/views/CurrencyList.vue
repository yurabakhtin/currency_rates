<template>
<div>
    <Pagination module="currencies"/>
    <b-table striped hover bordered head-variant="dark"
             :items="currencies"
             :fields="fields"
             @row-clicked="showCurrency"
    ></b-table>
    <Pagination module="currencies"/>
</div>
</template>

<script>
import Pagination from '../components/parts/Pagination'
import {mapActions} from "vuex";
export default {
    data() {
        return {
            fields: [
                {
                    key: 'internal_id',
                    sortable: true,
                    label: 'ID'
                },
                {
                    key: 'number',
                    sortable: false
                },
                {
                    key: 'code',
                    sortable: true,
                },
                {
                    key: 'name',
                    sortable: true,
                }
            ],
        }
    },
    components: {
        Pagination
    },
    props: {
        currencies: Array
    },
    methods: mapActions('currencies', ['showCurrency'])
}
</script>