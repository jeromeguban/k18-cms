<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Delivery Reports</h4>
            </template>

            <!-- <template slot="additionals">
                <tr>
                    <th>Total:</th>
                    <th>{{ totalOrderAmount | moneyFormat }}</th>
                </tr>
            </template> -->

            <template slot="total_order_amount" slot-scope="props">
                <span class="font-medium">{{ props.item.total_order_amount | moneyFormat }}</span>
            </template>

            <template slot="shipmates_fee" slot-scope="props">
                <span class="font-medium">{{ getShipmatesFee(props.item.courier_details) | moneyFormat }}</span>
            </template>

            <template slot="shipping_fee" slot-scope="props">
                <span class="font-medium">{{ props.item.shipping_fee | moneyFormat }}</span>
            </template>

            <template slot="customer" slot-scope="props">
                <span class="font-medium">{{ props.item.customer_firstname }}, {{ props.item.customer_lastname }}</span>
            </template>

            <template slot="total_amount" slot-scope="props">
                <span class="font-medium">{{ getTotalAmount(props.item) | moneyFormat }}</span>
            </template>

            <template slot="delivery_type" slot-scope="props">
                <span class="font-medium">{{ getDeliveryType(props.item) }}</span>
            </template>

            <template slot="receivable_amount" slot-scope="props">
                <span class="font-medium">{{ getReceivableAmount(props.item) | moneyFormat }}</span>
            </template>

            <template slot="payable_amount" slot-scope="props">
                <span class="font-medium">{{ getPayableAmount(props.item) | moneyFormat }}</span>
            </template>
            

            <template slot="additionals">

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
        </table-template>
    </div>
</template>
<script>
    import TableTemplate from "../../../Table";

    export default {
        components: { TableTemplate },
    	data() {
    		return {
                fields : {
                    id                  :'Order Number',
                    order_date          :'Date',
                    customer            : 'Customer Name',
                    address             : 'Address',
                    total_order_amount  : 'Total Order Amount',
                    shipping_fee        : 'Shipping Fee',
                    shipmates_fee        : 'Shipmates Fee',
                    total_amount        : 'Total Amount',
                    delivery_type       : 'Delivery Type',
                    receivable_amount   : 'Receivable Amount',
                    payable_amount      : 'Payable Amount',
                },
                link        : '',
                date            : new Date(),
                currentDate     : new Date(),
                totalOrderAmount: 0,
                totalShippingFee: 0,
                totalShipmatesFee: 0,
                totalAmount: 0,
                totalReceivableAmount: 0,
                totalPayableAmount: 0,

                params: {
                    from     : null,
                    to       : null,
                },
                
    		}
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
            totalOrderAmount() {
                return this.items.reduce((total, item) => total + parseFloat(item.total_order_amount), 0);
            },
        },

        watch : {
            'date'() {
                this.params.from = this.date.start ? this.date.start : null;
                this.params.to = this.date.end ? this.date.end : null;
            },
        },

    	methods: {

			generate() {
                this.link = 'delivery-reports-generate'
                app.$emit('reload');
			},

            exportBidderPerAuction() {
                let link = 'delivery-reports-export?'

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

            getShipmatesFee(courier_details) {
                return JSON.parse(courier_details).fees.shipmates_fee;
            },

            getTotalAmount(item) {
                return parseFloat(item.total_order_amount) + parseFloat(item.shipping_fee);
            },

            getDeliveryType(item) {
                const courierDetails = JSON.parse(item.courier_details)

                if (courierDetails && courierDetails.cash_on_delivery == true) {
                    return 'COD';
                } else {
                    return 'Regular';
                }
            },

            getReceivableAmount(item) {
                const courierDetails = JSON.parse(item.courier_details)

                let receivable_amount = courierDetails && courierDetails.cash_on_delivery == true ? parseFloat(item.total_order_amount) : 0;

                return receivable_amount
            },

            getPayableAmount(item) {
                const courierDetails = JSON.parse(item.courier_details)

                return (!courierDetails.cash_on_delivery) ? parseFloat(courierDetails.fees.shipmates_fee) + parseFloat(item.shipping_fee) : 0;
            },
    	}
    }
</script>
