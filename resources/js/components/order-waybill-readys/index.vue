<template>
    <div class="w-full">
        <table-template link="/orders" :fields="fields" :params="params" modalIdentifier="#order-waybills-create" :addNewBtn="false">
            <template slot="label">
                <h4>List of Ready Order Waybills Creation</h4>
            </template>

            <template slot="additionals">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Store</label>
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="store">
                        <option v-if="stores.length >= 1" value="all">
                            All
                        </option>
                        <option v-for="store in stores" :value="store.id">
                            {{ store.store_name }}
                        </option>
                    </select>
                </div>
            </template>

            <template slot="order_id" slot-scope="props">
                <span class="font-medium">{{ props.item.id }}</span>
            </template>

            <template slot="courier_name" slot-scope="props">
                <span class="font-medium">{{ getCourierName(props.item.courier_details) }}</span>
            </template>

            <template slot="courier_details" slot-scope="props">
                <span class="font-medium">{{ checkIfCashOnDelivery(props.item.courier_details) }}</span>
            </template>

            <template slot="id" slot-scope="props">
                <a v-if="isNotHMR(props.item.courier_details)" class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#order-createwaybill"  @click.prevent="setOrder(props.item, props.index)" v-can="'update.order-waybill'" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Create Waybill
                </a>
            </template>
            
        </table-template>

        <create
            :order_waybill_ready="order_waybill_ready"></create>
    </div>
    </template>
    <script>
    import TableTemplate from "../../Table";
    import Create from "./create";

    export default {
        components: { TableTemplate, Create },
        data() {
            return {
                fields: {
                    order_id         : 'Order Number',
                    order_date       : 'Order Date',
                    store_name       : 'Store Name',
                    reference_code	: 'Reference Code',
                    customer_name 	: 'Contact Person',
                    email 			: 'Email',
                    courier_name    : 'Courier',
                    courier_details : 'Cash on Delivery',
                    id              : 'Actions',
                },
                order_waybill_ready : null,
                index : 0,
                store : null,
                stores :  [],

                params: {
                    store: null,
                    status : 'Delivery',
                    tracking_status : 'none'
                },
            }
        },

        created() {
            this.getStores();
        },
        

        watch: {
            store() {
                this.params.store = this.store ? this.store : null;
                app.$emit("reload");
            },
        },

        methods: {
            printPDF(id) {
                let print_link = `order-waybill-print/${id}`

                window.open(print_link, "_blank");
            },

            isNotHMR(courierDetails) {
                try {
                    let details = JSON.parse(courierDetails);
                    return details.courier !== 'Book by HMR';
                } catch (error) {
                    return true; 
                }
            },

            getTrackingShipment(order_details, index) {
                this.order_details = order_details;
                this.index = index;
            },


            setOrder(order_waybill_ready, index) {
                console.log(order_waybill_ready, index);
                this.order_waybill_ready = order_waybill_ready;
                this.index = index;
            },


            getCourierName(courier_details) {
                return JSON.parse(courier_details).courier;
            },

            checkIfCashOnDelivery(courier_details) {
                return JSON.parse(courier_details).cash_on_delivery ? "Yes" : "No";
            },

            getStores() {
                axios.get("/stores").then(({ data }) => {
                    this.stores = data;
                });
            },



        }
    }
    </script>
