<template>
	<div>
		<div ref="close" id="costs-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Cost</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>

					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Store <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('store_id')" v-html="form.errors.get('store_id')" />
								</label>
								<multiselect v-model="store" :options="stores" label="store_name" name="stores"
									customMaxWidth="100%"></multiselect>

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Cost Type <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('cost_type_id')"
										v-html="form.errors.get('cost_type_id')" />
								</label>
								<multiselect v-model="cost_type" :options="cost_types" label="type" name="cost_type"
									customMaxWidth="100%"></multiselect>

								<label class="form-label w-full flex flex-col sm:flex-row">
									Amount
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('amount')" v-html="form.errors.get('amount')" />
								</label>
								<input v-model="form.amount" type="number" name="amount" class="form-control"
									placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-2">
									Remarks
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('remarks')" v-html="form.errors.get('amount')" />
								</label>
								<input v-model="form.remarks" type="text" name="remarks" class="form-control"
									placeholder="">

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
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
            form:new Form({
				store_id	   : '',
				company_id	   :'',
				cost_type_id   : '',
				amount 		   : '',
				remarks 	   : '',
			}),
			store           	: '',
			stores				: [],
			cost_type           : '',
            cost_types          : [],
			isLoading 		    : false,
		}
	},

	watch : {
		'store'() {
			this.form.store_id = this.store.id;
			this.form.company_id = this.store.company_id;
		},
		'cost_type'() {
			this.form.cost_type_id = this.cost_type.id;
		},
	},

	created() {
		this.getStore();
		this.getCostType();
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/costs')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Cost successfully created!',
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
			})
			.catch(()=>{
			this.isLoading = false;

			});
		},

		getStore() {
			axios.get('/stores')
			.then(({data})=> {
				this.stores = data;
			});
		},

		getCostType() {
			axios.get('/cost-types')
			.then(({data})=> {
				this.cost_types = data;
			});
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
