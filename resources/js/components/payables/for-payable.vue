<template>
    <div>
        <div ref="close" id="for-payables" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header bg-primary-1 text-theme-2">
                        <h2 class="font-medium text-base mr-auto">For Payables</h2>
                    </div>

                    <form class="">
                        <div class="modal-body p-10">

                            <div class="w-full h-full overflow-auto">
                                <div class="flex flex-col w-full ">
                                    <div class="flex">
                                        <div class="flex justify-between items-center py-4">
                                            <div class="flex flex-col w-1/2 pr-1 mr-5 ml-5">
                                                <label class="text-2xs font-semibold">From</label>
                                                <datepicker v-model="from"
                                                    input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1">
                                                </datepicker>
                                            </div>
                                            <div class="flex flex-col w-1/2 pr-1 mr-5 ml-5">
                                                <label class="text-2xs font-semibold">To</label>
                                                <datepicker v-model="to"
                                                    input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1">
                                                </datepicker>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center py-4 mt-2">
                                            <button class="btn btn-primary d-paddingTop10"
                                                @click.prevent="generateData()"> Generate </button>
                                        </div>
                                    </div>
                                </div>

                                <table-template link="for-payables" :fields="fields" :params="params"
                                    :addNewBtn="false">
                                    <template slot="company_id" slot-scope="props" v-can="'view.payable'">
                                        <router-link class="btn btn-primary d-paddingTop10"
                                            :to="'/for-payables/'+props.item.company_id+'?from='+params.from+'&to='+params.to+'&company_id='+props.item.company_id">
                                            Create Payable
                                        </router-link>

                                    </template>
                                </table-template>
                            </div>

                        </div>
                        <!-- BEGIN: Modal Footer -->
                        <div class="modal-footer text-right">
                            <button type="button" data-dismiss="modal"
                                class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        </div>
                        <!-- END: Modal Footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import TableTemplate from "../../Table";
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
export default {
	components: { TableTemplate, Datepicker},
	data() {
		return {
			fields: {
                company_name		 : 'Company Name',
				company_code		 : 'Company Code',
				total_commission	 : 'Total Commission',
				sub_total_amount	 : 'Sub Total Amount',
				discount_total_amount: 'Discount on Total Amount',
				total_sold_amount	 : 'Total Sold Amount',
				total_commission	 : 'Total Commission',
				total_costs  		 : 'Total Costs',
				total_payable_amount : 'Total Payable Amount',
                company_id           : 'Actions'
			},
			index: 0,
			from:null,
            to:null,
		
			params: {
				from:null,
                to: null,
                company_id:null,
				filter:null,
			}
		}
    },

     mounted() {

         app.$on('closeModal', (close) => {
            if(close)
                this.closeModal();
        })

    },

	watch: {
		
		'from'() {
            this.params.from =moment(this.from).format('YYYY-MM-DD') ? moment(this.from).format('YYYY-MM-DD') : null
		},
		'to'() {
            this.params.to = moment(this.to).format('YYYY-MM-DD') ? moment(this.to).format('YYYY-MM-DD') : null;
        },

    },
    
	created() {
		
	},
	methods: {
	
		generateData(){
            app.$emit('reload');
        },

        closeModal() {
            this.$refs.close.click();
        }
    
	}
}
</script>