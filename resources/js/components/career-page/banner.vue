<template>
	<div>
		<div ref="close" id="careers-banner" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Set Courier Banner</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">

								<input-file id="banner" class="mt-4" label="Banner" name="banner" :multiple="false"
									v-model="form.banner"
									:errorMessage="form.errors.has('banner') ? form.errors.get('banner') : ''"
									accept="image/png, image/jpeg, image/jpg" />

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
	props: ['career'],
	data() {
		return {
			form: new FormUpload({
				banner: '',
			}),
			career: '',
			isLoading: false,
		}
	},

	created() {
		this.career = this.career;
		this.isLoading = false;
	},

	watch: {
		'career'() {
			this.isLoading = false;
		},
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.post(`/careers/${this.career.career_id}/banner`)
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Banner successfully Uploaded!',
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
				}).catch((error) => {
					this.isLoading = false;
				});


		},

		closeModal() {

			setTimeout(() => this.$refs.close.click(), 3000);

		},
	}
}
</script>
