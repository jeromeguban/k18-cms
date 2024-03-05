<template>
<div>
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
                    <h4 class="text-lg font-medium">Detailed payables of {{payable.company_name}} : <span class="text-theme-10"> ( From {{payable.date_from}} -  To {{payable.date_to}} ) </span></h4>
                    <button class="btn btn-primary btn-primary-shadow mt-4 md:mt-0 justify-center " @click.prevent="exportExcel()">Export Payable</button>   
                </div>
                <div class="w-full table-box-shadow mb-5 justify-between items-center">
                    <div class="show-content justify-between items-center">
                        <div class="flex flex-row mt-16 text-md font-medium justify-between items-center">
                        
                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Discount Amount</label>
                                    <p class="text-lg text-success">{{ payable.discount_total_amount  }}</p>
                                </div>
                            </div>
                            
                            <div class="w-1/4 h-50" >
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Sub Total Amount</label>
                                    <p class="text-lg text-success">{{ payable.sub_total_amount  }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none">Total Commission</label>
                                    <p class="text-lg text-success">{{ payable.total_commission | moneyFormat}}</p>
                                </div>
                            </div>


                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Costs</label>
                                    <p class="text-lg text-success">{{ payable.total_costs  }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total payables Amount</label>
                                    <p class="text-lg text-success">{{ payable.total_payable_amount | moneyFormat }}</p>
                                </div>
                            </div>

                            <div class="w-1/4 h-50">
                                <div class="show-content__row">
                                    <label class="text-slate-500 font-medium leading-none"> Total Sold Amount</label>
                                    <p class="text-lg text-success">{{ payable.total_sold_amount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full mt-8">
                    <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4  w-full">
                        <div class="flex flex-row">
                            <div class="flex-row items-center justify-start">
                                <h4 class="text-lg font-medium">Stores</h4>
                            </div>
                        </div>
                       
                        <div class="overflow-x-auto mt-6">
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr>
                                        <th>Store Company Code</th>
                                        <th>Store Code</th>
                                        <th>Store Name</th>
                                        <th>Total Discount Amount</th>
                                        <th>Sub Total Amount</th>
                                        <th>Total Commission</th>
                                        <th>Total Costs</th>
                                        <th>Total payables Amount</th>
                                        <th>Total Sold Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(store, index) in stores" v-if="stores.length > 0">
                                        <td>{{ store.store_company_code }}</td>
                                        <td>{{ store.store_code }}</td>
                                        <td>{{ store.store_name }}</td>
                                        <td>{{ store.discount_total_amount | moneyFormat }}</td>
                                        <td>{{ store.sub_total_amount | moneyFormat }}</td>
                                        <td>{{ store.total_commission | moneyFormat }}</td>
                                        <td>{{ store.total_costs | moneyFormat }}</td>
                                        <td>{{ store.total_payable_amount | moneyFormat }}</td>
                                        <td>{{ store.total_sold_amount | moneyFormat }}</td>
                                        <td>
                                        <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#payable-store-items"  @click.prevent="setStore(store , index)" v-can="'create.payable'" >
                                             <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg> Items
                                        </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                 
                    </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
    <store-item v-if="store" :store="store"/>
</div>
  <payable-item v-if="payable" :payable="payable"/>
</div>
</template>
<script>
    import StoreItem from "./store-items";
    import PayableItem from "./payable-items";
	export default {

		components: { 
			StoreItem, PayableItem
		},
        
		data() {
			return {
                payable: null,
                store: null,
                stores: [],
                isLoading  : false,
			}
		},


		watch: {
			
        },


        mounted() {

		

		},

     created() {
            
            this.getPayables();
           
		},


		methods: {

			getPayables() {

				axios.get('/payables/' + this.$route.params.id)
                    .then(({ data }) => {
                       this.payable = data.payables;
                       this.stores = data.payable_stores;
                       this.isLoading = false;
				})

			},

             setStore(store, index) {
                console.log(index);
                this.store = store;
                this.index = index;
            },

            exportExcel(){
	            window.open(`/export-payable?payable_id=${this.$route.params.id}`, "_blank");
			},
			

		}
	}
</script>
