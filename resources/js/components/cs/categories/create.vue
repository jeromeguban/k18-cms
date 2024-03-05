<template>
	<div>
		<div ref="close" id="categories-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Category</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>

					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">
								<vfa-picker v-model="form.icon">
									<template v-slot:activator="{ on }">
										<label class="form-label w-full flex flex-col sm:flex-row">
											Icon
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
												v-if="form.errors.get('icon')" v-html="form.errors.get('icon')" />
										</label>
										<input v-model="form.icon" @click="on" type="text" name="icon"
											class="form-control mb-4">
									</template>
								</vfa-picker>

								<label class="form-label w-full flex flex-col sm:flex-row">
									Category Code
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('category_code')"
										v-html="form.errors.get('category_code')" />
								</label>
								<input v-model="form.category_code" type="text" name="category_code"
									class="form-control" placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Category Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('category_name')"
										v-html="form.errors.get('category_name')" />
								</label>
								<input v-model="form.category_name" type="text" name="category_name"
									class="form-control" placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3"
									@change="changeFeatured">
									Color
								</label>
								<div class="w-24 mt-1">
									<label for="settings" class="cursor-pointer">
										<colour-picker v-model="form.color" :value="form.color" no-input
											picker="sketch" />
									</label>
								</div>

								<label for="settings" class="cursor-pointer">
									<input-file id="image" class="mt-4" label="Image" name="image" :multiple="false"
										v-model="form.image"
										:errorMessage="form.errors.has('image') ? form.errors.get('image') : ''"
										accept="image/png, image/jpeg" />
								</label>

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3"
									@change="changeFeatured">
									Featured
								</label>
								<div class="w-24 mt-1">
									<label for="settings" class="cursor-pointer">
										<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
											:checked="form.featured" @change="changeFeatured">
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
	import ColourPicker from 'vue-colour-picker'
export default {
    components: {
    	ColourPicker
    },
	data() {
		return {
            form:new FormUpload({
                category_code : ''   ,
                category_name : ''   ,
                featured      : false,
                icon          : ''   ,
                image         : ''   ,
                color         : ''   ,
			}),

			isLoading : false,
		}
	},

    created() {
        this.form.color
    },

	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/categories')
				.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Category successfully created!',
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

        changeFeatured() {
			this.form.featured = !this.form.featured;
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
