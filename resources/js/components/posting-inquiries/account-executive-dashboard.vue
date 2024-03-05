<template>
    <div class="w-full mt-8">
        <h2 class="text-lg font-medium mr-auto">
            My Dashboard
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-8  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-8 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ open.length }}</div>
                        <div class="text-base text-theme-8 text-slate-500 mt-1">Open</div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-9  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-9 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ inProgress.length }}</div>
                        <div class="text-base text-theme-9 text-slate-500 mt-1">In Progress</div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-7  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-7 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ answered.length }}</div>
                        <div class="text-base text-theme-7 text-slate-500 mt-1">Answered</div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-12  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-12 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ onHold.length }}</div>
                        <div class="text-base text-theme-12 text-slate-500 mt-1">On Hold</div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-6  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-6 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ cancelled.length }}</div>
                        <div class="text-base text-theme-6 text-slate-500 mt-1">Cancelled</div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <span class="w-4 h-4 rounded-full bg-theme-10  text-white"></span>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-theme-10 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                        class="lucide lucide-arrow-right w-4 h-4">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium leading-8">{{ closed.length }}</div>
                        <div class="text-base text-theme-10 text-slate-500 mt-1">Closed</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TABLE -->
        <table-template link="/account-executive/inquiries" :fields="fields" :params="params" modalIdentifier="#"
            :addNewBtn="false">
            <template slot="label">
                <h4>Assigned Inquiries</h4>
            </template>

            <template slot="redirection-button">
                <router-link :to="'/posting-inquiries?tag=inquiries'">
                    <a href="javascript:;" class="btn btn-primary shadow-md mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="mail" data-lucide="mail" class="lucide lucide-mail block mx-auto">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        &nbsp; Inquiries
                    </a>
                </router-link>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="additionals">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Type</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="type">
                        <option value="posting-inquire">Posting Inquiries</option>
                        <option value="inquire">Contact Us</option>
                        <option value="Sell-with-us">Sell With Us</option>
                        <option value="order-by">Order By</option>
                    </select>
                </div>
                <div class="sm:flex items-center sm:mr-4" v-if="type == 'posting-inquire'">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Category</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="category">
                        <option value="real-estate">Real Estate</option>
                        <option value="posting">Posting</option>
                    </select>
                </div>

                <div class="sm:flex items-center sm:mr-4" v-if="type == 'order-by'">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter type</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="filter_type">
                        <option value="published_date">Published Date</option>
                        <option value="created_at">Created At</option>
                        <option value="updated_at">Updated At</option>
                    </select>
                </div>

                <div class="sm:flex items-center sm:mr-4" v-if="type == 'order-by'">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Date Range</label>
                    <VueDatePicker class="form-control h-9" v-model="date" :color="color" :min-date="minDate"
                        :max-date="maxDate" placeholder="From - To" range range-header-text="From %d To %d"
                        range-input-text="From %d To %d" fullscreen-mobile validate />
                </div>

                <div class="sm:flex items-center sm:mr-4" v-if="type == 'order-by'">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Order By</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="order_type">
                        <option value="ASC">Asc</option>
                        <option value="DESC">Desc</option>
                    </select>
                </div>
                <div class="mt-2 xl:mt-0">
                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary w-full sm:w-16"
                        @click.prevent="reset()">Reset</button>
                </div>
            </template>

            <template slot="account_executive" slot-scope="props">
                <a href="javascript:;" data-toggle="modal" data-target="#set-account-executive"
                    @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                    <div class="flex items-center w-40">
                        <span v-show="!props.item.account_executive" class="font-medium ml-2 text-theme-8">
                            No Current Assignee
                        </span>
                        <span v-show="props.item.account_executive" class="font-semibold ml-2"><u>{{
                                props.item.account_executive
                        }}</u></span>
                    </div>
                </a>
            </template>

            <template slot="status" slot-scope="props">

                <div class="flex items-center w-40">
                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'Open'" class=" flex flex-row text-theme-8">
                            <span class="w-4 h-4 rounded-full bg-theme-8 text-white"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'In Progress'" class=" flex flex-row text-theme-9">
                            <span class="w-4 h-4 rounded-full bg-theme-9  text-white"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'Answered'" class=" flex flex-row text-theme-7">
                            <span class="w-4 h-4 rounded-full bg-theme-7  text-white mr-1"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'On Hold'" class=" flex flex-row text-theme-7">
                            <span class="w-4 h-4 rounded-full bg-theme-12  text-white"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'Cancelled'" class=" flex flex-row text-theme-6">
                            <span class="w-4 h-4 rounded-full bg-theme-6  text-white"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#change-status"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.status == 'Closed'" class=" flex flex-row text-theme-10">
                            <span class="w-4 h-4 rounded-full bg-theme-10  text-white"></span>
                            <span class="font-medium ml-2">{{ props.item.status }}</span>
                        </div>
                    </a>
                </div>

            </template>

            <template slot="priority" slot-scope="props">

                <div class="flex items-center">
                    <a href="javascript:;" data-toggle="modal" data-target="#change-priority"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.priority == 'Low'" class="flex flex-row text-theme-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="#D2DFEA" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="flag" data-lucide="flag"
                                class="lucide lucide-flag block mx-auto">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                            <span class="font-medium ml-2">{{ props.item.priority }}</span>
                        </div>
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#change-priority"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.priority == 'Normal'" class=" flex flex-row text-theme-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="#3463D8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="flag" data-lucide="flag"
                                class="lucide lucide-flag block mx-auto">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                            <span class="font-medium ml-2">{{ props.item.priority }}</span>
                        </div>
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#change-priority"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.priority == 'High'" class=" flex flex-row text-theme-12">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="#FBC503" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="flag" data-lucide="flag"
                                class="lucide lucide-flag block mx-auto">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                            <span class="font-medium ml-2">{{ props.item.priority }}</span>
                        </div>
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#change-priority"
                        @click.prevent="setPostingInquiry(props.item, props.index)" v-can="'update.posting-inquiry'">
                        <div v-show="props.item.priority == 'Urgent'" class=" flex flex-row text-theme-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="#D63D3D" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="flag" data-lucide="flag"
                                class="lucide lucide-flag block mx-auto">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                            <span class="font-medium ml-2">{{ props.item.priority }}</span>
                        </div>
                    </a>

                </div>

            </template>

            <template slot="id" slot-scope="props">
                <div class="items-center justify-center w-30">
                    <a class="flex items-center mr-3 mt-1" href="javascript:;" data-toggle="modal"
                        data-target="#send-email" @click.prevent="setPostingInquiry(props.item, props.index)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1" stroke-linecap="round" stroke-linejoin="round" icon-name="mail"
                            data-lucide="mail" class="lucide lucide-mail block mx-auto w-4 h-4">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg> Email
                    </a>

                    <router-link :to="'/posting-inquiries/' + props.item.id" v-can="'view.posting-inquiry'">
                        <a class="flex items-center mr-3 mt-1 " href="javascript:;">
                            <svg class="fill-current w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512">
                                <path
                                    d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
                            </svg>Show
                        </a>
                    </router-link>

                    <a class="flex items-center text-theme-6 mt-1" href="javascript:;"
                        @click.prevent="deleteInquiry(props.item.id)" v-can="'delete.posting-inquiry'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-trash-2 w-4 h-4 mr-1">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                            </path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg> Delete
                    </a>
                </div>
            </template>
        </table-template>
        <priority :posting_inquiry="posting_inquiry" @updated="refreshData" />
        <status :posting_inquiry="posting_inquiry" @updated="refreshData" />
        <account-executive :posting_inquiry="posting_inquiry" @updated="refreshData" />
        <email :posting_inquiry="posting_inquiry" @updated="refreshData" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import Priority from "./priority";
