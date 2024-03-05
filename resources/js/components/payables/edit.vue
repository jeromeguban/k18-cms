<template>
	<div ref="close" id="payables-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Check</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<div class="input-form">
								<div>
									<label class="form-label w-full flex flex-col sm:flex-row">
										Remarks
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('remarks')" v-html="form.errors.get('remarks')" />
									</label>
									<input v-model="form.remarks" type="text" name="remarks" class="form-control"
										placeholder="">

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">
										Check Number
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('check_number')"
											v-html="form.errors.get('check_number')" />
									</label>
									<input v-model="form.check_number" type="text" name="check_number"
										class="form-control" placeholder="">

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">Check Date</label>
									<datepicker v-model="form.check_date"
										input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1">
									</datepicker>

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">Bank</label>
									<multiselect v-model="bank" :options="banks" label="name" name="name"
										customMaxWidth="100%"></multiselect>

									<label v-show="bank"
										class="form-label w-full flex flex-col sm:flex-row mt-2">Account</label>
									<multiselect v-show="bank" v-model="account" :options="accounts"
										label="account_number" name="account_number" customMaxWidth="100%">
									</multiselect>
									<div>
										<label class="form-label w-full flex flex-col sm:flex-row mt-2">Payment
											Status</label>
										<div class="col-sm-10">
											<select
												class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
												name="status" v-model="form.status">
												<option value="Open">Open</option>
												<option value="Submitted">Submitted</option>
												<option value="Approved">Approved</option>
											</select>

											<span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
												v-if="form.errors.has('status')"
												v-text="form.errors.get('status')"></span>
										</div>
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
						<button @click.prevent="submit()" class="btn btn-primary w-20">Update</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
export default {
	components: { Datepicker },
	props: ['payable'],
	data() {
		return {
			form: new Form({
				remarks: '',
				check_number: '',
				check_date: '',
				account_number_id: '',
				bank_id: '',
				status: '',

			}),
			bank: null,
			banks: [],
			account: null,
			accounts: [],
			isLoading: false,
		}
	},


	watch: {

		'payable'() {
			this.form = new Form(this.payable);
		},

		'bank'() {
			if (this.bank) {
				this.getAccountNumbers(this.bank.id);
			}
			this.form.bank_id = this.bank.id;
		},

		'account'() {
			this.form.account_number_id = this.account.id;
		},

		'status'() {
			this.form.status = this.form.status;
		},
	},


	created() {
		this.form = new Form(this.payable);
		this.getBanks();
	},


	methods: {

		submit() {
			this.isLoading = true;
			this.form.patch(`/payables/${this.payable.id}`)
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Payable successfully Updated!',
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

		getBanks() {
			axios.get('/banks')
				.then(({ data }) => {
					this.banks = data;
					this.getBankDetail();
				});
		},

		getBankDetail() {
			if (this.banks && this.form.bank_id) {
				this.bank = this.banks.find((bank) => {
					return bank.id == this.form.bank_id
				});
			}
		},

		getAccountNumbers(bank_id) {
			axios.get('/banks/' + bank_id + '/account-numbers')
				.then((response) => {
					this.accounts = response.data;
					this.getAccountNumberDetail();
				});
		},

		getAccountNumberDetail() {
			if (this.accounts && this.form.account_number_id) {
				this.account = this.accounts.find((account) => {
					return account.id == this.form.account_number_id
				});
			}
		},

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

	}

}
</script>
