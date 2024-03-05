<template>
    <div class="w-full">
        <table-template link="/customers" :params="params" :fields="fields" modalIdentifier="#customers-create">
            <template slot="label">
                <h4>List of Customers</h4>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.customer_firstname }}</span>
            </template>

            <template slot="additionals">

                <div class="w-64 sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Date Range</label>
                    <VueDatePicker class="form-control h-9" v-model="date" :color="color" :min-date="minDate"
                        :max-date="maxDate" placeholder="From - To" range range-header-text="From %d To %d"
                        range-input-text="From %d To %d" fullscreen-mobile validate />

                </div>

                <div class="mt-2 xl:mt-0">
                    <button type="button" class="btn btn-primary w-full sm:w-16"
                        @click.prevent="exportData()">Export</button>
                </div>

                <div class="mt-2 xl:mt-0 ml-4">
                    <button type="button" class="btn btn-primary w-full sm:w-16" @click.prevent="reset()">Reset</button>
                </div>

            </template>

            <template slot="search-filter">

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="params.decrypt">
                        <option v-for="(filter) in filters" :value="filter">{{ filter }}</option>
                    </select>
                </div>

            </template>

            <template slot="customer_id" slot-scope="props">
                <div class="intro-x dropdown w-8 h-8">

                    <div class="dropdown-toggle w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                            <path
                                d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" />
                        </svg>
                    </div>

                    <div class="dropdown-menu w-56">
                        <div class="dropdown-menu__content box bg-white-26 dark:bg-dark-6">
                            <div class="p-2">

                                <router-link :to="'/customers/' + props.item.customer_id + '?tag=customers'" v-can="'view.customer'">
                                    <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;">
                                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
                                        </svg>Show
                                    </a>
                                </router-link>

                                <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" data-toggle="modal" data-target="#customers-edit"
                                    @click.prevent="setCustomer(props.item, props.index)" v-can="'update.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg> Edit
                                </a>

                                <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" data-toggle="modal" data-target="#password-reset"
                                    @click.prevent="setCustomer(props.item, props.index)" v-can="'update.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg> Password Reset
                                </a>

                                <a v-if="!props.item.blocked_date"
                                    class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" @click.prevent="block(props.item.customer_id)"
                                    v-can="'update.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg> Block
                                </a>

                                <a v-if="props.item.blocked_date"
                                    class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" @click.prevent="unblock(props.item.customer_id)"
                                    v-can="'update.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg> Unblock
                                </a>

                                <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" @click.prevent="validate(props.item.customer_id)"
                                    v-can="'update.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                        class="feather feather-trash-2 hover:fill-white w-4 h-4 mr-1">
                                        <path
                                            d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L277.3 424.9l-40.1 74.5c-5.2 9.7-16.3 14.6-27 11.9S192 499 192 488V392c0-5.3 1.8-10.5 5.1-14.7L362.4 164.7c2.5-7.1-6.5-14.3-13-8.4L170.4 318.2l-32 28.9 0 0c-9.2 8.3-22.3 10.6-33.8 5.8l-85-35.4C8.4 312.8 .8 302.2 .1 290s5.5-23.7 16.1-29.8l448-256c10.7-6.1 23.9-5.5 34 1.4z" />
                                    </svg> Validate OTP
                                </a>

                                <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" @click.prevent="deleteCustomer(props.item.customer_id)"
                                    v-can="'delete.customer'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg> Delete
                                </a>

                                <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                    href="javascript:;" data-toggle="modal" data-target="#customer-activity-log"
                                    @click.prevent="setCustomer(props.item, props.index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                        class="feather feather-trash-2 w-4 h-4 mr-1">
                                        <path
                                            d="M504 255.531c.253 136.64-111.18 248.372-247.82 248.468-59.015.042-113.223-20.53-155.822-54.911-11.077-8.94-11.905-25.541-1.839-35.607l11.267-11.267c8.609-8.609 22.353-9.551 31.891-1.984C173.062 425.135 212.781 440 256 440c101.705 0 184-82.311 184-184 0-101.705-82.311-184-184-184-48.814 0-93.149 18.969-126.068 49.932l50.754 50.754c10.08 10.08 2.941 27.314-11.313 27.314H24c-8.837 0-16-7.163-16-16V38.627c0-14.254 17.234-21.393 27.314-11.314l49.372 49.372C129.209 34.136 189.552 8 256 8c136.81 0 247.747 110.78 248 247.531zm-180.912 78.784l9.823-12.63c8.138-10.463 6.253-25.542-4.21-33.679L288 256.349V152c0-13.255-10.745-24-24-24h-16c-13.255 0-24 10.745-24 24v135.651l65.409 50.874c10.463 8.137 25.541 6.253 33.679-4.21z" />
                                    </svg> Activity Log
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </template>

        </table-template>

        <create></create>

        <edit v-if="customer" :customer="customer" />

        <password-reset :customer="customer" />

        <customer-activity-log v-if="customer" :customer="customer" />

    </div>
