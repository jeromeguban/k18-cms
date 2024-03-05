<template>
	<div>
		<div ref="close" id="bidder_deposits-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Bidder Deposits</h2>
					</div>
					<form class="">
						<div class="modal-body p-10">
							<div class="input-form">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Select Customer
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('customer_id')" v-html="form.errors.get('customer_id')" />
								</label>
								<multiselect searchable="true" :options="customers" name="customer" v-model="customer"
									:custom-label="customerCustomLabel" customMaxWidth="100%"></multiselect>

								<label class="form-label w-full flex flex-col sm:flex-row">
									Payment Type
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('payment_type_id')"
										v-html="form.errors.get('payment_type_id')" />
								</label>
								<multiselect :options="payment_types" name="payment_type" v-model="payment_type"
									:custom-label="paymentTypeLabel" customMaxWidth="100%"></multiselect>

								<label for="validation-form-3" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Amount <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('deposit_amount')"
										v-html="form.errors.get('deposit_amount')" />
								</label>
								<input v-model="form.deposit_amount" type="deposit_amount" name="deposit_amount"
									class="form-control" placeholder="">

								<label for="validation-form-3" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Payment Status <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('payment_status')"
										v-html="form.errors.get('payment_status')" />
								</label>

								<select
									class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
									name="payment_status" v-model="form.payment_status">
									<option value="Paid">Paid</option>
									<option value="Pending">Pending</option>
								</select>

							</div>
						</div>
						<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
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
			form : new Form({
				customer_id : '',
				payment_type_id :'',
				deposit_amount : '',
				payment_status : 'Paid',
			}),
			customer : null,
			customers : [],
			payment_type : null,
			payment_types: [],
			isLoading : false,
		}
	},

	watch : {
		'customer'() {
			this.form.customer_id = this.customer.customer_id;
		},

		'payment_type'() {
			this.form.payment_type_id = this.payment_type.id;
		},
	},

	created() {
		this.getCustomers();
		this.getPaymentTypes();
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/bidder-deposits')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Bid Deposit successfully created!',
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

		getCustomers() {
			axios.get('/customers')
			.then(({data})=>{
				this.customers = data;
			});
		},

		getPaymentTypes() {
			axios.get('/payment-types')
			.then(({data})=>{
				this.payment_types = data;
			});
		},

		customerCustomLabel ({customer_firstname, customer_lastname, email}) {
            return `${customer_lastname}, ${customer_firstname} - ${email}`;
        },

        paymentTypeLabel({name, biller_name}) {
			return `${name} - ${biller_name}`;
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
