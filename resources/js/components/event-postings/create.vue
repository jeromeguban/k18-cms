<template>
	<div>
		<div ref="close" id="add-posting" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">New Product</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
                        <div class="modal-body p-10">
                            <div class="input-form">
                                <div class="flex items-center mt-4">
                                    <div class="w-full">
                                        <div class="flex flex-col w-full mb-3 pr-1">
                                            <span class="font-semibold">Barcode</span>
                                            <input type="text" 
                                                class="border border-solid border-gray-300 mt-2 focus:border-indigo-300 px-2 py-2 rounded outline-none  flex-0 h-10 w-full"	
                                                v-model="barcode"
                                                @keyup.enter="getItems"
                                            >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex w-full mt-2" v-if="posting_items">
                                    <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4  w-full">
                                        <div class="flex flex-col md:flex-row justify-between items-center mt-5">
                                            <h4 class="text-lg font-medium">Select Items</h4>
                                        </div>
                                        <div class="overflow-x-auto mt-6">
                                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                                            <table class="table table-report -mt-2">
                                                <thead>
                                                    <tr>
                                                        <th>SKU</th>
                                                        <th>Name</th>
                                                        <th>Condition</th>
                                                        <th>Quantity</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(posting_item, index) in posting_items" v-if="posting_items.length > 0">
                                                        <td>{{ posting_item.sku }}</td>
                                                        <td>{{ posting_item.name }}</td>
                                                        <td>{{ posting_item.condition}}</td>
                                                        <td>{{ posting_item.quantity }}</td>
                                                        <td>
                                                            <button @click.prevent="getBarcode(posting_item)" class="btn btn-primary w-20">Select</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>                 
                                    </div>
                                    </div>
                                </div>

                                <div class="flex w-full">
                                    <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4 w-full">
                                        <div class="flex flex-row justify-between w-ful">
                                            <div>
                                                <h2 class="font-semibold">Product Details</h2>
                                            </div>
                                            <div>
                                                <span class="text-red-500 my-2 flex items-center text-2xs" 
                                                    v-if="form.errors.has('posting_id')"
                                                    v-text="form.errors.get('posting_id')"
                                                />
                                            </div>
                                        </div>
                                        <div v-if="item" class="flex justify-center border-b border-dashed border-gray-300 py-4">
                                            <img
                                                alt="Banner"
                                                :src="banner"
                                                class="w-1/2 h-1/2 image-fit"
                                            />
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Name : {{name}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Description : {{description | limitDescription(250)}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Quantity : {{quantity}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Unit Price : {{unit_price | moneyFormat}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>SRP : {{suggested_retail_price | moneyFormat}}</span>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
							<button @click.prevent="submit()" class="btn btn-primary w-20">Save</button>
						</div>
						<!-- END: Modal Footer -->
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
            form:new Form({
                posting_id : null,
                event_id : this.$route.params.id,
			}),
            item: null,
            barcode: null,
            name: null,
            description: null,
            extended_description: null,
            quantity: null,
            unit_price: null,
            suggested_retail_price: null,
			isLoading : false,
            error: null,
            posting_items: null
		}
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/tag-posting')
				.then(() => {
                 this.$emit('created');
				 this.isLoading = false;
                 this.item = null;
                 this.posting_items = null;
                 this.barcode = null;
                 this.banner = null;
                 this.name = null;
                 this.description = null;
                 this.extended_description = null;
                 this.quantity = 0;
                 this.unit_price = null;
                 this.suggested_retail_price = null;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Item successfully Added!',
						config: {
						timeout: 2000,
						showProgressBar: true,
						closeOnClick: false,
						pauseOnHover: true
						}
					}), 1000);
				}));
				 // Reload page
                this.closeModal();
				app.$emit('reload');
                this.$emit('refreshProducts');
			})
			.catch((error)=>{
				this.isLoading = false;
                if(error.response.status)
                    this.error = error.response.data.message;
                    this.$modal.error({
                        message:error.response.data.message,
                    });		
			});
        },

        getItems() {
            if(this.barcode){
                axios.get(`/posting-items/${this.barcode}/sku`)
                .then(({data})=>{
                    this.posting_items = data;
                })
                .catch((error)=>{
                    if(error.response.status)
                    this.error = error.response.data.message;
                    this.$modal.error({
                        message:error.response.data.message,
                    });		
                })
            }
        },

        getBarcode(posting_item) {
            if(this.barcode){
                axios.get(`/posting/${posting_item.id}/items/${this.barcode}/sku`)
                .then(({data})=>{
                    this.item = data;
                    this.form.posting_id = data.posting_id;
                    this.banner = data.banner;
                    this.name = data.name;
                    this.description =  data.description;
                    this.extended_description =  data.extended_description;
                    this.quantity =  data.quantity;
                    this.unit_price =  data.unit_price;
                    this.suggested_retail_price =  data.suggested_retail_price;
                })
                .catch((error)=>{
                    if(error.response.status)
                    this.error = error.response.data.message;
                    this.$modal.error({
                        message:error.response.data.message,
                    });		
                })
            }
        },

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
