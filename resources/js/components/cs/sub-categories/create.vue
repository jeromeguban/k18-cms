<template>
	<div>
		<div ref="close" id="sub-categories-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Sub Category</h2>
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
									Sub Category Code
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('sub_category_code')"
										v-html="form.errors.get('sub_category_code')" />
								</label>
								<input v-model="form.sub_category_code" type="text" name="sub_category_code"
									class="form-control" placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Sub Category Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('sub_category_name')"
										v-html="form.errors.get('sub_category_name')" />
								</label>
								<input v-model="form.sub_category_name" type="text" name="sub_category_name"
									class="form-control" placeholder="">

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
	props:['category'],
	data() {
		return {
            form:new Form({
                sub_category_code : '',
                sub_category_name : '',
				department_id : '',
				icon : ''
            }),
		}
	},

	created() {

	},

	methods: {
		submit() {
			this.form.department_id = this.category.department_id;
			this.form.post('/categories/' + this.$route.params.id + '/sub-categories')
			.then(()=>{
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Sub Category successfully created!',
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
