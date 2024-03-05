<template>
    <div class="w-full">
        <table-template
            link="/posting-inquiries"
            :fields="fields"
            :other_fields="other_fields"
            :params="params"
            modalIdentifier="#"
            :addNewBtn="false"
        >
            <template slot="label">
                <h4>List of Posting Inquiries</h4>
            </template>

            <template slot="redirection-button">
                <router-link
                    :to="'/account-executive/dashboard?tag=inquiries'"
                    v-if="account_executive"
                >
                    <a
                        href="javascript:;"
                        class="btn btn-primary shadow-md mr-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            icon-name="monitor"
                            data-lucide="monitor"
                            class="lucide lucide-monitor block mx-auto"
                        >
                            <rect
                                x="2"
                                y="3"
                                width="20"
                                height="14"
                                rx="2"
                                ry="2"
                            ></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        &nbsp; My Dashboard
                    </a>
                </router-link>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="additionals">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Type</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="type"
                    >
                        <option value="Posting-Inquire">
                            Posting Inquiries
                        </option>
                        <option value="Inquire">Contact Us</option>
                        <option value="Sell-with-Us">Sell With Us</option>
                    </select>
                </div>
                <div
                    class="sm:flex items-center sm:mr-4"
                    v-if="type == 'Posting-Inquire'"
                >
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Category</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="category"
                    >
                        <option value="real-estate">Real Estate</option>
                        <option value="posting">Posting</option>
                    </select>
                </div>

                <div
                    class="sm:flex items-center sm:mr-4"
                    v-if="type == 'order-by'"
                >
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

                <div
                    class="sm:flex items-center sm:mr-4"
                    v-if="type == 'order-by'"
                >
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Order By</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="order_type"
                    >
                        <option value="ASC">Asc</option>
                        <option value="DESC">Desc</option>
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
                </div>
            </template>

            <template slot="created_at" slot-scope="props">
                <span class="w-20">{{ props.item.created_at }}</span>
            </template>

            <template slot="message" slot-scope="props">
                <span class="w-80">{{ props.item.message }}</span>
            </template>

            <template slot="slug" slot-scope="props">
                <a
                    v-if="props.item.posting_id"
                    :href="'https://hmr.ph/postings/' + props.item.slug"
                    target="_blank"
                    class="font-medium"
                >
                    <u>https://hmr.ph/postings/{{ props.item.slug }}</u>
                </a>

                <a
                    v-if="!props.item.posting_id"
                    class="font-medium text-theme-8"
                >
                    <u>N/A</u>
                </a>
            </template>

            <template slot="account_executive" slot-scope="props">
                <a
                    href="javascript:;"
                    data-toggle="modal"
                    v-if="
                        props.item.account_executive &&
                        props.item.type == 'Sell-with-Us'
                    "
                    :data-target="
                        props.item.type == 'Sell-with-Us'
                            ? '#set-account-executive'
                            : null
                    "
                    @click.prevent="setPostingInquiry(props.item, props.index)"
                    v-can="'update.posting-inquiry'"
                >
                    <div class="flex items-center">
                        <span
                            v-show="props.item.account_executive"
                            class="font-semibold ml-2"
                            ><u>{{ props.item.account_executive }}</u></span
                        >
                    </div>
                </a>

                <a
                    href="javascript:;"
                    data-toggle="modal"
                    v-if="props.item.type == 'Sell-with-Us'"
                    :data-target="
                        props.item.type == 'Sell-with-Us'
                            ? '#set-account-executive'
                            : null
                    "
                    @click.prevent="setPostingInquiry(props.item, props.index)"
                    v-can="'update.posting-inquiry'"
                >
                    <div class="flex items-center w-40">
                        <span
                            v-show="!props.item.account_executive"
                            class="font-medium ml-2 text-theme-8"
                        >
                            Unassigned
                        </span>
                    </div>
                </a>

                <span
                    v-if="props.item.type != 'Sell-with-Us'"
                    class="font-medium text-theme-8 ml-2"
                >
                    Unassigned
                </span>
            </template>

            <template slot="status" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Status:</span>
                    <div class="flex items-center w-40">
                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'Open'"
                                class="flex flex-row text-theme-8"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-8 text-white"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>

                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'In Progress'"
                                class="flex flex-row text-theme-9"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-9 text-white"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>

                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'Answered'"
                                class="flex flex-row text-theme-7"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-7 text-white mr-1"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>

                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'On Hold'"
                                class="flex flex-row text-theme-7"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-12 text-white"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>

                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'Cancelled'"
                                class="flex flex-row text-theme-6"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-6 text-white"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>

                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-status'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.status == 'Closed'"
                                class="flex flex-row text-theme-10"
                            >
                                <span
                                    class="w-4 h-4 rounded-full bg-theme-10 text-white"
                                ></span>
                                <span class="font-medium ml-2">{{
                                    props.item.status
                                }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            </template>

            <template slot="priority" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Priority:</span>
                    <div class="flex items-center">
                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-priority'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.priority == 'Low'"
                                class="flex flex-row text-theme-8"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="#D2DFEA"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    icon-name="flag"
                                    data-lucide="flag"
                                    class="lucide lucide-flag block mx-auto"
                                >
                                    <path
                                        d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                                    ></path>
                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                </svg>
                                <span class="font-medium ml-2">{{
                                    props.item.priority
                                }}</span>
                            </div>
                        </a>
                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-priority'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.priority == 'Normal'"
                                class="flex flex-row text-theme-10"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="#3463D8"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    icon-name="flag"
                                    data-lucide="flag"
                                    class="lucide lucide-flag block mx-auto"
                                >
                                    <path
                                        d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                                    ></path>
                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                </svg>
                                <span class="font-medium ml-2">{{
                                    props.item.priority
                                }}</span>
                            </div>
                        </a>
                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-priority'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.priority == 'High'"
                                class="flex flex-row text-theme-12"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="#FBC503"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    icon-name="flag"
                                    data-lucide="flag"
                                    class="lucide lucide-flag block mx-auto"
                                >
                                    <path
                                        d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                                    ></path>
                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                </svg>
                                <span class="font-medium ml-2">{{
                                    props.item.priority
                                }}</span>
                            </div>
                        </a>
                        <a
                            href="javascript:;"
                            data-toggle="modal"
                            :data-target="'#change-priority'"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'update.posting-inquiry'"
                        >
                            <div
                                v-show="props.item.priority == 'Urgent'"
                                class="flex flex-row text-theme-6"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="#D63D3D"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    icon-name="flag"
                                    data-lucide="flag"
                                    class="lucide lucide-flag block mx-auto"
                                >
                                    <path
                                        d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                                    ></path>
                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                </svg>
                                <span class="font-medium ml-2">{{
                                    props.item.priority
                                }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            </template>

            <template slot="id" slot-scope="props" class="items-center">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>

                    <div class="flex flex-col gap-1">
                        <router-link
                            v-if="props.item.type == 'Sell-with-Us'"
                            :to="'/posting-inquiries/' + props.item.id"
                            v-can="'view.posting-inquiry'"
                        >
                            <a
                                class="flex items-center mr-3"
                                href="javascript:;"
                            >
                                <svg
                                    class="fill-current w-4 h-4 mr-1"
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
                            v-if="props.item.type == 'Sell-with-Us'"
                            :data-target="
                                props.item.type == 'Sell-with-Us'
                                    ? '#set-account-executive'
                                    : null
                            "
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'admin.posting-inquiry'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                icon-name="user"
                                data-lucide="user"
                                class="lucide lucide-user block mx-auto w-4 h-4 mr-1"
                            >
                                <path
                                    d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"
                                ></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Assign
                        </a>

                        <a
                            class="flex items-center mr-4"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#change-status"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'admin.posting-inquiry'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                icon-name="circle"
                                data-lucide="circle"
                                class="lucide lucide-circle block mx-auto w-4 h-4 mr-1"
                            >
                                <circle cx="12" cy="12" r="10"></circle></svg
                            >Status
                        </a>

                        <a
                            class="flex items-center mr-3"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#change-priority"
                            @click.prevent="
                                setPostingInquiry(props.item, props.index)
                            "
                            v-can="'admin.posting-inquiry'"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                icon-name="flag"
                                data-lucide="flag"
                                class="lucide lucide-flag block mx-auto w-4 h-4 mr-1"
                            >
                                <path
                                    d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                                ></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line></svg
                            >Priority
                        </a>

                        <a
                            class="flex items-center text-theme-6 mr-3"
                            href="javascript:;"
                            @click.prevent="deleteInquiry(props.item.id)"
                            v-can="'delete.posting-inquiry'"
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
        <priority :posting_inquiry="posting_inquiry" />
        <status :posting_inquiry="posting_inquiry" />
        <account-executive :posting_inquiry="posting_inquiry" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import Priority from "./priority";
import Status from "./status";
import AccountExecutive from "./account-executive";
export default {
    components: { TableTemplate, Priority, Status, AccountExecutive },
    props: {
        account_executive: {
            type: String,
            default: null,
        },
        developer: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            fields: {
                full_name: "Name",
                mobile_no: "Mobile No.",
                email: "Email",
                created_at: "Date of inquiry",
                message: "Message",
                slug: "Item Link",
                account_executive: "Account Executive",
            },

            other_fields: {
                status: "Status",
                priority: "Priority",
                id: "Actions",
            },

            posting_inquiry: null,
            category: null,
            type: "",
            filter_type: null,
            order_type: null,
            date: new Date(),
            currentDate: new Date(),
            color: "#1C3FAA",
            priority: null,
            params: {
                category: null,
                type: null,
                from: null,
                to: null,
                filter_type: null,
                order_type: null,
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
        category() {
            this.params.category = this.category ? this.category : null;
            app.$emit("reload");
        },

        type() {
            this.params.type = this.type ? this.type : null;
            app.$emit("reload");
        },

        date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit("reload");
        },

        filter_type() {
            this.params.filter_type = this.filter_type
                ? this.filter_type
                : null;
        },
    },

    methods: {
        deleteInquiry(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to delete this Inquiry?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    axios
                        .delete(`/posting-inquiries/${id}`)
                        .then(() => {
                            this.$modal.success({
                                message: "Inquiry Successfully Deleted",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },
        reset() {
            this.params.category = null;
            this.params.type = null;
            this.params.to = null;
            this.params.from = null;
            this.params.filter_type = null;
            this.params.order_type = null;
            this.category = null;
            this.type = null;
            this.date = null;
            this.filter_type = null;
            this.order_type = null;

            app.$emit("reload");
        },

        setPostingInquiry(posting_inquiry, index) {
            console.log(posting_inquiry);
            this.posting_inquiry = posting_inquiry;
            this.index = index;
        },

        clearPostingInquiry() {
            this.posting_inquiry = null;
            this.index = 0;
        },
    },
};
</script>
