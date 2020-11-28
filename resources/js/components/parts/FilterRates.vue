<template>
<div>
    <b-form inline @submit.prevent="filterRates">
        <b-input-group>
            <label class="mr-sm-2" for="date_from">From date:</label>
            <b-form-datepicker
                id="date_from"
                v-model="dateFrom"
                reset-button
            ></b-form-datepicker>
        </b-input-group>

        <b-input-group>
            <label class="mr-sm-2" for="date_to">To date:</label>
            <b-form-datepicker
                id="date_to"
                v-model="dateTo"
                reset-button
            ></b-form-datepicker>
        </b-input-group>

        <b-button type="submit" variant="success"><b-icon icon="filter"></b-icon> Filter</b-button>
        <b-button type="button" variant="warning" @click="resetRates"><b-icon icon="backspace"></b-icon> Reset</b-button>
    </b-form>
</div>
</template>

<script>
import {mapActions} from 'vuex';
import {computedFormInputs} from '../../helpers/form'

export default {
    methods: mapActions('filter', ['filterRates', 'resetRates']),
    computed: computedFormInputs('filter', ['dateFrom', 'dateTo']),
}
</script>

<style scoped>
form {
    margin-bottom: 1rem;
}
.input-group, button {
    margin-right: 1rem;
}
</style>