<template>
	<div ref="close" id="customers-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Customer</h2>
				</div>
				<form class="">
					<div class="modal-body p-10 overflow-auto  sideform-container--height">
						<div class="input-form">
							<div class="flex">
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										First Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_firstname')"
											v-html="form.errors.get('customer_firstname')" />
									</label>
									<input v-model="form.customer_firstname" type="text" name="customer_firstname"
										class="form-control" placeholder="">
								</div>

								<div class="w-full ml-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Last Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_lastname')"
											v-html="form.errors.get('customer_lastname')" />
									</label>
									<input v-model="form.customer_lastname" type="text" name="customer_lastname"
										class="form-control" placeholder="">
								</div>
							</div>

							<div class="flex mt-2">
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Middle Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_middlename')"
											v-html="form.errors.get('customer_middlename')" />
									</label>
									<input v-model="form.customer_middlename" type="text" name="customer_middlename"
										class="form-control" placeholder="">
								</div>

								<div class="w-full ml-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Suffix Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_suffixname')"
											v-html="form.errors.get('customer_suffixname')" />
									</label>
									<input v-model="form.customer_suffixname" type="text" name="customer_suffixname"
										class="form-control" placeholder="">
								</div>
							</div>

							<div class="flex mt-2">
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Company Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_company_name')"
											v-html="form.errors.get('customer_company_name')" />
									</label>
									<input v-model="form.customer_company_name" type="text" name="customer_company_name"
										class="form-control" placeholder="">
								</div>

								<div class="w-full ml-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Gender
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_gender')"
											v-html="form.errors.get('customer_gender')" />
									</label>
									<select
										class="border border-solid border-gray-300 mb-2 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-9"
										name="customer_gender" v-model="form.customer_gender">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
								</div>
							</div>

							<div class="flex mt-2">
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Birth Date
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('customer_birthday')"
											v-html="form.errors.get('customer_birthday')" />
									</label>
									<datepicker v-model="form.customer_birthday"
										input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-9">
									</datepicker>
								</div>

								<div class="w-full ml-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Mobile Number
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('mobile_no')" v-html="form.errors.get('mobile_no')" />
									</label>
									<input v-model="form.mobile_no" type="text" name="mobile_no" class="form-control"
										placeholder="">
								</div>
							</div>

							<div>
								<label class="form-label w-full flex flex-col sm:flex-row">
									Telephone Number
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('customer_phone')"
										v-html="form.errors.get('customer_phone')" />
								</label>
								<input v-model="form.customer_phone" type="text" name="customer_phone"
									class="form-control" placeholder="">
							</div>

							<div class="mt-2">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Country
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('country')" v-html="form.errors.get('country')" />
								</label>
								<multiselect searchable="true" :options="countries" name="country"
									v-model="form.country" label="name" customMaxWidth="100%"></multiselect>
							</div>

							<div v-if="form.country">
								<label v-if="is_philippines" class="form-label w-full flex flex-col sm:flex-row">
									Province
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('province')" v-html="form.errors.get('province')" />
								</label>
								<multiselect :options="provinces" name="province" label="provDesc"
									v-model="form.province" v-if="is_philippines" customMaxWidth="100%"></multiselect>
							</div>

							<div v-if="form.province">
								<label v-if="is_philippines" class="form-label w-full flex flex-col sm:flex-row">
									City
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('city')" v-html="form.errors.get('city')" />
								</label>
								<multiselect :options="cities" name="city" label="citymunDesc" v-model="form.city"
									placeholder="Select City" v-if="is_philippines" customMaxWidth="100%"></multiselect>
							</div>


							<div v-if="form.city">
								<label v-if="is_philippines" class="form-label w-full flex flex-col sm:flex-row">
									Barangay
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('barangay')" v-html="form.errors.get('barangay')" />
								</label>
								<multiselect :options="barangays" name="barangay" label="brgyDesc"
									v-model="form.barangay" placeholder="Select Barangay" v-if="is_philippines"
									customMaxWidth="100%"></multiselect>
							</div>
							<div v-if="form.barangay">
								<label v-if="is_philippines" class="form-label w-full flex flex-col sm:flex-row">
									Zip Code
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('zipcode')" v-html="form.errors.get('zipcode')" />
								</label>
								<multiselect :options="zipcodes" name="zipcode" label="zipcode" v-model="form.zipcode"
									placeholder="Select Zip Code" v-if="is_philippines" customMaxWidth="100%">
								</multiselect>
							</div>

							<label class="form-label w-full flex flex-col sm:flex-row">
								Additional Information
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('additional_information')"
									v-html="form.errors.get('additional_information')" />
							</label>
							<input v-model="form.additional_information" type="text" name="additional_information"
								class="form-control" placeholder="">

							<div class="flex">
								<div class="relative block min-h-6  mt-8 mb-4 flex flex-col w-1/2">
									<div class="flex w-full mb-3">
										<input class="form-check-input" type="checkbox" id="login_credential"
											:checked="form.login_credential" @change="changeLoginCredential">
										<label for="login_credential" v-if="form.login_credential"
											class="custom-checkbox-label relative mb-0 align-top font-semibold ml-2">
											Add Login Credentials
										</label>
										<label for="login_credential" v-if="!form.login_credential"
											class="custom-checkbox-label relative mb-0 align-top font-semibold ml-2">
											Add Login Credentials
										</label>
									</div>
								</div>
							</div>

							<div v-show="login_credential">
								<div>
									<label class="form-label w-full flex flex-col sm:flex-row">
										User Name
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('username')" v-html="form.errors.get('username')" />
									</label>
									<input v-model="form.username" type="text" name="username" class="form-control"
										placeholder="">
								</div>

								<div class="mt-2">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Email
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('email')" v-html="form.errors.get('email')" />
									</label>
									<input v-model="form.email" type="text" name="email" class="form-control"
										placeholder="">
								</div>

								<!-- <div class="mt-2">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Password
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('password')" v-html="form.errors.get('password')"/>
								</label>
								<input v-model="form.password"  type="password" name="password" class="form-control" placeholder="" >
							</div>

							<div class="mt-2">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Password Confirmation
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('password_confirmation')" v-html="form.errors.get('password_confirmation')"/>
								</label>
								<input v-model="form.password_confirmation"  type="password" name="password_confirmation" class="form-control" placeholder="" >
							</div> -->
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
				</form>
			</div>
		</div>
	</div>
