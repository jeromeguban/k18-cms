<template>

	<div ref="close" id="affiliate-marketing-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Affiliate</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
                    <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                </button> -->
				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								First Name
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('first_name')" v-html="form.errors.get('first_name')" />
							</label>
							<input v-model="form.first_name" type="text" name="first_name" class="form-control"
								placeholder="">


							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Last Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-t

								heme-6 " v-if="form.errors.get('last_name')" v-html="form.errors.get('last_name')" />
							</label>
							<input v-model="form.last_name" type="text" name="last_name" class="form-control"
								placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Middle Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-t

								heme-6 " v-if="form.errors.get('middle_name')" v-html="form.errors.get('middle_name')" />
							</label>
							<input v-model="form.middle_name" type="text" name="last_name" class="form-control"
								placeholder="">

							<label for="validation-form-3" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Email <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('email')" v-html="form.errors.get('email')" />
							</label>
							<input v-model="form.email" type="email" name="email" class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">
								Phone No. <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('phone_no')" v-html="form.errors.get('phone_no')" />
							</label>
							<input v-model="form.phone_no" type="number" name="phone" class="form-control"
								placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">
								Commission <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('commission')" v-html="form.errors.get('commission')" />
							</label>
							<input v-model="form.commission" type="number" name="phone" class="form-control"
								placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3"
								@change="changeStatus">
								Status
							</label>
							<div class="w-24 mt-1">
								<label for="settings" class="cursor-pointer">
									<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
										:checked="form.active" @change="changeStatus">
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
export default {
	props:['affiliate_marketing'],
	data() {
		return {
			form: new Form(),
			isLoading : false,
		}
	},

	created() {
		this.form = new Form(this.affiliate_marketing);
	},

	watch:{
		'affiliate_marketing' () {
			this.form = new Form(this.affiliate_marketing);
		},
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/affiliate-marketing/${this.affiliate_marketing.id}`)
			.then(()=>{
			   this.isLoading = false;
		       this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'User Successfuly Updated!',
						config: {
						timeout: 2000,
						showProgressBar: true,
						closeOnClick: false,
						pauseOnHover: true
						}
					}), 1000);
				}));

				this.form = new Form(this.affiliate_marketing);
				// Reload page
                this.closeModal();
				app.$emit('reload');
			})
			.catch(()=>{
			this.isLoading = false;
			});
		},

		changeStatus() {
			this.form.active = !this.form.active;
		},

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},


	}
}
</script>
