
<template>
	<div ref="close" id="quicklink-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit QuickLink</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>

				<form enctype="multipart/form-data">
					<div class="modal-body p-10">
						<div class="input-form">
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
							<input v-model="form.name" type="text" name="label" class="form-control" placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Label <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('label')" v-html="form.errors.get('label')" />
							</label>
							<input v-model="form.label" type="text" name="label" class="form-control" placeholder="">

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Slogan <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('slogan')" v-html="form.errors.get('slogan')" />
							</label>
							<input v-model="form.slogan" type="text" name="label" class="form-control" placeholder="">

							<div class="flex mt-3 ">
								<div class="w-full mr-2">
									<label for="validation-form-2"
										class="form-label w-full flex flex-col sm:flex-row mt-3">
										Gradient Light Color
									</label>
									<colour-picker v-model="form.gradient[0].light" :value="form.gradient[0].light"
										picker="sketch" />
								</div>
								<div class="w-full ml-2">
									<label for="validation-form-2"
										class="form-label w-full flex flex-col sm:flex-row mt-3">
										Gradient Dark Color
									</label>
									<colour-picker v-model="form.gradient[0].dark" :value="form.gradient[0].dark"
										picker="sketch" />
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
import ColourPicker from 'vue-colour-picker'
export default {
	props: ['quicklink'],
	components: {ColourPicker},
	data() {
		return {
			isLoading : false,
			form: {}
		}
	},
	created() {
		this.form = new Form(this.quicklink);
		this.form.gradient = JSON.parse(this.form.gradient);
		
	},

    watch:{
		'quicklink' () {
		this.form = new Form(this.quicklink);
		this.form.gradient = JSON.parse(this.form.gradient);
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`quicklinks/${this.quicklink.id}`)
			.then(()=>{
				 this.isLoading = false;
                 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Quicklink successfully updated!',
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
				this.$emit('updated');

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