</template>
<script>
import TableTemplate from "../../Table";
import Create from "./create";
import Edit from "./edit";
import PasswordReset from "./password-reset";
import CustomerActivityLog from "../customer-activity-log/index";

export default {
    components: { TableTemplate, Create, Edit, PasswordReset, CustomerActivityLog },
    data() {
        return {
            fields: {
                customer_firstname: 'First Name',
                customer_lastname: 'Last Name',
                customer_middlename: 'Middle Name',
                username: 'Username',
                email: 'Email',
                mobile_no: 'Mobile No',
                customer_id: 'Actions',

            },

            customer: null,
            customers: [],
            q: null,

            filters: [
                'First Name',
                'Last Name',
                'Email',
                'Mobile No'
            ],

            filter: null,

            params: {
                decrypt: null,
                from: null,
                to: null
            },

            date: new Date(),
            currentDate: new Date(),
            color: '#1C3FAA',
            link: '',

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
                this.currentDate.getDate(),
            );
        },
    },


    watch: {
        'date'() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit('reload');
        },
    },


    methods: {
        searchCustomers() {
            axios.get('/customers', {
                params: {
                    q: this.q,
                    filter: this.filter,
                    decrypt: 1,
                }
            }).then(({ data }) => {
                this.customers = data;
            }).catch((error) => {
                console.log(error);
            });
        },

        deleteCustomer(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to delete this Customer?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Delete'
            })
                .then(confirmed => {
                    axios.delete(`/customers/${id}`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Customer Successfully Deleted',
                                title: 'Success'
                            });
                            // Reload page
                            app.$emit('reload');
                        })
                        .catch();
                }).catch();
        },

        setCustomer(customer, index) {
            console.log(index);
            this.customer = customer;
            this.index = index;
        },

        clearCustomer() {
            this.customer = null;
            this.index = 0;

        },

        block(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to Block this Customer?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Block'
            })
                .then(confirmed => {
                    axios.patch(`/customers/${id}/block`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Customer Successfully Block',
                                title: 'Success'
                            });
                            // Reload page
                            app.$emit('reload');
                        })
                        .catch();
                }).catch();
        },

        unblock(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to Unblock this Customer?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Block'
            })
                .then(confirmed => {
                    axios.patch(`/customers/${id}/unblock`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Customer Successfully Unblock',
                                title: 'Success'
                            });
                            // Reload page
                            app.$emit('reload');
                        })
                        .catch();
                }).catch();
        },

        validate(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to Manually Validate Customers OTP Verification?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Validate'
            })
                .then(confirmed => {
                    axios.patch(`/customers/${id}/validate`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Customer Successfully Validate',
                                title: 'Success'
                            });
                            // Reload page
                            app.$emit('reload');
                        })
                        .catch();
                }).catch();
        },

        reset() {

            this.params.to = null;
            this.params.from = null;
            this.date = null;
            app.$emit('reload');

        },

        exportData() {

            let link = 'customer-export?'

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
    }
}
</script>
