<template>
    <div class="w-full">
        <table-template
            link="/bidder-deposits"
            :params="params"
            :other_fields="other_fields"
            :fields="fields"
            modalIdentifier="#bidder_deposits-create"
            :addNewBtn="false"
        >
            <template slot="label">
                <h4>List of Bidder Deposits</h4>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="additionals">
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
            </template>

            <template slot="id" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>
                    <div
                        class="flex justify-center flex-wrap"
                        v-if="props.item.status == 'Deposit'"
                    >
                        <a
                            @click.prevent="
                                withdrawBidderDeposit(props.item.id)
                            "
                            class="btn btn-link mr-2"
                            >Withdraw</a
                        >
                    </div>
                    <div
                        class="flex justify-center flex-wrap"
                        v-if="props.item.payment_status == 'Pending'"
                    >
                        <a
                            @click.prevent="markAsPaid(props.item.id)"
                            class="btn btn-link mr-2"
                            >Mark As Paid</a
                        >
                    </div>
                </div>
            </template>
        </table-template>

        <create></create>
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
                customer_firstname: "First Name",
                customer_lastname: "Last Name",
                email: "Email",
                payment_name: "Payment Name",
                payment_status: "Payment Status",
                reference_code: "Reference Code",
                deposit_amount: "Amount",
                status: "Status",
                created_at: "Created Date",
                created_by_name: "Created By",
            },

            other_fields: {
                id: "Actions",
            },

            index: 0,
            filters: ["First Name", "Last Name", "Email", "Mobile No"],

            params: {
                decrypt: null,
                status: null,
            },
        };
    },
    methods: {
        withdrawBidderDeposit(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to withdraw this Deposit?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Withdraw",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/bidder-deposits/${id}/withdraw`)
                        .then(() => {
                            this.$modal.success({
                                message: "Bidder Deposit Successfully Withdraw",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },

        markAsPaid(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to mark as Paid?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Paid",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/bidder-deposits/${id}/paid`)
                        .then(() => {
                            this.$modal.success({
                                message:
                                    "Bidder Deposit Successfully mark as Paid",
                                title: "Success",
                            });

                            this.getCustomerBidderDeposit();

                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },
    },
};
</script>
