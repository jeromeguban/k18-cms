<template>
    <div>
        <!-- BEGIN: Affiliate Report -->
        <div class="col-span-12 mt-6">
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 lg:col-span-12">

                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">Affiliate Marketing Dashboard</h2>
                    </div>
                    <div class="intro-y box overflow-hidden mt-4">
                        <!-- <div class="w-64 mt-6 ml-6 sm:flex items-center sm:mr-4">
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
                        </div> -->

                        <div class="grid grid-cols-2 gap-12 mt-2">

                            <div class="p-5 mt-12 sm:mt-5">
                                <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center" v-if="affiliate_marketings.length <= 0">
									<div class="text-center text-xs mt-2">Loading...</div>
								</div>
                                <div class="flex flex-col md:flex-row md:items-center">
                                    <div class="flex" v-for="affiliate_marketing in affiliate_marketings">
                                        <div>
                                            <div class="text-slate-500 text-lg xl:text-xl font-medium">{{ affiliate_marketing.last_month_total_amount | moneyFormat}}</div>
                                            <div class="mt-0.5 text-slate-500">Last Month</div>
                                        </div>
                                        <div
                                            class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5">
                                        </div>
                                        <div>
                                            <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium">{{ affiliate_marketing.current_month_total_amount | moneyFormat }}</div>
                                            <div class="mt-0.5 text-slate-500">This Month</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[275px]">
                                    <yearly-chart :affiliate_marketings="affiliate_marketings" :options="{responsive: true, maintainAspectRatio: false}"/>
                                </div>
                            </div>

                            <div class="p-5 mt-12 sm:mt-5">
                                <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center" v-if="affiliate_marketings.length <= 0">
									<div class="text-center text-xs mt-2">Loading...</div>
								</div>
                                <div class="flex flex-col md:flex-row md:items-center">
                                    <div class="flex" v-for="affiliate_marketing in affiliate_marketings">
                                        <div>
                                            <div class="text-slate-500 text-lg xl:text-xl font-medium">{{ affiliate_marketing.yesterday_total_amount | moneyFormat}}</div>
                                            <div class="mt-0.5 text-slate-500">Yesterday</div>
                                        </div>
                                        <div
                                            class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5">
                                        </div>
                                        <div>
                                            <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium">{{ affiliate_marketing.today_total_amount | moneyFormat }}</div>
                                            <div class="mt-0.5 text-slate-500">Today</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[275px]">
                                    <monthly-chart :affiliate_marketings="affiliate_marketings" :options="{responsive: true, maintainAspectRatio: false}"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Affiliate Report -->

        <order-posting/>

    </div>
</template>
<script>
import OrderPosting from '../order-posting';
import Datepicker from 'vuejs-datepicker';
import MonthlyChart from './MonthlyChart';
import YearlyChart from './YearlyChart';
    export default {
        components: { Datepicker, YearlyChart, MonthlyChart ,OrderPosting},
        data() {
            return {
                affiliate_marketing     : null,
                affiliate_marketings    : [],
                date                    : new Date(),
                currentDate             : new Date(),
                color                   : '#1C3FAA',

                params: {
                    from        : null,
                    to          : null,
                },

            }
        },

        created() {
            this.getAffiliateMarketingData();
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

            
        },

        watch: {
            'date'() {
                this.params.from = this.date.start ? this.date.start : null;
                this.params.to = this.date.end ? this.date.end : null;
                this.getAffiliateMarketingDataPerDateRange();
            },
        },

    methods: {

             getAffiliateMarketingData() {
                axios.get(`/dashboard/affiliate-marketing`)
                .then(({data})=>{
                    this.affiliate_marketings = data;
                });
            },
            
            getAffiliateMarketingDataPerDateRange() {
                axios.get(`/dashboard/affiliate-marketing?start_date=${this.params.from}&end_date=${this.params.to}`)
                .then(({data})=>{
                    this.affiliate_marketings = data;
                });
            },

        },
    }
</script>
