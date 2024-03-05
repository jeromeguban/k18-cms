<template>
    <div class="w-full">
        <table-template
            link="/payables"
            :fields="fields"
            :other_fields="other_fields"
            :addNewBtn="false"
            :additionalBtn="true"
            additionalBtnModalIdentifier="#for-payables"
            additionalBtnName="For Payables"
        >
            <template slot="label">
                <h4>List of Payables</h4>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="id" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>
                    <div class="flex flex-col gap-1">
                        <router-link
                            :to="'/payables/' + props.item.id + '?tag=accounting'"
                            v-can="'show.payable'"
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
                            class="flex items-center mr-3"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#payables-edit"
                            @click.prevent="setPayable(props.item, props.index)"
                            v-can="'update.cost-type'"
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
                                class="feather feather-edit w-4 h-4 mr-1 text-gray-600"
                            >
                                <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                ></path>
                                <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                ></path>
                            </svg>
                            Check
                        </a>

                        <a
                            class="flex items-center text-theme-6"
                            href="javascript:;"
                            @click.prevent="deletePayable(props.item.id)"
                            v-can="'delete.cost-type'"
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
                                class="feather feather-trash-2 w-4 h-4 mr-1"
                            >
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                ></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Delete
                        </a>
                    </div>
                </div>
            </template>

            <template slot="status" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Status:</span>
                    <span
                        v-show="props.item.status == 'Open'"
                        class="w-2 h-2 rounded-full bg-theme-9 text-white mr-1"
                    ></span>
                    <span
                        v-show="props.item.status == 'Submitted'"
                        class="w-2 h-2 rounded-full bg-theme-12 text-white mr-1"
                    ></span>
                    <span
                        v-show="props.item.status == 'Approved'"
                        class="w-2 h-2 rounded-full bg-theme-10 text-white mr-1"
                    ></span>
                    <span class="font-medium ml-auto">{{
                        props.item.status
                    }}</span>
                </div>
            </template>
        </table-template>

        <for-payable />
        <edit :payable="payable" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import ForPayable from "./for-payable";
import Edit from "./edit";
export default {
    components: { TableTemplate, ForPayable, Edit },
    data() {
        return {
            fields: {
                company_name: "Company Name",
                company_code: "Company Code",
                total_commission: "Total Commission",
                sub_total_amount: "Sub Total Amount",
                discount_total_amount: "Discount on Total Amount",
                total_sold_amount: "Total Sold Amount",
                total_commission: "Total Commission",
                total_costs: "Total Costs",
                total_payable_amount: "Total Payable Amount",
                date_from: "From",
                date_to: "To",
                check_number: "Check Number",
                account_name: "Account Name",
                account_number: "Account No.",
                bank_name: "Bank",
            },

            other_fields: {
                status: "Status",
                id: "Actions",
            },

            payable: null,
            index: 0,
        };
    },
    methods: {
        deletePayable(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to delete this Payable?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    axios.delete(`/payables/${id}`).then(() => {
                        this.$modal.success({
                            message: "You successfully deleted a Payable",
                            title: "Deleted",
                        });
                        // Reload page
                        app.$emit("reload");
                    });
                })
                .catch();
        },

        setPayable(payable, index) {
            console.log(index);
            this.payable = payable;
            this.index = index;
        },
        clearCostType() {
            this.payable = null;
            this.index = 0;
        },
    },
};
</script>
