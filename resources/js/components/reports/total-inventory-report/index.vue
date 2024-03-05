<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Posting Report</h4>
            </template>

            <template slot="additionals">

                <div class="w-64 sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Store</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="store"
                    >
                        <option v-if="storeList.length >= 1" value="all">
                            All
                        </option>
                        <option v-for="store in storeList" :value="store.id">
                            {{ store.store_name }}
                        </option>
                    </select>
                </div>

                <div class="w-64 sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Date Range</label>
                    <VueDatePicker class="form-control h-9"
                        v-model="date"
                        :color="color"
                        :min-date="minDate"
                        :max-date="maxDate"
                        placeholder="From - To"
                        range
                        range-header-text="From %d To %d"
                        range-input-text="From %d To %d"
                        fullscreen-mobile
                        validate
                    />
                </div>


                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate </button>
                </div>
                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportBidderPerAuction()"> Export </button>
                </div>
            </template>
            <template slot="first_quantity_log" slot-scope="props">
                <span>{{ props.item.first_quantity_log }}</span>
            </template>
        </table-template>
    </div>
</template>
<script>
    import TableTemplate from "../../../Table/index.vue";

    export default {
        components: { TableTemplate },
        props: {
            user: {
                type: String,
                default: null,
            },
        },
    	data() {
    		return {
                fields : {
                    updated_at         : 'Date',
                    store_name	       : 'Branch',
                    listers_name 	   : 'Lister name',
                    name 	            : 'Product',
                    first_quantity_log 		   : 'Total Quantity',
                    first_total_amount 	   : 'Total Amount',
                },
                store: [],
                stores: [],
                link        : '',
                date            : new Date(),
                currentDate     : new Date(),
                params: {
                    from     : null,
                    to       : null,
                    stores   : null,
                },
    		}
    	},

        created() {
            this.activeUser();
        },


        computed: {
            minDate () {
                return new Date(
                    this.currentDate.getFullYear() - 1,
                    this.currentDate.getMonth(),
                    this.currentDate.getDate()
                );
            },
            maxDate () {
                return new Date(
                    this.currentDate.getFullYear() + 1,
                    this.currentDate.getMonth(),
                    this.currentDate.getDate(),
                );
            },

            storeList() {
                return this.stores.filter((store) => {
                    return store.store_type
                        .toLowerCase()
                        .includes('retail');
                });
            },
        },

        watch : {
            'date'() {
                this.params.from = this.date.start ? this.date.start : null;
                this.params.to = this.date.end ? this.date.end : null;
            },

            store() {
             this.params.store = this.store ? this.store : null;
            },
        },

    	methods: {

            activeUser() {
                axios
                    .get("/users/" + this.user, {
                        params: {
                            relations: ["stores"],
                        },
                    })
                    .then((response) => {
                        this.stores = response.data.stores;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            getStore() {
                axios.get("/stores").then(({ data }) => {
                    this.stores = data;
                });
            },

			generate() {
                this.link = 'retail/generate-total-inventory-report'
                app.$emit('reload');
			},

            exportBidderPerAuction() {
                let link = 'retail/total-inventory-report?'

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
    	}
    }
</script>
