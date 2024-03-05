<template>
    <div class="w-full">
       <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
           <template slot="label">
               <h4>Voucher Report</h4>
           </template>

           <template slot="additionals">

              <div class="intro-y">  
               <div class="overflow-x-auto scrollbar-hidden mt-6">
                   <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                       <div class="w-full">
                           <div class="flex flex-row w-full">
                                <div class="sm:flex items-center sm:mr-4">
                                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Voucher</label>
                                    <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="voucher">
                                        <option v-for="(voucher) in vouchers" :value="voucher.code">{{voucher.code}}</option>
                                    </select>
                                </div>

                                <div class="sm:flex items-center sm:mr-4">
                                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Order Status</label>
                                    <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="status">
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="processing">Processing</option>
                                        <option value="for-delivery">For Delivery</option>
                                        <option value="completed">Completed</option>
                                        <option value="failed-delivery">Failed Delivery</option>
                                    </select>
                                </div>

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
                                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Store</label>
                                    <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="store">
                                        <option v-if="storeList.length >= 1" value="all">All</option>
                                        <option v-for="(store) in storeList" :value="store.id">{{store.store_name}}</option>
                                    </select>

                                <div class="mt-2 xl:mt-0 mr-3 ml-5">
                                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary w-full sm:w-16" @click.prevent="reset()" >Reset</button>
                                </div>

                                <div class="flex justify-between items-center py-4 mr-3 ml-2">
                                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportReport()"> Export </button>
                                </div>

                                <div class="flex justify-between items-center py-4 mr-3 ml-2">
                                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate </button>
                                </div>

                                </div>  
                           </div>
                       </div>
                   </div>
               </div>
              </div>

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

               fields : {
                   voucher_code        : 'Voucher',
                   store_name          : 'Store Name',
                   order_number        : 'Order No.',
                   reference_code      : 'Ref Code',
                   total_order_price   : 'Total Price',
                   total_discount_price: 'Discount Price',
                   customer_name       : 'Customer',
                //    contact_number      : 'Contact No.',
                //    email               : 'Email',
                //    cancelled_date      : 'Cancelled Date',
                   created_at          : 'Ordered Date',
                   order_status        : 'Order Status',
                  
               },
               link      : '',
               store     : null,
               stores    : [],
               voucher     : null,
               vouchers    : [],
               category    : 'Retail',
               status      : null,
               date        : new Date(),
               currentDate : new Date(),
               color       : '#1C3FAA',
               
               params: {
                   voucher  : null,
                   status   : null,
                   stores   : null,
                   from		: null,
                   to	    : null
               }
           }
       },

       computed: {
           storeList() {
               return this.stores.filter(store => {
                   return store.store_type.toLowerCase().includes(this.category.toLowerCase())
               })
           }
       },

       created() {
           this.getStore();
           this.getVoucher();
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
           
       'status'() {
           this.params.status = this.status ? this.status : null;
           this.generate();
       },

       'store'() {
           this.params.store = this.store ? this.store : null;
           this.generate();
       },

       'date'() {
           this.params.from = this.date.start ? this.date.start : null;
           this.params.to = this.date.end ? this.date.end : null;
           this.generate();
       },

       'voucher'() {
           this.params.voucher = this.voucher ? this.voucher : null;
           this.generate();
       },

       },

       methods: {
           getStore() {
               axios.get('/stores')
               .then(({data})=> {
                   this.stores = data;
               });
           },

           getVoucher() {
               axios.get('/vouchers')
               .then(({data})=> {
                   this.vouchers = data;
               });
           },

           reset() {
               
               this.voucher        = null;
               this.store          = null;
               this.params.store   = null;
               this.params.status  = null;
               this.params.from    = null;
               this.params.to      = null;

           },

           exportReport() {

               let link = 'retail/voucher-report?'

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

           generate(){
               this.link = 'retail/generate-voucher-report'
               app.$emit('reload');
           },

       }
   }
</script>
