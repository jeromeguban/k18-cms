<template>
<div>
    <div class="intro-y box overflow-hidden mt-16">
        <loader v-if="isLoading" object="#1C3FAA"  size="9" speed="2" bg="#1E1E1E" objectbg="#999793" opacity="5" disableScrolling="false" name="dots"></loader>
		<div class="px-5 sm:px-16 py-10 sm:py-20">
			<a  v-on:click="$router.go(-1)" class="flex cursor-pointer">
				<svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
				<span class=" ml-1">Back</span>		
			</a>
			 
			<div class="overflow-x-auto mt-4">
                 <div class="flex flex-col md:flex-row justify-between items-center mt-5">
                    <h4 class="text-lg font-medium">Detailed Payable of {{for_payable.code}} - {{for_payable.store_name}}  </h4>
                 </div>
                <div class="w-full table-box-shadow mb-5 justify-between items-center">
                    <div class="show-content justify-between items-center">
                        <div class="flex flex-row mt-16 text-md font-medium justify-between items-center">
                        
                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Discount Amount</label>
                                    <p class="text-lg text-success">{{ for_payable.discount_total_amount | moneyFormat }}</p>
                                </div>
                            </div>
                            
                            <div class="w-1/4 h-50" >
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Sub Total Amount</label>
                                    <p class="text-lg text-success">{{ for_payable.sub_total_amount | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Commission</label>
                                    <p class="text-lg text-success">{{ for_payable.total_commission | moneyFormat }}</p>
                                </div>
                            </div>


                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Costs</label>
                                    <p class="text-lg text-success">{{ for_payable.total_costs | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Payable Amount</label>
                                    <p class="text-lg text-success">{{ for_payable.total_payable_amount | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Sold Amount</label>
                                    <p class="text-lg text-success">{{ for_payable.total_sold_amount | moneyFormat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
	export default {

		components: { 
			
		},
        
		data() {
			return {
                for_payable: null,
                isLoading  : false,
                form: new Form({
                    costs: [],
            		from: null,
            		to: null,
            		store_id: null
                }),
                close: true,
                isLoading : true,
			}
		},


		watch: {
			'form.from'() {

				if(this.form.from){

					this.form.from = moment(this.form.from).format('YYYY-MM-DD')
					this.$router.push({path: this.$route.path, query: Object.assign({}, this.$route.query, { from: this.form.from })});

				}
            },
            
			'form.to'() {

				if(this.form.to){

					this.form.to = moment(this.form.to).format('YYYY-MM-DD')
					this.$router.push({path: this.$route.path , query: Object.assign({}, this.$route.query, { to: this.form.to })});

				}
            },

            'form.company_id'() {

				if(this.form.company_id){

					this.$router.push({path: this.$route.path , query: Object.assign({}, this.$route.query, { company_id: this.form.company_id })});

				}
			}
        },


        mounted() {

			app.$on('setCosts', (costs) => {
				this.form.costs = costs
			})

		},

     created() {
            

            if(this.$route.query.store_id)
				this.form.store_id = this.$route.query.store_id

			if(this.$route.query.from)
				this.form.from = this.$route.query.from

			if(this.$route.query.to)
                this.form.to = this.$route.query.to

            this.getStoreForPayables();

           
		},


		methods: {

			getStoreForPayables() {

				axios.get('/stores/' + this.$route.params.id + '/for-payables',{
					params: {
						from: this.form.from,
                        to: this.form.to,
                        store_id: this.form.store_id
					}
				})
                    .then(({ data }) => {
                  
                       this.for_payable = data;
                       this.isLoading = false;
				})

			},

          

		}
	}
</script>
