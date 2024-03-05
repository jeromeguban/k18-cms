<template>
    <div class="w-full">
        <table-template
            link="/vouchers"
            :fields="fields"
            :other_fields="other_fields"
            modalIdentifier="#vouchers-create"
            :additionalBtn="true"
            additionalBtnModalIdentifier="#terms-create"
            additionalBtnName="Create Voucher Terms"
        >
            <template slot="label">
                <h4>List of Vouchers</h4>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="expiration_date" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold"
                        >Expiration Date:</span
                    >

                    {{ props.item.expiration_date }}
                </div>
            </template>

            <template slot="active" slot-scope="props">
                <div class="flex items-center w-24 mr-6">
                    <span class="mr-4 uppercase font-semibold">Active:</span>
                    <label
                        v-if="props.item.active == 1"
                        class="w-20 text-left text-md text-theme-9"
                        >Active</label
                    >
                    <label
                        v-if="props.item.active == 0"
                        class="w-20 text-left text-md text-theme-6"
                        >Inactive</label
                    >
                    <div class="w-24 mt-1">
                        <label for="settings" class="cursor-pointer">
                            <input
                                type="checkbox"
                                class="show-code form-check-switch mr-0 ml-3"
                                :id="'enable-' + index"
                                :checked="props.item.active == 1"
                                @change="
                                    changeActiveStatus(
                                        props.item.id,
                                        props.item.active
                                    )
                                "
                            />
                            <label
                                :for="'enable-' + index"
                                class="font-semibold text-gray-600"
                                v-if="props.item.active == 1"
                            ></label>
                            <label
                                :for="'enable-' + index"
                                class="font-semibold text-gray-600"
                                v-else
                            ></label>
                        </label>
                    </div>
                </div>
            </template>

            <template slot="id" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>

                    <div class="flex flex-col gap-1">
                        <a
                            class="flex items-center mr-3"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#vouchers-edit"
                            @click.prevent="setVoucher(props.item, props.index)"
                            v-can="'update.voucher'"
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
                            Edit
                        </a>

                        <a
                            class="flex items-center text-theme-6"
                            href="javascript:;"
                            @click.prevent="deleteVoucher(props.item.id)"
                            v-can="'delete.voucher'"
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
        </table-template>

        <create></create>
        <term></term>
        <edit v-if="voucher" :voucher="voucher" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import Edit from "./edit";
import Create from "./create";
import Term from "./term";
export default {
    components: { TableTemplate, Create, Edit, Term },
    data() {
        return {
            fields: {
                term_name: "Terms & Conditions",
                name: "Name",
                code: "Code",
                type: "Type",
                value: "Value",
                limit: "Limit",
                total_usage: "Total Usage",
                minimum_purchase_requirements: "Minimum Purchase Requirements",
                category: "Category",
            },

            other_fields: {
                expiration_date: "Expiration Date",
                active: "Status",
                id: "Actions",
            },

            active: "",
            voucher: null,
            index: 0,
        };
    },

    methods: {
        deleteVoucher(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to delete this voucher?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    axios.delete(`/vouchers/${id}`).then(() => {
                        this.$modal.success({
                            message: "You successfully deleted a voucher",
                            title: "Deleted",
                        });
                        // Reload page
                        app.$emit("reload");
                    });
                })
                .catch();
        },

        setVoucher(voucher, index) {
            console.log(index);
            this.voucher = voucher;
            this.index = index;
        },

        clearVoucher() {
            this.voucher = null;
            this.index = 0;
        },

        changeActiveStatus(id, active) {
            axios
                .patch(`vouchers/${id}/active`, {
                    active: active,
                })
                .then(({ data }) => {
                    app.$emit("reload");
                })
                .catch((error) => console.log());
        },
    },
};
</script>
