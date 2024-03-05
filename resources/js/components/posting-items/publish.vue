<template>
	<div id="posting-items-publish" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">{{ !posting_item.google_merchant_product_id ? 'Upload to'
					: 'Update on' }} Google Merchant</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">

						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Category</label>
							<multiselect v-model="category" :options="categories" name="categories"
								label="category_name" customMaxWidth="100%"></multiselect>
							<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
								v-if="form.errors.get('category_id')" v-html="form.errors.get('category_id')" />

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Brand</label>
							<multiselect v-model="brand" :options="brands" name="brands" label="brand_name"
								customMaxWidth="100%"></multiselect>
							<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
								v-if="form.errors.get('brand_id')" v-html="form.errors.get('brand_id')" />

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Condition</label>
							<select
								class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
								name="condition" v-model="form.condition">
								<option value="new">New</option>
								<option value="refurbished">Refurbished</option>
								<option value="used">Used</option>
							</select>
							<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
								v-if="form.errors.get('condition')" v-html="form.errors.get('condition')" />

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">GTIN (UPC)</label>
							<input v-model="form.gtin" type="text" name="gtin" class="form-control" placeholder="">
							<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('gtin')"
								v-html="form.errors.get('gtin')" />
							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Availability</label>
							<input type="text" disabled="true" class="form-control"
								:value="posting_item.quantity > 0 ? 'In Stock' : 'Out of Stock'">
						</div>

					</div>

					<vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader>
					<!-- BEGIN: Modal Footer -->
					<div class="modal-footer text-right">
						<button type="button" data-dismiss="modal" id="google-merchant-cancel"
							class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
						<button @click.prevent="submit()" class="btn btn-primary w-20">{{
						!posting_item.google_merchant_product_id ? 'Publish' : 'Update' }}</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
export default {	
	props:['posting_item'],
	data() {
		return {
			form 	:  new Form({
				condition : null,
				category_id : null,
				brand_id : null,
				gtin : null,
				brand_name : null,
			}),
			category : null,
			categories : [],
			brand : {
				brand_id: null,
				brand_name: null
			},
			brands : [],
		}
	},

	created(){
		this.getGoogleMerchantProduct()
		this.getGoogleCategory()
		this.getBrands()
		
	},

	watch : {
		'category'() {
			this.form.category_id = this.category ? this.category.id : null
		},

		'brand'() {
			this.form.brand_id = this.brand ? this.brand.brand_id : null
			this.form.brand_name = this.brand ? this.brand.brand_name : null
		},
	},

	methods: {		
		submit() {
			this.form.post(`/products/${this.posting_item.id}/google-merchant/publish`)
			.then(()=>{
				this.category = null
			    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Item successfully published at Google Merchant!',
						config: {
							timeout: 2000,
							showProgressBar: true,
							closeOnClick: false,
							pauseOnHover: true
						}
					}), 1000);
				}));  
					 // Reload page
				document.querySelector('#google-merchant-cancel').click()
				app.$emit('reload');
			})
			.catch((response)=>{
			
			});
		},

		getGoogleCategory() {
			axios.get('/google/product-categories')
			.then(({data})=>{
				this.categories = data;
				this.category = this.categories.find((category)=>{
					return category.id == this.form.category_id
				})
			});
		},

		getBrands() {
			axios.get('/brands')
			.then(({data})=>{
				this.brands = data;
				this.brand = this.brands.find((brand)=>{
					return brand.brand_id == this.form.brand_id
				})
			});
		},

		getGoogleMerchantProduct() {
			if(!this.posting_item.google_merchant_product_id)
				return
			axios.get(`/google-merchant/${this.posting_item.google_merchant_product_id}/products`)
				.then(({data})=>{
					this.form.condition = data.condition
					this.form.gtin = data.gtin
					this.form.brand_name = data.brand_name
					this.form.category_id = data.google_product_category_id
					this.form.brand_id = data.brand_id
				})
		}
	}
}
</script>