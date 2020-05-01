<template>
    <table>
        <thead>
        <tr>
            <th v-for="column in columns_">
                <span>{{column.label}}</span>
                <a href="#" @click="sortBy(column, 'DESC')" v-show="column.sortable && column.direction != 'DESC'">down</a>
                <a href="#" @click="sortBy(column, 'ASC')" v-show="column.sortable && column.direction != 'ASC'">up</a>
            </th>
        </tr>
        </thead>
        <tbody v-if="loading">
            <tr>
                <td colspan="100%">Chargement...</td>
            </tr>
        </tbody>
        <draggable v-else v-model="data_" tag="tbody" @end="dragend" :disabled="!draggable">
            <tr v-for="row in data_">
                <td v-for="column in columns_" v-html="getCellValue(row, column)"></td>
            </tr>
        </draggable>
    </table>
</template>

// https://michaelnthiessen.com/avoid-mutating-prop-directly/

// we pass data down the the component tree using props
// A parent component will use props to pass data down to it's children components

// Only the component can change it's own state
// Only the parent of the component can change the props


<script>
    import draggable from "vuedraggable";
    import axios from "axios";

    export default {
        name: 'v-table',
        props: {
            data: {type: [Array, Object], default: () => []},
            columns: Array,
            draggable: {type: Boolean, default: false},
            url: {type: String, default: null},
        },
        data() {
            return {
                loading: false,
                columns_: [],
                data_: [] // real name => data_vue_compliant_coz_mutating_prop_sux
            }
        },
        created() {
            const defaultColumnDef = {
                key: null,
                label: null,
                sortable: false,
                direction: null
            };

            this.columns_ = this.columns.map(column => {
                return {...defaultColumnDef, ...column};
            });

            if (this.url) { // async loading
                this.loading = true;
            } else {
                this.loading = false;
                this.data_ = this.data;
            }
        },
        mounted() {
            axios
                .get(this.url)
                .then(response => {
                    this.data_ = response.data.bpi;
                    this.loading = false;
                });
        },
        methods: {

            getCellValue(row, column) {
                const property_path = column.key;
                return row[property_path];
            },

            sortBy(column, direction) {
                // one column can be sorted at same time
                for (let column of this.columns_) {
                    column.direction = null;
                }

                column.direction = direction;
            },

            dragend() {
                console.log(this.data_);
            }
        },
        components: {
            draggable
        }
    }
</script>

<style lang="scss" scoped>
    @import "~bootstrap/scss/_functions.scss";
    @import "~bootstrap/scss/_variables.scss";
    @import "~bootstrap/scss/_mixins.scss";

    table {
        background-color: $gray-100
    }

</style>