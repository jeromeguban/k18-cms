<template>
	<div ref="close" id="banks-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Bank</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<div class="input-form">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Bank Name
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
								</label>
								<input v-model="form.name" type="text" name="name" class="form-control" placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Description
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('description')" v-html="form.errors.get('description')" />
								</label>
								<input v-model="form.description" type="text" name="description" class="form-control"
									placeholder="">
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
	components: {},
	props: ['bank'],
	data() {
		return {
			form: {},
			isLoading: false,
		}
	},
	created() {
		this.form = new Form(this.bank);
	},
	watch: {
		'bank'() {
			this.form = new Form(this.bank);
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/banks/${this.bank.id}`)
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Bank Successfuly Updated!',
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

		closeModal() {

			setTimeout(() => this.$refs.close.click(), 3000);

		},

	}
}
</script>

