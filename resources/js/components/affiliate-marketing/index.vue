<template>
    <div class="w-full">
        <table-template
            link="/affiliate-marketing"
            :fields="fields"
            :other_fields="other_fields"
            modalIdentifier="#affiliate-marketing-create"
            :isLoading="isLoading"
        >
            <template slot="label">
                <h4>List of Marketers</h4>
            </template>
            <template slot="additionals">
                <div class="w-80 sm:flex items-center sm:mr-4">
                    <label class="w-18 flex-none xl:w-auto xl:flex-initial mr-2"
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
                        class="btn btn-primary mr-2 w-full sm:w-16"
                        @click.prevent="reset()"
                    >
                        Reset
                    </button>
                </div>

                <div class="mt-2 xl:mt-0">
                    <button
                        id="tabulator-html-filter-go"
                        type="button"
                        class="btn btn-primary mr-2 w-full sm:w-16"
                        @click.prevent="exportData()"
                    >
                        Export
                    </button>
                </div>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>
            <template slot="active" slot-scope="props">
                <div class="flex items-center w-24 mr-6">
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
                <span class="mr-4 uppercase font-semibold">Actions:</span>
                <router-link
                    :to="'/affiliate-marketing/' + props.item.id + '?tag=affiliate&link=marketer'" 
                    v-can="'view.affiliate-marketing'"
                >
                    <a class="flex items-center mr-3" href="javascript:;">
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
                    data-target="#affiliate-marketing-edit"
                    @click.prevent="
                        setAffiliateMarketing(props.item, props.index)
                    "
                    v-can="'update.affiliate-marketing'"
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
                    Edit
                </a>

                <a
                    class="flex items-center text-theme-6"
                    href="javascript:;"
                    @click.prevent="deleteAffiliateMarketing(props.item.id)"
                    v-can="'delete.affiliate-marketing'"
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
            </template>
        </table-template>

        <create></create>

        <edit
            v-if="affiliate_marketing"
            @update="clearAffiliateMarketing"
            :affiliate_marketing="affiliate_marketing"
        />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import Edit from "./edit";
import Create from "./create";

export default {
    components: { TableTemplate, Create, Edit },
    data() {
        return {
            fields: {
                code: "Code",
                first_name: "First Name",
                last_name: "Last Name",
                middle_name: "Middle Name",
                phone_no: "Phone No.",
                email: "Email",
                active: "Status",
            },

            other_fields: {
                id: "Actions",
            },

            affiliate_marketing: null,
            index: 0,
            date: new Date(),
            currentDate: new Date(),
            color: "#1C3FAA",
            isLoading: false,

            params: {
                from: null,
                to: null,
                id: this.$route.params.id,
            },
        };
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
        date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
        },
    },
    methods: {
        deleteAffiliateMarketing(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to delete this Affiliate?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    this.isLoading = true;
                    axios
                        .delete(`/affiliate-marketing/${id}`)
                        .then(() => {
                            this.isLoading = false;
                            this.$modal.success({
                                message: "User Successfully Deleted",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },

        setAffiliateMarketing(affiliate_marketing, index) {
            console.log(index);
            this.affiliate_marketing = affiliate_marketing;
            this.index = index;
        },

        clearAffiliateMarketing() {
            this.affiliate_marketing = null;
            this.index = 0;
        },

        reset() {
            this.params.to = null;
            this.params.from = null;
            this.date = null;
        },

        exportData() {
            let link = "affiliate-marketing/global-export?";

            let parameters = Object.keys(this.params)
                .filter((parameter) => {
                    return this.params[parameter];
                })
                .map((parameter) => {
                    return `${parameter}=${this.params[parameter]}`;
                })
                .join("&");

            window.open(link + parameters, "_blank");
        },

        changeActiveStatus(id, active) {
            axios
                .patch(`affiliate-marketing/${id}/active`, {
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
