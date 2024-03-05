<template>
<div class="w-full">
    <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
        <template slot="label">
            <h4>List of Auction Access Requests</h4>
        </template>

        <template slot="additionals">

            <div class="flex flex-col w-full pr-1 mr-2">
                <label class="text-2xs font-semibold">Filter By</label>
                <multiselect v-model="filter" :options="filters" label="name"
                    customMaxWidth="100%"></multiselect>
            </div>

            <div v-if="params.filter=='Auction'" class="flex flex-col w-full pr-1 mr-2">
                <label class="text-2xs font-semibold">Auction</label>
                <multiselect v-model="auction" :options="auctions" :custom-label="getAuctionLabel"
                    name="getAuctionLabel" customMaxWidth="100%"></multiselect>
            </div>
            <div v-if="params.filter=='Auction'" class="flex flex-row 2xl:w-full mt-2 sm:mt-2">
                <div class="flex justify-between items-center">
                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary" @click.prevent="reset()" >Reset</button>
                </div>

                <div class="flex justify-between items-center">
                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary ml-2" @click.prevent="exportAuctionAccessRequest()" >Export</button>
                </div>
            </div>

        </template>

        <template slot="search-filter">

            <div class="sm:flex items-center sm:mr-4">
                <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter</label>
                <select id="tabulator-html-filter-field"
                    class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="params.filter_customer">
                    <option v-for="(filter_customer) in filter_customers" :value="filter_customer">{{ filter_customer }}</option>
                </select>
            </div>

        </template>

        <template slot="name" slot-scope="props">
          <span class="font-medium">{{ props.item.name }}</span>
        </template>
        <template slot="id" slot-scope="props">
            <a v-if="!props.item.status || props.item.status == 'Declined'" class="flex items-center mr-3" href="javascript:;" @click.prevent="approve(props.item.id)" v-can="'create.auction-access-request'" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Approve
            </a>
            <a v-if="!props.item.status || props.item.status == 'Approved'" class="flex items-center mr-3" href="javascript:;" @click.prevent="decline(props.item.id)" v-can="'create.auction-access-request'" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Decline
            </a>

            <a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="remove(props.item.id)" v-can="'delete.auction-access-request'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
            </a>
        </template>
    </table-template>
</div>
</template>
<script>
import TableTemplate from "../../Table";
export default {
components: { TableTemplate},
    data() {
        return {
            fields: {
                auction_number      : 'Auction Number',
                customer_firstname  : 'First Name',
                customer_lastname   : 'Last Name',
                mobile_no           : 'Mobile No.',
                email               : 'Email',
                status              : 'Status',
                evaluated_at        : 'Date Evaluated',
                id                  : 'Actions',
            },
            print_link      : '',
            auction         : null,
            auctions        : [],

            filter_customers: [
                'First Name',
                'Last Name',
                'Email',
                'Mobile No'
            ],
            filter_customer : null,

            filter          : null,
            filters         : [
                {
                    name  : 'Auction',
                    value : 'Auction',
                }
            ],
            link            : '',

            params: {
                filter: null,
                filter_customer: null,
                auction_id: null,
            },
        }
    },

    created() {
		this.getAuctions();
	},

    watch: {
        'filter'() {
            this.params.filter = this.filter.value;
            this.getAuctions();
        },

        'filter_customer'() {
            this.params.filter_customer = this.filter_customer;
        },

        'auction'() {
            this.params.auction_id = this.auction.auction_id;
            this.link = '/auction-access-requests';
            app.$emit('reload');
        }
    },

    methods : {
        approve(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to Approve this Customer?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Block'
                })
                .then(confirmed => {
                    axios.post(`/auctions/access-requests/${id}/approved`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You successfully approved a Customer',
                         title: 'Success'
                         });
                        // Reload page
                        app.$emit('reload');
                    })
                    .catch();
                }).catch();
        },

        decline(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to Decline this Customer?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Block'
                })
                .then(confirmed => {
                    axios.post(`/auctions/access-requests/${id}/declined`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You successfully declined a Customer',
                         title: 'Success'
                         });
                        // Reload page
                        app.$emit('reload');
                    })
                    .catch();
                }).catch();
        },

        remove(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to remove the Request?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Delete'
                })
                .then(confirmed => {
                    axios.delete(`/auction-access-requests/${id}`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You Successfully Removed the Request',
                         title: 'Success'
                         });
                        // Reload page
                     app.$emit('reload');
                    })
                    .catch();
                }).catch();
        },

        getAuctions() {
            axios.get('/auctions')
            .then(({data})=>{
                this.auctions =data;
            });
        },

        getAuctionLabel({auction_number, name}) {
            return `${auction_number} - ${name}`;
        },

        reset() {
            this.params.auction_id = null;
            this.auction = null;
            this.params.filter = null;
            this.filter = null;
            this.params.filter_customer = null;
            this.filter_cutsomer = null;
            app.$emit('reload');
         },

         exportAuctionAccessRequest() {
                let print_link = 'export-auction-access-requests?'

                let parameters = Object.keys(this.params)
                                        .filter(parameter => {
                                            return this.params[parameter]
                                        })
                                        .map(parameter => {
                                            return `${parameter}=${this.params[parameter]}`
                                        })
                                        .join("&")

                window.open(print_link + parameters , "_blank")
         },
    },

}
</script>
