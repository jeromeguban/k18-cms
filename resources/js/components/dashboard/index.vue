<template>
     <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: General Report -->
        <div class="col-span-12 mt-8">
            <div class="intro-y flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Today's Report
                </h2>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                <div class="w-64 sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Date Range</label>
                    <VueDatePicker class="form-control h-9"
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
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Category</label>
                    <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="category">
                        <option value="Auction">Auction</option>
                        <option value="Retail">Retail</option>
                        <option value="Whole Sale">Whole Sale</option>
                        <option value="Expression of Interest">Expression of Interest</option>
                    </select>
                </div>
                <div v-if="category == 'Retail' || category === 'Auction'" class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Store</label>
                        <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="store">
                            <option v-if="storeList.length >= 1" value="all">All</option>
                            <option v-for="(store) in storeList" :value="store.id">{{store.store_name}}</option>
                        </select>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5" v-for="order in orders">
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <!-- <i data-feather="shopping-cart" class="report-box__icon text-theme-10"></i> -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="shopping-cart" data-lucide="shopping-cart" class="lucide lucide-shopping-cart report-box__icon text-theme-10"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                                <!-- <div class="ml-auto">
                                    <div class="report-box__indicator bg-theme-10 tooltip cursor-pointer" title="Click to See details">
                                        <span class="text-white">{{order.total_order}}</span>
                                        <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                    </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ order.sub_total_amount | moneyFormat }}</div>
                            <div class="text-base text-gray-600 mt-1" v-if="!params.from && !params.to">Today's Sales</div>
                            <div class="text-base text-gray-600 mt-1" v-if="params.from && params.to">Total Sales</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                <!-- <div class="ml-auto">
                                    <div class="report-box__indicator bg-theme-10 tooltip cursor-pointer" title="Click to See details">
                                        <span class="text-white">{{order.total_order}}</span>
                                        <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                    </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" >{{ order.total_order}}</div>
                            <div class="text-base text-gray-600 mt-1" v-if="!params.from && !params.to">New Orders</div>
                            <div class="text-base text-gray-600 mt-1" v-if="params.from && params.to">Total Orders</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>                                <!-- <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-12 tooltip cursor-pointer" title="Click to See details">
                                            <span class="text-white">{{order.total_order}}</span>
                                            <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                        </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ order.total_posting_items}}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Posted Items</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>                                <!-- <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-12 tooltip cursor-pointer" title="Click to See details">
                                            <span class="text-white">{{order.total_order}}</span>
                                            <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                        </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ order.total_posted}}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Posted</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                           <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 12-8.5 8.5c-.83.83-2.17.83-3 0 0 0 0 0 0 0a2.12 2.12 0 0 1 0-3L12 9"></path><path d="M17.64 15 22 10.64"></path><path d="m20.91 11.7-1.25-1.25c-.6-.6-.93-1.4-.93-2.25v-.86L16.01 4.6a5.56 5.56 0 0 0-3.94-1.64H9l.92.82A6.18 6.18 0 0 1 12 8.4v1.56l2 2h2.47l2.26 1.91"></path></svg>
                                <!-- <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-12 tooltip cursor-pointer" title="Click to See details">
                                            <span class="text-white">{{order.total_pending}}</span>
                                            <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                        </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ order.total_auctions}}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Auction</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="shopping-bag" data-lucide="shopping-bag" class="lucide lucide-shopping-bag report-box__icon text-theme-11"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ order.total_pending}}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Pending</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="users" data-lucide="users" class="lucide lucide-users report-box__icon text-theme-10"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg>                                <!-- <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-12 tooltip cursor-pointer" title="Click to See details">
                                            <span class="text-white">{{order.total_pending}}</span>
                                            <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                        </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ customers_total }}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Customers</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="users" data-lucide="users" class="lucide lucide-users report-box__icon text-theme-13"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><circle cx="19" cy="11" r="2"></circle><path d="M19 8v1"></path><path d="M19 13v1"></path><path d="m21.6 9.5-.87.5"></path><path d="m17.27 12-.87.5"></path><path d="m21.6 12.5-.87-.5"></path><path d="m17.27 10-.87-.5"></path></svg>
                                <!-- <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-12 tooltip cursor-pointer" title="Click to See details">
                                            <span class="text-white">{{order.total_pending}}</span>
                                            <i data-feather="external-link" class="w-4 h-4 mr-1"></i>
                                        </div>
                                </div> -->
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ customers_registeration_total }}</div>
                            <div class="text-base text-gray-600 mt-1">Total no. of Registered</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-12 mt-8">

                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">Sales Report</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-12 mt-5">

                        <div class="box p-5 mt-12 sm:mt-5">
                            <div class="flex flex-col md:flex-row md:items-center">
                                <div class="flex">
                                    <div>
                                        <div class="text-slate-500 text-lg xl:text-xl font-medium">{{ order.total_amount_last_monthly | moneyFormat}}</div>
                                        <div class="mt-0.5 text-slate-500">Last Month</div>
                                    </div>
                                    <div
                                        class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5">
                                    </div>
                                    <div>
                                        <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium">{{ order.total_amount_this_monthly | moneyFormat }}</div>
                                        <div class="mt-0.5 text-slate-500">This Month</div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[275px]">
                                <area-chart-yearly :orders="orders" :options="{responsive: true, maintainAspectRatio: false}"/>
                            </div>
                        </div>

                        <div class="box p-5 mt-12 sm:mt-5">
                            <div class="flex flex-col md:flex-row md:items-center">
                                <div class="flex">
                                    <div>
                                        <div class="text-slate-500 text-lg xl:text-xl font-medium">{{ order.total_amount_yesterday | moneyFormat}}</div>
                                        <div class="mt-0.5 text-slate-500">Yesterday</div>
                                    </div>
                                    <div
                                        class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5">
                                    </div>
                                    <div>
                                        <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium">{{ order.total_amount_today | moneyFormat }}</div>
                                        <div class="mt-0.5 text-slate-500">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[275px]">
                                <area-chart-monthly :orders="orders" :options="{responsive: true, maintainAspectRatio: false}"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END: General Report -->


    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import AreaChartMonthly from './AreaChartMonthly';
