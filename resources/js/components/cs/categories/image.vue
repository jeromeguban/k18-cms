<template>
	<div ref="close" id="categories-image" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Category</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">

							<input-file id="image" class="mt-4" label="Image" name="image" :multiple="false"
								v-model="form.image"
								:errorMessage="form.errors.has('image') ? form.errors.get('image') : ''"
								accept="image/png" />

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
</template>
<script>
export default {
   components: {},
	props:['category'],
	data() {
		return {
          	form: new FormUpload({
                image : '',
			}),
            category  : '',
			isLoading : false,
		}
	},
	created() {
        this.category = this.category;
        this.isLoading = false;
	},
	watch:{
        'category' () {
        	this.isLoading = false;
        },
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.post(`/category/${this.category.category_id}/image`)
			.then(()=>{
                this.isLoading = false;
                this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Image successfully Updated!',
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
