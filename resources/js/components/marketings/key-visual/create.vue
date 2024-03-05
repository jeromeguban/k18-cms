<template>
	<div ref="close" id="key-visual-create" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Create New Key Visual</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>

				<form class="" @keydown.enter.prevent="" @submit.prevent="store">
					<div class="modal-body p-10">

						<div class="input-form">

							<input-file id="banner" class="mt-4" label="Desktop Banner" name="banner" :multiple="false"
								v-model="form.banner"
								:errorMessage="form.errors.has('banner') ? form.errors.get('banner') : ''"
								accept="image/png, image/jpeg" />

							<input-file id="mobile_banner" class="mt-4" label="Mobile Banner" name="mobile banner"
								:multiple="false" v-model="form.mobile_banner"
								:errorMessage="form.errors.has('mobile_banner') ? form.errors.get('mobile_banner') : ''"
								accept="image/png, image/jpeg" />


							<label class="form-label w-full flex flex-col sm:flex-row">
								Link
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('link')" v-html="form.errors.get('link')" />
							</label>
							<input v-model="form.link" type="url" name="link" class="form-control" placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
							</label>
							<input v-model="form.name" type="text" name="name" class="form-control" placeholder="">
						</div>

					</div>

					<vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader>
					<!-- BEGIN: Modal Footer -->
					<div class="modal-footer text-right">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
						<button @click.prevent="store()" class="btn btn-primary w-20">Save</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			form:  new FormUpload({
				banner: '',
				mobile_banner:'',
				link: '',
				name: '',
			}),
			isLoading : false,
		}
	},

	methods: {
		store() {
			this.isLoading = true;
			this.form.post('key-visuals')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Key Visual successfully created!',
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
				this.$emit('created');
				this.form.banner = null;
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
