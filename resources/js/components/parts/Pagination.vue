<template>
<div>
    <b-pagination
        v-if="getTotalCount > 1"
        :value="getCurrentPage"
        :total-rows="getTotalCount"
        :per-page="getPerPage"
        prev-text="Prev"
        next-text="Next"
        @change="changePage"
        align="left"
    ></b-pagination>
    <b-select
        :value="getPerPage"
        :options="pageSizes"
        @change="setPerPage"
        class="per-page"
    ></b-select>
    <div class="clearfix"></div>
</div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {pageSizes} from '../../config'
import Pagination from '../../helpers/pagination'

export default {
    props: ['module'],
    computed: mapState(Pagination.getters),
    methods: mapActions(Pagination.methods),
    data() {
        return {
            pageSizes
        }
    }
}
</script>

<style scoped>
.b-pagination {
    float: left;
    margin: 1px 1rem 0 0;
}
.per-page {
    float: right;
    width: auto;
    margin-bottom: 1rem;
}
</style>