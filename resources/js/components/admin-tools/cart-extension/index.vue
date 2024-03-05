<template>
    <div class="w-full">
        <table-template
            :link="'/cart/items'"
            :fields="fields"
            :other_fields="other_fields"
            :params="params"
            :addNewBtn="false"
        >
            <template slot="label">
                <h4>Cart Extension</h4>
            </template>

            <template slot="additionals">
                <div class="flex flex-col w-2/5 pr-1 mr-5 ml-5">
                    <div class="flex flex-col pr-1 mr-2">
                        <label class="text-2xs font-semibold">Filter By</label>
                        <multiselect
                            v-model="filter"
                            :options="filters"
                            label="name"
                            customMaxWidth="100%"
                        >
                        </multiselect>
                    </div>
                </div>

                <div
                    class="flex flex-col w-2/5 pr-1 mr-5 ml-5"
                    v-if="params.filter === 'auction'"
                >
                    <label class="text-2xs font-semibold flex-row mt-2"
                        >Select Auction:</label
                    >
                    <div class="flex items-center">
                        <multiselect
                            v-model="auction"
                            :options="auctions"
                            :custom-label="getAuctionLabel"
                            name="getAuctionLabel"
                            customMaxWidth="100%"
                        ></multiselect>
                    </div>
                </div>

                <div
                    class="flex flex-col w-2/5 pr-1 mr-5 ml-5"
                    v-if="params.filter === 'customer'"
                >
                    <div class="flex flex-col pr-1 mr-2">
                        <label class="text-2xs font-semibold"
                            >Filter Customer By</label
                        >
                        <multiselect
                            v-model="filter_customer"
                            :options="filter_customers"
                            label="name"
                            customMaxWidth="100%"
                        >
                        </multiselect>
                    </div>
                </div>

                <div
                    class="flex flex-col w-2/5 pr-1 mr-5 ml-5 mt-0.1"
                    v-if="filter_customer"
                >
                    <label class="text-2xs font-semibold mb-0.5"
                        >Search By {{ filter_customer.name }}:</label
                    >
                    <input
                        type="text"
                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                        v-model="search_customer"
                        @keypress.enter="searchCustomers"
                    />
                </div>

                <div
                    class="flex flex-col w-2/5 pr-1 mr-5 ml-5 -mt-2"
                    v-if="filter_customer"
                >

                
                    <label class="text-2xs font-semibold flex-row mt-2"
                        >Select Customer:</label
                    >
                    <div class="" v-if="filter_customer">
                        <div class="flex items-center">
                            <multiselect
                                v-model="customer"
                                :options="customers"
                                name="customer"
                                :custom-label="customerCustomLabel"
                                customMaxWidth="100%"
                            />
                        </div>
                    </div>
                </div>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="id" slot-scope="props">
                <span class="mr-4 uppercase font-semibold">Actions:</span>
                <a
                    class="flex items-center mr-3"
                    href="javascript:;"
                    @click.prevent="extendCart(props.item.id)"
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
                        class="feather feather-send w-4 h-4 mr-2"
                    >
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Extend
                </a>
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
                auction_number: "Auction Number",
                name: "Item Name",
                description: "Description",
                lot_number: "Lot No.",
                quantity: "Quantity",
                price: "Price",
                total_price: "Total Price",
                expires_at: "Expiration Date",
                store_name: "Store Name",
                store_company_name: "Company",
                category: "Category",
            },

            other_fields: {
                id: "Actions",
            },

            search_customer: null,
            customer: null,
            customers: [],

            auction: null,
            auctions: [],

            filter: null,
            filters: [
                {
                    name: "Auction",
                    value: "auction",
                },
                {
                    name: "Customer",
                    value: "customer",
                },
            ],

            filter_customer: null,
            filter_customers: [
                {
                    name: "First Name",
                    value: "first_name",
                },
                {
                    name: "Last Name",
                    value: "last_name",
                },
                {
                    name: "Email",
                    value: "email",
                },
                {
                    name: "Mobile No",
                    value: "mobile_no",
                },
            ],

            params: {
                customer_id: null,
                filter: null,
                filter_customer: null,
                auction_id: null,
            },
        };
    },

    watch: {
        customer() {
            this.params.customer_id = this.customer.customer_id;
            app.$emit("reload");
        },

        filter() {
            this.params.filter = this.filter.value;
            this.getAuctions();
            this.reset();
        },

        auction() {
            this.params.auction_id = this.auction.auction_id;
            app.$emit("reload");
        },
    },

    methods: {
        extendCart(id) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to extend this cart's expiration?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Cart Expiration",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/cart/${id}/item`)
                        .then(() => {
                            this.$modal.success({
                                message:
                                    "You successfully extend this cart's expiration",
                                title: "Cart Expiration",
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

        searchCustomers() {
            axios
                .get("/customers", {
                    params: {
                        search_customer: this.search_customer,
                        filter: this.filter_customer.value,
                    },
                })
                .then(({ data }) => {
                    this.customers = data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        customerCustomLabel({ customer_firstname, customer_lastname, email }) {
            return `${customer_lastname}, ${customer_firstname} - ${email}`;
        },

        getAuctions() {
            axios
                .get("/auctions")
                .then(({ data }) => {
                    this.auctions = data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getAuctionLabel({ auction_number }) {
            return `${auction_number}`;
        },

        reset() {
            this.customer = null;
            this.filter_customer = null;
            (this.params.customer_id = null),
                (this.params.filter_customer = null);
            this.params.auction_id = null;
        },
    },
};
</script>
