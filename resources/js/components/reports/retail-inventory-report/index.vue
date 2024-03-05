<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Detailed Inventory Report</h4>
            </template>

            <template slot="additionals">
                <div class="overflow-x-auto scrollbar-hidden mt-6">
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <div class="w-full">
                            <div class="sm:flex items-center sm:mr-4">
                                <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Store</label>
                                <select id="tabulator-html-filter-field"
                                    class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                                    v-model="store">
                                    <option v-if="storeList.length >= 1" value="all">All</option>
                                    <option v-for="(store) in storeList" :value="store.id">{{ store.store_name }}
                                    </option>
                                </select>

                                <div class="w-full ml-4 sm:flex items-center sm:mr-4">
                                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Quantity</label>
                                    <select id="tabulator-html-filter-field"
                                        class="form-select mr-2 w-full sm:w-32 2xl:w-full mt-2 sm:mt-0"
                                        v-model="params.filter_quantity">
                                        <option v-for="(filter_quantity) in filter_quantities" :value="filter_quantity">
                                            {{ filter_quantity }}</option>
                                    </select>
                                </div>

                                <div class="w-full sm:flex items-center sm:mr-4">
                                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Status</label>
                                    <select id="tabulator-html-filter-field"
                                        class="form-select mr-2 w-full sm:w-32 2xl:w-full mt-2 sm:mt-0"
                                        v-model="params.filter_status">
                                        <option v-for="(filter_status) in filter_statuses" :value="filter_status">
                                            {{ filter_status }}</option>
                                    </select>
                                </div>

                                <div class="mt-2 xl:mt-0 mr-3 ml-5">
                                    <button id="tabulator-html-filter-go" type="button"
                                        class="btn btn-primary w-full sm:w-16" @click.prevent="reset()">Reset</button>
                                </div>

                                <div class="flex justify-between items-center py-4 mr-3 ml-2">
                                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportData()"> Export
                                    </button>
                                </div>

                                <div class="flex justify-between items-center py-4 mr-3 ml-2">
                                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </table-template>
    </div>
</template>
<script>
import TableTemplate from "../../../Table";
export default {
    components: { TableTemplate },
    data() {
        return {
            fields: {
                store_company_code: 'Store Code',
                store_name: 'Store Name',
                name: 'Item Name',
                sku: 'SKU',
                quantity: 'Quantity',
                unit_price: 'Price',
                suggested_retail_price: 'Retail Price',
                published_date: 'Published Date',

            },
            link: '',
            store: null,
            stores: [],
            category: 'Retail',
            filter_quantities: [
                'Without Zero Quantity',
                'With Zero Quantity'
            ],
            filter_quantity: null,
            filter_statuses: [
                '',
                'Published',
                'Unpublished'
            ],
            filter_status: null,
            params: {
                store: null,
                filter_quantity: 'Without Zero Quantity',
                filter_status: null
            },
        }
    },

    computed: {
        storeList() {
            return this.stores.filter(store => {
                return store.store_type.toLowerCase().includes(this.category.toLowerCase())
            })
        }
    },

    created() {
        this.getStore();
    },

    watch: {

        'store'() {
            this.params.store = this.store ? this.store : null;
            app.$emit('reload');
        },

        'filter_quantity'() {
            this.params.filter_quantity = this.filter_quantity ? this.filter_quantity : null;
            app.$emit('reload');
        },

        'filter_status'() {
            this.params.filter_status = this.filter_status ? this.filter_status : null;
            app.$emit('reload');
        },

    },

    methods: {
        getStore() {
            axios.get('/stores')
                .then(({ data }) => {
                    this.stores = data;
                });
        },

        reset() {

            this.store = null;
            app.$emit('reload');

        },

        exportData() {

            let link = 'retail/inventory-report?'

            let parameters = Object.keys(this.params)
                .filter(parameter => {
                    return this.params[parameter]
                })
                .map(parameter => {
                    return `${parameter}=${this.params[parameter]}`
                })
                .join("&")

            window.open(link + parameters, "_blank")
        },

        generate() {
            this.link = 'retail/generate-inventory-report'
            app.$emit('reload');
        },

    }
}
</script>