import Status from "./status";
import AccountExecutive from "./account-executive";
import Email from "./email";
export default {
    components: { TableTemplate, Priority, Status, AccountExecutive, Email },
    props: {
        account_executive: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            fields: {
                full_name: 'Name',
                mobile_no: 'Mobile No.',
                email: 'Email',
                created_at: 'Date of inquiry',
                message: 'Message',
                description: 'Description',
                account_executive: 'Account Executive',
                status: 'Status',
                priority: 'Priority',
                id: 'Actions'
            },
            posting_inquiry: null,
            category: null,
            type: null,
            filter_type: null,
            order_type: null,
            date: new Date(),
            currentDate: new Date(),
            color: '#1C3FAA',
            priority: null,
            params: {
                category: null,
                type: null,
                from: null,
                to: null,
                filter_type: null,
                order_type: null
            },
            inquiry: null,
            inquiries: []
        }
    },
    created() {
        this.getAccountExecutiveInquiries();
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

        'closed'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'Closed' && inquiry.account_executive_id == this.account_executive
            })
        },
        'cancelled'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'Cancelled' && inquiry.account_executive_id == this.account_executive
            })
        },
        'onHold'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'On Hold' && inquiry.account_executive_id == this.account_executive
            })
        },
        'answered'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'Answered' && inquiry.account_executive_id == this.account_executive
            })
        },
        'inProgress'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'In Progress' && inquiry.account_executive_id == this.account_executive
            })
        },
        'open'() {
            return this.inquiries.filter((inquiry) => {
                return inquiry.status == 'Open' && inquiry.account_executive_id == this.account_executive
            })
        },
    },

    watch: {
        'category'() {
            this.params.category = this.category ? this.category : null;
            app.$emit('reload');
        },

        'type'() {
            this.params.type = this.type ? this.type : null;
            if (this.type != 'order-by')
                app.$emit('reload');
        },

        'date'() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit('reload');
        },

        'filter_type'() {
            this.params.filter_type = this.filter_type ? this.filter_type : null;
        },

        'order_type'() {
            this.params.order_type = this.order_type ? this.order_type : null;
            app.$emit('reload');
        }
    },

    methods: {
        deleteInquiry(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to delete this Inquiry?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Delete'
            })
                .then(confirmed => {
                    axios.delete(`/posting-inquiries/${id}`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Inquiry Successfully Deleted',
                                title: 'Success'
                            });
                            // Reload page
                            app.$emit('reload');
                        })
                        .catch();
                }).catch();
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

            app.$emit('reload');

        },

        setPostingInquiry(posting_inquiry, index) {
            console.log(index);
            this.posting_inquiry = posting_inquiry;
            this.index = index;
        },

        clearPostingInquiry() {
            this.posting_inquiry = null;
            this.index = 0;
        },

        getAccountExecutiveInquiries() {

            axios.get('/account-executive/inquiries')
                .then(({
                    data
                }) => {
                    this.inquiries = data;
                });
        },

        refreshData() {
            this.getAccountExecutiveInquiries();
        },

    }
}
</script>