import AreaChartYearly from './AreaChartYearly';
    export default {
        components: { Datepicker, AreaChartYearly, AreaChartMonthly },
        props: {
            user: {
                type: String,
                default: null
            },
        },
        data() {
            return {

                category        : null,
                order           : null,
                orders          : [],
                store           : [],
                stores          : [],
                date            : new Date(),
                currentDate     : new Date(),
                color           : '#1C3FAA',
                customers_registeration_total : null,
                customers_total : null,
                params: {
                    from        : null,
                    to          : null,
                    stores      : null,
                },

            }
        },

        created() {
            this.getOrdersAndPosted();
            this.getTotalCustomerRegistrations();
            this.getTotalCustomers();
            this.activeUser();
        },

        computed: {
            minDate () {
                return new Date(
                    this.currentDate.getFullYear() - 1,
                    this.currentDate.getMonth(),
                    this.currentDate.getDate()
                );
            },
            maxDate () {
                return new Date(
                    this.currentDate.getFullYear() + 1,
                    this.currentDate.getMonth(),
                    this.currentDate.getDate(),
                );
            },

            storeList() {
                return this.stores.filter(store => {
                    return store.store_type.toLowerCase().includes(this.category.toLowerCase())
                })
            }

        },

        watch: {
            'date'() {
                this.params.from = this.date.start ? this.date.start : null;
                this.params.to = this.date.end ? this.date.end : null;
                this.generateDateRange();
            },


            'store'() {
                this.params.store = this.store ? this.store: null;
                this.getStoreOrdersAndPosted();
            },
        },

        methods : {
            getOrdersAndPosted() {
                axios.get(`/total-orders-postings/`)
                .then(({data})=>{
                    this.orders = data;
                });
            },

            getStoreOrdersAndPosted() {
                axios.get(`/total-orders-postings/?store=${this.store}&category=${this.category}`)
                .then(({data})=>{
                    this.orders = data;
                });
            },

            generateDateRange() {
                axios.get(`/total-orders-postings?start_date=${this.params.from}&end_date=${this.params.to}&store=${this.store}`)
                .then(({data})=>{
                    this.orders = data;
                });

                axios.get(`/customers-registeration-total?start_date=${this.params.from}&end_date=${this.params.to}`)
                .then(({data})=>{
                    this.customers_registeration_total = data;
                })

                axios.get(`/customers-total?start_date=${this.params.from}&end_date=${this.params.to}`)
                .then(({data})=>{
                    this.customers_total = data;
                })

            },

            getTotalCustomerRegistrations() {
                axios.get(`/customers-registeration-total/`)
                .then(({data})=>{
                    this.customers_registeration_total = data;
                })
            },

            getTotalCustomers() {
                axios.get(`/customers-total/`)
                .then(({data})=>{
                    this.customers_total = data;
                })
            },

            activeUser() {
                axios.get('/users/' + this.user, {
                    params: {
                        relations: ['stores']
                    }
                })
                .then((response) => {
                    this.stores = response.data.stores;
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            getStore() {
                axios.get('/stores')
                .then(({data})=> {
                    this.stores = data;
                });
            },


        },
    }
</script>
