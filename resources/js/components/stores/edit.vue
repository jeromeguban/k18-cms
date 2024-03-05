<template>
	<div ref="close" id="stores-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Store</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">

						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								Store Code
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('code')" v-html="form.errors.get('code')" />
							</label>
							<input v-model="form.code" type="text" name="code" class="form-control" placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Store Company Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('store_company_name')"
									v-html="form.errors.get('store_company_name')" />
							</label>
							<input v-model="form.store_company_name" type="text" name="store_company_name"
								class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">
								Holding Company <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('company_id')" v-html="form.errors.get('company_id')" />
							</label>
							<multiselect v-model="company" :options="companies" label="company_name" name="companies"
								customMaxWidth="100%"></multiselect>

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Store Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('store_name')" v-html="form.errors.get('store_name')" />
							</label>
							<input v-model="form.store_name" type="text" name="store_name" class="form-control"
								placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Address Line <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('address_line')" v-html="form.errors.get('address_line')" />
							</label>
							<input v-model="form.address_line" type="text" name="address_line" class="form-control"
								placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Extended Address <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('extended_address')"
									v-html="form.errors.get('extended_address')" />
							</label>
							<input v-model="form.extended_address" type="text" name="extended_address"
								class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Store Description</label>
							<vue-editor v-model="form.description"></vue-editor>

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Contact Number <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('contact_number')"
									v-html="form.errors.get('contact_number')" />
							</label>
							<input v-model="form.contact_number" type="text" name="contact_number" class="form-control"
								placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Email <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('email')" v-html="form.errors.get('email')" />
							</label>
							<input v-model="form.email" type="text" name="email" class="form-control" placeholder="">
							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Store Company Code</label>

							<div class="col-sm-10">
								<select
									class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
									name="store_company_code" v-model="form.store_company_code">
									<option value="HRH">HRH</option>
									<option value="HASI">HASI</option>
								</select>

								<span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
									v-if="form.errors.has('store_company_code')"
									v-text="form.errors.get('store_company_code')"></span>
							</div>
							<label class="text-2xs font-semibold">Store Type</label>
							<div class="col-sm-10">
								<select
									class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
									name="store_type" v-model="form.store_type">
									<option value="HMR Retail Haus">HMR Retail Haus</option>
									<option value="Save On Surplus">Save On Surplus</option>
									<option value="Auction Warehouse">Auction Warehouse</option>
								</select>

								<span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
									v-if="form.errors.has('store_type')" v-text="form.errors.get('store_type')"></span>
							</div>

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Classifications</label>
							<div class="col-sm-10">
								<multiselect v-model="classification" :options="classifications"
									label="classification_name" name="classifications" customMaxWidth="100%">
								</multiselect>
								<span class="help-block text-red-500" v-if="form.errors.has('classification_id')"
									v-text="form.errors.get('classification_id')"></span>
							</div>

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Courier</label>
							<div class="col-sm-10">
								<multiselect :multiple="true" v-model="form.couriers" :options="couriers" label="name"
									name="couriers" customMaxWidth="100%"></multiselect>
								<span class="help-block text-red-500" v-if="form.errors.has('couriers')"
									v-text="form.errors.get('couriers')"></span>
							</div>

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3"
								@change="changeDelivery">
								Delivery
							</label>
							<div class="w-24 mt-1">
								<label for="settings" class="cursor-pointer">
									<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
										:checked="form.delivery" @change="changeDelivery">
								</label>
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
						<button @click.prevent="submit()" class="btn btn-primary w-20">Update</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
import { VueEditor } from "vue2-editor";
export default {
	components: { VueEditor },
	props: ['store'],
	data() {
		return {
			form: {},
			company: '',
			companies: [],
			courier: [],
			couriers: [],
			classification: '',
			classifications: [],
			isLoading: false,
		}
	},
	created() {
		this.form = new Form(this.store);
		this.getCompany();
		this.getClassification();
		new Promise((resolve, reject) => {
			this.getCouriers();
			resolve();
		}).then(() => {
			this.show();
		})
	},
	watch: {
		'store'() {
			this.form = new Form(this.store);
			this.getCompany();
			this.getCouriers();
			this.getClassification();
			new Promise((resolve, reject) => {
				this.getCouriers();
				resolve();
			}).then(() => {
				this.show();
			})
		},

		'company'() {
			this.form.company_id = this.company.id;
		},

		'courier'() {
			this.form.couriers = this.courier;
		},

		'classification'() {
			this.form.classification_id = this.classification.id;
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/stores/${this.store.id}`)
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Store Successfuly Updated!',
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
				.catch(() => {
					this.isLoading = false;
				});
		},

		getCompany() {
			axios.get('/companies')
				.then(({ data }) => {
					this.companies = data;
					this.getCompanyValue();
				});
		},

		getCompanyValue() {
			if (this.companies && this.form.company_id) {
				this.company = this.companies.find((company) => {
					return company.id == this.form.company_id
				});
			}
		},

		getCouriers() {
			axios.get('/couriers')
				.then(({ data }) => {
					this.couriers = data;
				});
		},

		show() {

			axios.get('/stores/' + this.store.id, {
				params: {
					relations: ['couriers']
				}
			})
				.then((response) => {
					this.form = new Form(response.data);
					console.log(this.form.courier)
				})
				.catch((error) => {
					console.log(error);
				});
		},

		getClassification() {
			axios.get('/classifications')
				.then(({ data }) => {
					this.classifications = data;
					this.getClassifications();
				});
		},

		getClassifications() {
			if (this.classifications && this.form.classification_id) {
				this.classification = this.classifications.find((classification) => {
					return classification.id == this.form.classification_id
				});
			}
		},

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

		changeDelivery() {
			this.form.delivery = !this.form.delivery;
		},
	}
}
</script>
