<template>
	<div>
		<div ref="close" id="account-numbers-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Bank</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Account Number
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('account_number')" v-html="form.errors.get('name')" />
								</label>
								<input v-model="form.account_number" type="text" name="account_number"
									class="form-control" placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Account Name
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('account_name')"
										v-html="form.errors.get('account_name')" />
								</label>
								<input v-model="form.account_name" type="text" name="account_name" class="form-control"
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
				account_number: '',
				account_name  : '',
			}),
			isLoading: false,
			bank : null
		}
	},

	created(){
		this.getBank();
	},

	methods: {
		submit() {
			this.isLoading = true;
		    this.form.post('/banks/' + this.$route.params.id + '/account-numbers')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Account Number successfully created!',
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

		getBank() {
			axios.get(`/banks/${this.$route.params.id}`)
			.then(({data})=>{
				this.bank = data;
			});
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
