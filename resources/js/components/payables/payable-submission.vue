<template>
<div>
    <loader v-if="isLoading" object="#1C3FAA"  size="9" speed="2" bg="#1E1E1E" objectbg="#999793" opacity="5" disableScrolling="false" name="dots"></loader>
    <div class="intro-y box overflow-hidden mt-16">
		<div class="px-5 sm:px-16 py-10 sm:py-20">
			<a  @click.prevent="back()" class="flex cursor-pointer">
				<svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
				<span class=" ml-1">Back</span>		
			</a>
			 
			<div class="overflow-x-auto mt-4">
                 <div class="flex flex-col md:flex-row justify-between items-center mt-5">
                    <h4 class="text-lg font-medium">Detailed Payable of {{ for_payable[0].company_name }} with Company Code : {{for_payable[0].company_code}}</h4>
                 </div>
                <div class="w-full mt-4">
                    <div class="flex flex-col w-full">
                        <div class="flex">
                            <div class="flex justify-between items-center py-4">	
                                <div class="flex flex-col w-1/2 pr-1 ">
                                    <label class="font-semibold">From</label>
                                    <datepicker 
                                        v-model="form.from" 
                                        input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"/>
                                </div>	
                                <div class="flex flex-col w-1/2 pr-1 mr-5 ml-5">
                                    <label class="font-semibold">To</label>
                                    <datepicker 
                                        v-model="form.to" 
                                        input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"/>
                                </div>						
                            </div>

                                <div v-can="'issue-check.payable'" class="flex justify-between items-center py-4 mt-2 ml-4">
                                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generatePayable()">Generate</button>
                                </div>

                                <div v-can="'issue-check.payable'" class="flex justify-between items-center py-4 mt-2 ml-4">
                                    <button class="btn btn-primary d-paddingTop10" @click="submit()">Submit Payables</button>
                                </div>
                        
                        </div>
                    </div>
                </div>
                
                <div class="w-full table-box-shadow mb-5 justify-between items-center">
                    <div class="show-content justify-between items-center">
                        <div class="flex flex-row mt-6 text-md font-medium justify-between items-center">
                            <div class="w-1/4 h-100">
                                <div class="show-content__row">

                                    <label class="text-slate-500 font-medium leading-none">Default Commission</label>
                                    <p class="text-lg text-success">{{  for_payable[0].default_commission}} %</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Discount Amount</label>
                                    <p class="text-lg text-success">{{ for_payable[0].discount_total_amount | moneyFormat }}</p>
                                </div>
                            </div>
                            
                            <div class="w-1/4 h-50" >
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Sub Total Amount</label>
                                    <p class="text-lg text-success">{{ sub_total_amount | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Commission</label>
                                    <p class="text-lg text-success">{{ total_commission_amount | moneyFormat }}</p>
                                </div>
                            </div>


                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Costs</label>
                                    <p class="text-lg text-success">{{ total_costs | moneyFormat }}</p>
                                </div>
                            </div>

                             <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Excluded Items Amount</label>
                                    <p class="text-lg text-success">{{ total_exclude_items | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Payable Amount</label>
                                    <p class="text-lg text-success">{{ total_payable_amount | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Sold Amount</label>
                                    <p class="text-lg text-success">{{ total_amount | moneyFormat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                   <cost :company_id="form.company_id"/>
                </div>
			</div>
		</div>
	</div>
       <store-payable :company_id="form.company_id" :from="form.from" :to="form.to"/>
</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
    import Cost from './costs'
    import StorePayable from '../store-payables/index'
	export default {

    components: { 
        Datepicker, Cost, StorePayable
    },

	data() {
        return {
            for_payable: null,
            isLoading  : true,
            form: new Form({
                costs: [],
                items: [],
                excluded_items: [],
                from: null,
                to: null,
                company_id: null
            }),
            close: true,
            index: 0,
            params: {
                excluded_items: [],
                excluded_payable_amount: 0,
                excluded_commission: 0,
                excluded_sub_total_amount: 0,
                excluded_sold_amount: 0,
                from: null,
                to: null,
                company_id: null
                
            },
        }
    },


    computed: {
            
        
			'total_costs'() {
				return this.form.costs.reduce((a,b) => {
		   			return parseFloat(a) + parseFloat(b.amount)
		   		}, 0)
            },

            'total_exclude_items'() {


                 if (this.params.excluded_payable_amount.length > 0) {
                    
                    return this.params.excluded_payable_amount.reduce((a,b) => {
		   			    return parseFloat(a) + parseFloat(b)
                    }, 0)
                    
                 } else {
                     
                    return this.form.items.reduce((a,b) => {
		   			    return parseFloat(a) + parseFloat(b.sub_total_amount)
		   	    	}, 0)
                
                }
               
            },

            'total_exclude_items_commission'() {

                if (this.params.excluded_commission.length > 0) {
                    
                    return this.params.excluded_commission.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b)
                    }, 0)
                    
                } else {
                    
                    return this.form.items.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b.total_commission)
                    }, 0)
                
                }
            
             },


         'total_excluded_sub_total_amount'() {

                if (this.params.excluded_sub_total_amount.length > 0) {
                    
                    return this.params.excluded_sub_total_amount.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b)
                    }, 0)
                    
                } else {
                    
                    return this.form.items.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b.sub_total_amount)
                    }, 0)
                
                }
            
            },



            'total_excluded_sold_amount'() {

                if (this.params.excluded_sold_amount.length > 0) {
                    
                    return this.params.excluded_sold_amount.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b)
                    }, 0)
                    
                } else {
                    
                    return this.form.items.reduce((a,b) => {
                        return parseFloat(a) + parseFloat(b.total_sold_amount)
                    }, 0)
                
                }
            
            },

        
                
            
			'total_payable_amount'() {
				return (
					parseFloat(this.for_payable[0].total_sold_amount) - (parseFloat(this.for_payable[0].total_commission) + parseFloat(this.total_costs) + parseFloat(this.total_exclude_items))) ?? 0.00
            },
            
            'total_commission_amount'() {
                
				return (
					parseFloat(this.for_payable[0].total_commission) - (parseFloat(this.total_exclude_items_commission ?? 0))) ?? 0
            },

            'sub_total_amount'() {
                    
                    return (
                        parseFloat(this.for_payable[0].sub_total_amount) - (parseFloat(this.total_excluded_sub_total_amount ?? 0))) ?? 0
            },

            'total_amount'() {
                    
                return (
                    parseFloat(this.for_payable[0].total_sold_amount) - (parseFloat(this.total_excluded_sold_amount ?? 0))) ?? 0
            }
        
		},

		watch: {
			'form.from'() {

				if(this.form.from){
                    this.params.from = moment(this.form.from).format('YYYY-MM-DD')
					this.form.from = moment(this.form.from).format('YYYY-MM-DD')
					this.$router.push({path: this.$route.path, query: Object.assign({}, this.$route.query, { from: this.form.from })});

				}
            },
            
			'form.to'() {

				if(this.form.to){
                    this.params.to = moment(this.form.to).format('YYYY-MM-DD')
					this.form.to = moment(this.form.to).format('YYYY-MM-DD')
					this.$router.push({path: this.$route.path , query: Object.assign({}, this.$route.query, { to: this.form.to })});

				}
            },

            'form.company_id'() {

				if(this.form.company_id){
                    this.params.company_id = this.form.company_id
					this.$router.push({path: this.$route.path , query: Object.assign({}, this.$route.query, { company_id: this.form.company_id })});

				}
            },

            'form.items'() {

				if(this.form.items){
                
                    this.form.excluded_items = this.form.items.map((item) => item.order_posting_id);
                    this.params.excluded_items = this.form.items.map((item) => item.order_posting_id);
                    this.params.excluded_payable_amount = this.form.items.map((item) => item.payable_amount);
                    this.params.excluded_commission = this.form.items.map((item) => item.total_commission);
                    this.params.excluded_sub_total_amount = this.form.items.map((item) => item.sub_total_amount);
                    this.params.excluded_sold_amount = this.form.items.map((item) => item.total_sold_amount);
                    
                    this.$router.push({
                        path: `/for-payables/${this.form.company_id}`,
                        query: Object.assign({},this.$route.query,{
                            excluded_items: this.params.excluded_items,
                            excluded_payable_amount: this.params.excluded_payable_amount,
                            excluded_commission: this.params.excluded_commission,
                            excluded_sub_total_amount: this.params.excluded_sub_total_amount,
                            excluded_sold_amount: this.params.excluded_sold_amount
                        })
                    }).catch((error) => { })
				}
            },

          
        },


        mounted() {

			app.$on('setCosts', (costs) => {
				this.form.costs = costs
            }),

            app.$on('setItems', (items) => {
                this.form.items = items
			})

		},

     created() {
            
            if(this.$route.query.company_id)
				this.form.company_id = this.$route.query.company_id

			if(this.$route.query.from)
				this.form.from = this.$route.query.from

			if(this.$route.query.to)
                this.form.to = this.$route.query.to
                
            this.getQueryParameters();
            
            this.getCompanyForPayables();

            app.$emit('closeModal', this.close)

        
           
		},


		methods: {

			submit() {
				this.$modal.dialog({
					message: 'Are you sure you want to submit this payable?',
					confirmButton: 'Okay',
					cancelButton: 'Cancel',
					title: 'Submit'
				})
                .then(confirmed => {  
                    this.isLoading = true;
					this.form.post('/payables')
                        .then(({ data }) => {
                        this.isLoading = false;
						this.$modal.success({
							message: 'Payables Successfully Created',
							title: 'Success'
                        });

                        window.location.replace("https://cms.hmr.ph/dashboard#/payables?tag=accounting");
                        
					})
				}).catch(()=>{
                this.isLoading = false;

                });
            },

            getCompanyForPayables() {
                this.isLoading = true;
				axios.get('/for-payables',{
					params: this.getParameters()
				}).then(({data})=>{
				    this.isLoading = false;
                    this.for_payable = data;
				})
			},

            back() {
                 window.location.replace("https://cms.hmr.ph/dashboard#/payables?tag=accounting");
            },

            generatePayable() {
                this.getCompanyForPayables(); 
            },


             getParameters() {
				let parameters = {
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    company_id: this.$route.query.company_id,
                    excluded_items: null,
                    excluded_payable_amount: 0,
                    excluded_commission: 0,
                    excluded_sub_total_amount:0,
                    excluded_sold_amount:0
                }
               
				return parameters
			},

            getQueryParameters() {

                if (this.$route && this.$route.query && this.form.excluded_items.length <= 0) {

					if(this.$route.query.excluded_items){
						this.params.excluded_items = Array.isArray(this.$route.query.excluded_items) 
						? this.$route.query.excluded_items 
						: [this.$route.query.excluded_items];

                        this.form.excluded_items = this.params.excluded_items
                    }

                    if(this.$route.query.excluded_payable_amount){
						this.params.excluded_payable_amount = Array.isArray(this.$route.query.excluded_payable_amount) 
						? this.$route.query.excluded_payable_amount 
						: [this.$route.query.excluded_payable_amount];
                    }

                     if(this.$route.query.excluded_commission){
						this.params.excluded_commission = Array.isArray(this.$route.query.excluded_commission) 
						? this.$route.query.excluded_commission 
						: [this.$route.query.excluded_commission];
					}

                     if(this.$route.query.excluded_sub_total_amount){
						this.params.excluded_sub_total_amount = Array.isArray(this.$route.query.excluded_sub_total_amount) 
						? this.$route.query.excluded_sub_total_amount 
						: [this.$route.query.excluded_sub_total_amount];
                    }

                     if(this.$route.query.excluded_sold_amount){
						this.params.excluded_sold_amount = Array.isArray(this.$route.query.excluded_sold_amount) 
						? this.$route.query.excluded_sold_amount 
						: [this.$route.query.excluded_sold_amount];
					}
				}
			},
            

		}
	}
</script>