</template>
<script>
import { VueEditor } from "vue2-editor";
import Datepicker from 'vuejs-datepicker';
export default {
	components: { VueEditor, Datepicker },
	props:['customer'],
	data() {
		return {
			form		: {},
			countries 	: [],
			provinces 	: [],
			cities 		: [],
			barangays 	: [],
			zipcodes 	: [],
			login_credential: false,
			isLoading   : false,

		}
	},

	computed: {
  		is_philippines: function () {
    		return (this.form.country == 'Philippines') ? true : false;
  		}
	},

	created() {
		this.form = new Form(this.customer);

		this.getCountries();
		this.getProvinces();
		this.getZipcodes();
	},

	watch:{
		'customer' () {
			this.form = new Form(this.customer);
		},

		'form.province': function () {
			if(this.form.province) {
				this.getCities();
			}
		},

		'form.city': function () {
			if(this.form.city) {
				this.getBarangays();
			}
		}
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/customers/${this.customer.customer_id}`)
			.then(() => {
			 this.isLoading = false;
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Customer Successfuly Updated!',
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

		getCountries(){
			axios.get('/countries')
			.then(({data})=>{
				this.countries = data;
			});
		},

		getProvinces(){
			axios.get('/provinces')
			.then(({data})=>{
				this.provinces = data;
			});
        },

        getCities(){
        	axios.get(`/provinces/${this.form.province}/municipalities`)
			.then((response) => {
				this.cities = response.data;
			}).catch((error) => {
				console.log(error);
			});
        },

        getBarangays(){
        	axios.get(`/municipalities/${this.form.city}/barangays`)
			.then((response) => {
				this.barangays = response.data;
			}).catch((error) => {
				console.log(error);
			});
        },

        getZipcodes() {
			axios.get('/zipcodes')
			.then(({data})=>{
				this.zipcodes = data;
			});
		},

		changeLoginCredential(){
			this.login_credential = !this.login_credential;
		},

		saveLoginCredentials() {
			this.form.post(`/customer/${this.customer.customer_id}/credentials`)
			.then(()=>{
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Customer Successfuly Updated!',
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

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},


	}
}
</script>
