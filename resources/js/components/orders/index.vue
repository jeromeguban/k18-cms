<template>
    <div class="w-full">
        <table-template
            link="/orders"
            :params="params"
            :fields="fields"
            :other_fields="other_fields"
            modalIdentifier="#"
            :addNewBtn="false"
        >
            <template slot="label">
                <h4>List of Orders</h4>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{
                    props.item.customer_firstname
                }}</span>
            </template>

            <template slot="order_number" slot-scope="props">
                <span class="font-medium">{{ props.item.id }}</span>
            </template>

            <template slot="additionals">
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
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Filter</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="params.decrypt"
                    >
                        <option v-for="filter in filters" :value="filter">
                            {{ filter }}
                        </option>
                    </select>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Status</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="status"
                    >
                        <option value="Pickup">Pickup</option>
                        <option value="Delivery">Delivery</option>
                        <option value="Transacted">Transacted</option>
                        <option value="Untransacted">Untransacted</option>
                    </select>
                </div>

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

                <div class="mt-2 xl:mt-0">
                    <button
                        id="tabulator-html-filter-go"
                        type="button"
                        class="btn btn-primary w-full sm:w-16"
                        @click.prevent="reset()"
                    >
                        Reset
                    </button>
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

            <template slot="id" slot-scope="props" class="items-center">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>
                    <div class="flex">
                        <router-link
                            :to="'/orders/' + props.item.id + '?tag=orders'"
                            v-can="'view.orders'"
                        >
                            <a
                                class="flex items-center mr-3"
                                href="javascript:;"
                            >
                                <svg
                                    class="fill-current w-3 h-3 mr-1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                >
                                    <path
                                        d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"
                                    /></svg
                                >Show
                            </a>
                        </router-link>

                        <a
                            class="flex items-center mr-2"
                            href="javascript:;"
                            @click.prevent="
                                validateRefCode(props.item.reference_code)
                            "
                            v-can="'list.order'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"
                            >
                                <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                ></path>
                                <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                ></path>
                            </svg>
                            Validate Payment
                        </a>

                        <a
                            v-if="props.item.category == 'Retail'"
                            class="flex items-center"
                            href="javascript:;"
                            @click.prevent="importSalesOrder(props.item.id)"
                            v-can="'update.order'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"
                            >
                                <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                ></path>
                                <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                ></path>
                            </svg>
                            Import Sales Order
                        </a>

                        <a
                            v-if="
                                props.item.category == 'Retail' &&
                                props.item.payment_id
                            "
                            class="flex items-center"
                            href="javascript:;"
                            @click.prevent="updateSalesOrder(props.item.id)"
                            v-can="'update.order'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"
                            >
                                <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                ></path>
                                <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                ></path>
                            </svg>
                            Update Sales Order
                        </a>
                    </div>
                </div>
            </template>
        </table-template>
    </div>
</template>
<script>
import TableTemplate from "../../Table";
export default {
    components: { TableTemplate },
    data() {
        return {
            fields: {
                order_number : "Order Number",
                category: "Store Type",
                payment_name: "Payment Type",
                reference_code: "Reference Code",
                payment_id: "Transaction ID",
                order_date : 'Date Ordered',
                customer_firstname: "First Name",
                customer_lastname: "Last Name",
                email: "Email",
                customer_created_at : "Customer Created Date",
                checkout_method: "Checkout Method",
                payment_status: "Payment Status",
                order_status: "Order Status",
                sub_total_amount: "Sub Total",
                total_discount_price: "Total Discount Price",
                total_order_amount: "Total Amount",
                release_type: "Release Type",
                address: "Address",
            },

            other_fields: {
                id: "Actions",
            },

            filters: ["First Name", "Last Name", "Email", "Mobile No"],

            filter: null,
            status: null,
            store : null,
            stores :  [],
            params: {
                store: null,
                decrypt: null,
                status: null,
                from : null,
				to : null,
            },
            date: new Date(),
            currentDate: new Date(),
        };
    },

    created() {
        this.getStores();
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

    watch: {
        status() {
            this.params.status = this.status ? this.status : null;
            app.$emit("reload");
        },

        store() {
            this.params.store = this.store ? this.store : null;
            app.$emit("reload");
        },

        date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
        },
    },

    methods: {
        reset() {
            this.params.status = null;
            this.status = null;

            app.$emit("reload");
        },

        importSalesOrder(id) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to Rerun Import Sales Order?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Validate",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/orders/${id}/import-sales-order`)
                        .then(() => {
                            this.$modal.success({
                                message: "Successfully Import Sales Order",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch((error) => {
                            if (error.response.status)
                                this.$modal.error({
                                    message: error.response.data.message,
                                });
                        });
                });
        },

        updateSalesOrder(id) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to Rerun Update Sales Order?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Validate",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/orders/${id}/update-sales-order`)
                        .then(() => {
                            this.$modal.success({
                                message: "Successfully Update Sales Order",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch((error) => {
                            if (error.response.status)
                                this.$modal.error({
                                    message: error.response.data.message,
                                });
                        });
                });
        },

        validateRefCode(reference_code) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to Validate this Order's Reference Code?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Validate",
                })
                .then((confirmed) => {
                    axios
                        .get(`/payment/validate?ref_code=${reference_code}`)
                        .then(() => {
                            this.$modal.success({
                                message: "Successfully Validated",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch((error) => {
                            if (error.response.status)
                                this.$modal.error({
                                    message: error.response.data.message,
                                });
                        });
                });
        },

        getStores() {
            axios.get("/stores").then(({ data }) => {
                this.stores = data;
            });
        },

        exportData() {
            if(this.params.from && this.params.to){
                let link = 'orders-export?'

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
    },
};
</script>
