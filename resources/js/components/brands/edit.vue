<template>
	<div ref="close" id="brands-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Brand</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<div class="input-form">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Brand Code
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('brand_code')" v-html="form.errors.get('brand_code')" />
								</label>
								<input v-model="form.brand_code" type="text" name="brand_code" class="form-control"
									placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Brand Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('brand_name')" v-html="form.errors.get('brand_name')" />
								</label>
								<input v-model="form.brand_name" type="text" name="brand_name" class="form-control"
									placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Caption <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('caption')" v-html="form.errors.get('caption')" />
								</label>
								<input v-model="form.caption" type="text" name="caption" class="form-control"
									placeholder="">

								<div class="flex">
									<div>
										<label for="validation-form-2"
											class="form-label w-full flex flex-col sm:flex-row mt-3"
											@change="changeActive">
											Active
										</label>
										<div class="w-24 mt-1">
											<label for="settings" class="cursor-pointer">
												<input type="checkbox" class="show-code form-check-switch mr-0 "
													:checked="form.active" @change="changeActive">
											</label>
										</div>
									</div>

									<div>
										<label for="validation-form-2"
											class="form-label w-full flex flex-col sm:flex-row mt-3"
											@change="changeFeatured">
											Featured
										</label>
										<div class="w-24 mt-1">
											<label for="settings" class="cursor-pointer">
												<input type="checkbox" class="show-code form-check-switch mr-0 "
													:checked="form.featured" @change="changeFeatured">
											</label>
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
export default {
	components: {},
	props:['brand'],
	data() {
		return {
			form: {},
			isLoading : false,
		}
	},
	created() {
		this.form = new Form(this.brand);
	},
	watch:{
        'brand' () {
        this.form = new Form(this.brand);
        },
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/brands/${this.brand.brand_id}`)
			.then(() => {
				this.isLoading = false;
		     	this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Brand Successfuly Updated!',
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

		changeActive() {
			this.form.active = !this.form.active;
		},

		changeFeatured() {
			this.form.featured = !this.form.featured;
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
