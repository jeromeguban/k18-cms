<template>
    <div class="w-full">
        <table-template link="/abandoned-carts" :params="params" :fields="fields" modalIdentifier="#"
            :addNewBtn="false">
            <template slot="label">
                <h4>List of Abandoned Carts</h4>
            </template>
            <template slot="additionals">

                <!-- <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Date Range</label
                    >
                    <VueDatePicker
                        class="form-control h-9"
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
                <div class="mt-2 xl:mt-0">
                    <button class="btn btn-primary d-paddingTop10"> Export </button>
                </div> -->
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Date Range</label
                    >
                    <VueDatePicker
                        class="form-control h-9"
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

                <div class="mt-2 xl:mt-0">
                    <button
                        id="tabulator-html-filter-go"
                        type="button"
                        class="btn btn-primary w-full sm:w-16"
                        @click.prevent="exportData()"
                    >
                        Export
                    </button>
                </div>
            </template>

            <template slot="search-filter">

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select mr-2 w-full sm:w-32 2xl:w-full mt-2 sm:mt-0" v-model="params.decrypt">
                        <option v-for="(filter) in filters" :value="filter">{{ filter }}</option>
                    </select>
                </div>

            </template>

            <template slot="customer_firstname" slot-scope="props">
                <span class="w-40">{{ props.item.customer_firstname }} {{ props.item.customer_lastname }}</span>
            </template>

            <template slot="name" slot-scope="props">
                <span class="w-60">{{ props.item.name }}</span>
            </template>

            <!-- <template slot="description" slot-scope="props">
                <span class="w-60" v-html="props.item.description"></span>
            </template> -->

            <template slot="posting_quantity" slot-scope="props">
                <span class="w-20" v-html="props.item.posting_quantity"></span>
            </template>

            <template slot="expires_at" slot-scope="props">
                <span class="w-20" v-html="props.item.expires_at"></span>
            </template>

            <template slot="id" slot-scope="props">
                <div class="flex flex-col w-40">
                    <a class="flex mr-3 mt-1" href="javascript:;" data-toggle="modal" data-target="#item-details"
                        @click.prevent="setCart(props.item, props.index)">
                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
                        </svg> Show Item Details
                    </a>

                    <a class="flex mt-2" href="javascript:;" data-toggle="modal" data-target="#cart-action"
                        @click.prevent="setCart(props.item, props.index)" v-can="'update.cart'">
                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path
                                d="M551.991 64H129.28l-8.329-44.423C118.822 8.226 108.911 0 97.362 0H12C5.373 0 0 5.373 0 12v8c0 6.627 5.373 12 12 12h78.72l69.927 372.946C150.305 416.314 144 431.42 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-17.993-7.435-34.24-19.388-45.868C506.022 391.891 496.76 384 485.328 384H189.28l-12-64h331.381c11.368 0 21.177-7.976 23.496-19.105l43.331-208C578.592 77.991 567.215 64 551.991 64zM464 416c17.645 0 32 14.355 32 32s-14.355 32-32 32-32-14.355-32-32 14.355-32 32-32zm-256 0c17.645 0 32 14.355 32 32s-14.355 32-32 32-32-14.355-32-32 14.355-32 32-32zm294.156-128H171.28l-36-192h406.876l-40 192zM272 196v-8c0-6.627 5.373-12 12-12h36v-36c0-6.627 5.373-12 12-12h8c6.627 0 12 5.373 12 12v36h36c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12h-36v36c0 6.627-5.373 12-12 12h-8c-6.627 0-12-5.373-12-12v-36h-36c-6.627 0-12-5.373-12-12z" />
                        </svg> Cart Actions
                    </a>
                </div>
            </template>
        </table-template>
        <item-detail v-if="cart" :cart="cart" />
        <cart-action v-if="cart" :cart="cart" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import ItemDetail from "./item-detail";
import CartAction from "./cart-action";
export default {
    components: { TableTemplate, ItemDetail, CartAction },
    data() {
        return {
            cart: null,
            fields: {
                customer_firstname: 'Customer Name',
                // customer_lastname: 'Last Name',
                mobile_no: 'Mobile No.',
                email: 'Email',
                name: 'Item',
                // description: 'Description',
                quantity: 'Quantity',
                posting_quantity: 'Inventory',
                total_price: 'Total Price',
                expires_at: 'Cart Expiry Date',
                id: 'Actions'
            },
            filters: [
                '',
                'First Name',
                'Last Name',
                'Email',
                'Mobile No'
            ],
            filter: null,
            params: {
                decrypt: null,
            },

            date: new Date(),
            currentDate: new Date(),

            params: {
                category: null,
                status: null,
                for_viewing: null,
                stores: null,
                from: null,
                to: null,
                filter_type: null,
                order_type: null,
            },
        }
    },

    computed: {
        minDate() {
            return new Date(
                this.currentDate.getFullYear() - 1,
                this.currentDate.getMonth(),
                this.currentDate.getDate()
            );
        },
        maxDate() {
            return new Date(
                this.currentDate.getFullYear() + 1,
                this.currentDate.getMonth(),
                this.currentDate.getDate()
            );
        },
    },

    watch : {
        date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
        },
    },


    methods: {
        setCart(cart, index) {
            this.cart = cart;
            this.index = index;
        },

        clearCart() {
            this.cart = null;
            this.index = 0;
        },

        exportData() {

            if(this.params.from && this.params.to){
                let link = 'abandon-carts/export?'

                let parameters = Object.keys(this.params)
                                        .filter(parameter => {
                                            return this.params[parameter]
                                        })
                                        .map(parameter => {
                                            return `${parameter}=${this.params[parameter]}`
                                        })
                                        .join("&")

                window.open(link + parameters, "_blank")
            }
        },



    }
}
</script>
