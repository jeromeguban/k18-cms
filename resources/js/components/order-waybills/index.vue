<template>
<div class="w-full">
    <order-waybill-ready></order-waybill-ready>
	<table-template link="/order-waybills" :fields="fields" :params="params" :addNewBtn="false">
		<template slot="label">
			<h4>List of Order Waybills</h4>
		</template>

		<template slot="name" slot-scope="props">
            <span class="font-medium">{{ props.item.customer_name }}</span>
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
        <template slot="courier_name" slot-scope="props">
            <span class="font-medium">{{ getCourierName(props.item.courier_details) }}</span>
        </template>
        <template slot="courier_details" slot-scope="props">
            <span class="font-medium">{{ checkIfCashOnDelivery(props.item.courier_details) }}</span>
        </template>

        <template slot="id" slot-scope="props">
            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#store-address-edit" @click.prevent="cancelledShipment(props.item.tracking_number, props.index)" v-can="'update.order-waybill'" v-if="'order_details.waybill_status' == 'pending'" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Cancel Shipment
            </a>

            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#tracking-number"  @click.prevent="getTrackingShipment(props.item, props.index)" v-can="'update.order-waybill'" v-if="'order_details.waybill_status' == 'pending'" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Track Shipment
            </a>

            <a class="flex items-center mr-3" href="javascript:;" @click.prevent="getShipment(props.item.tracking_number)" v-can="'update.order-waybill'"  >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Print Waybill
            </a>

            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#order-waybill-create"  @click.prevent="setWayBill(props.item, props.index)" v-can="'update.order-waybill'" v-if="props.item.waybill_status == 'cancelled' || props.item.waybill_status == 'failed_delivery'" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Rebook Shipment
            </a>

            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#order-waybill-edit" @click.prevent="setWayBill(props.item, props.index)" v-can="'update.order-waybill'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit Shipment
            </a>

        	<!-- <a class="flex items-center mr-3" href="javascript:;"
                @click.prevent="printPDF(props.item.id)">
                 <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45">
	                        <path d="M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5 c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5 V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25 S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25 S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25 S33.189,18.792,32.5,18.792z"/>
                </svg> Print PDF
            </a>

            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#waybill-update-status"  @click.prevent="setWayBill(props.item, props.index)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Update Waybill Status
            </a> -->
        </template>
	</table-template>
	<create
        :order_waybill_ready="order_waybill"></create>
    
    <edit 
        :order_waybill_ready="order_waybill"></edit>

    <tracking-shipment
        v-if="order_details"
        @update="clearShipment"
        :order_details="order_details" />

    <waybill-status
        v-if="order_waybill"
        :order_waybill="order_waybill"/>
</div>
</template>
<script>
import TableTemplate from "../../Table";
import Create from "./create";
import Edit from "./edit";
import WaybillStatus from "./waybill-status";
import OrderWaybillReady from "../order-waybill-readys/index";
import TrackingShipment from "../tracking-shipment";

export default {
    components: { TableTemplate, Create, Edit , WaybillStatus, TrackingShipment, OrderWaybillReady },
    data() {
        return {
            fields: {
                order_id 		: 'Order Number',
                order_date      : 'Order Date',
                store_name      : 'Store Name',
                pickup_date     : 'Pickup Date',
                delivered_date  : 'Delivered Date',
                reference_code	: 'Reference Code',
                tracking_number : 'Tracking Number',
                customer_name 	: 'Customer',
                email 			: 'Email',
                total_order_amount : 'Total Order Amount',
                waybill_status  : 'Waybill Status',
                courier_name    : 'Courier',
                courier_details : 'Cash on Delivery',
                id 				: 'Actions'
            },
            order_details : null,
            order_waybill : null,
            index : 0,
            store : null,
            stores :  [],
            link : '',
            params: {
                store: null,
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

        getTrackingShipment(order_details, index) {
            this.order_details = order_details;
            this.index = index;
        },

        setWayBill(order_waybill, index) {
            this.order_waybill = order_waybill;
            this.index = index;
        },

        cancelledShipment(tracking_number, index) {
            this.$modal.dialog({
                message: 'Are you sure you want to Cancelled this Shipment?',
                confirmButton: 'Okay',
                cancelButton: 'Cance l',
                title: 'Cancelled'
            })
            .then(confirmed => {
                axios.post(`/cancel-shipment`, {
                    params: {
                        tracking_number : tracking_number
                    }
                })
                .then(() => {
                    this.$modal.success({
                        message: 'Customer Successfully Cancelled',
                        title: 'Success'
                    });
                    // Reload page
                    app.$emit('reload');
                })
                .catch();
            }).catch();
        },

        clearShipment() {
            this.order_details = null;
            this.index = 0;
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

        getShipment(tracking_number) {
            axios.get(`/get-shipments`, {
                params: {
                    tracking_number : tracking_number
                }
            })
            .then(({data})=>{
                this.link = data;
    
                 window.open(this.link, "_blank")
            })
        },

    }
}
</script>
