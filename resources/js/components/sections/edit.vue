
<template>
	<div ref="close" id="sections-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Section</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>

				<form enctype="multipart/form-data">
					<div class="modal-body p-10">

						<div class="input-form">
							<div>
								<label class="form-label w-full flex flex-col sm:flex-row">
									Section Type
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('section_type')" v-html="form.errors.get('section_type')" />
								</label>
								<select
									class="border border-solid border-gray-300 mb-2 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-9"
									name="section_type" v-model="form.section_type">
									<option value="banner-upload">Banner Upload</option>
									<option value="item-posting">Item Posting</option>
								</select>
							</div>
							<div v-if="form.section_type == 'banner-upload'">
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

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Label <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('section_label')" v-html="form.errors.get('section_label')" />
								</label>
								<input v-model="form.section_label" type="section_label" name="section_label"
									class="form-control" placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row">
									Type
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('type')" v-html="form.errors.get('type')" />
								</label>
								<select
									class="border border-solid border-gray-300 mb-2 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-9"
									name="type" v-model="form.type">
									<option value="50%">50%</option>
									<option value="100%">100%</option>
								</select>
							</div>
							<div v-if="form.section_type == 'item-posting'">
								<label class="form-label w-full flex flex-col sm:flex-row">
									Label
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('section_label')" v-html="form.errors.get('section_label')" />
								</label>
								<input v-model="form.section_label" type="text" name="section_label" class="form-control"
									placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row">
									Name
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
								</label>
								<input v-model="form.name" type="text" name="name" class="form-control" placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row">
									Categories :
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('parameters')" v-html="form.errors.get('parameters')" />
								</label>
								<multiselect 
											 v-model="form.parameters.categories" 
											:options="categories"
											track-by="category_id" 
											name="categories"
											label="category_name"
											:multiple="true"
											customMaxWidth="100%"/>

								<label class="form-label w-full flex flex-col sm:flex-row">
									Tags :
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('parameters')" v-html="form.errors.get('parameters')" />
								</label>
								<multiselect :multiple="true" 
											v-model="form.parameters.tags" 
											:options="tags"
											track-by="id"  
											name="tags"
											label="name" 
											customMaxWidth="100%"/>

								

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
	props:['section'],
	data() {
		return {
			form: {},
			isLoading : false,
			tag : [],
			tags : [],
			category : [],
			categories : [],
		}
	},
	created() {
		this.form = new Form(this.section);
		this.getCategories();
		this.getTags();
		if(this.section.parameters) {
			this.form.parameters = this.section.parameters ? JSON.parse(this.section.parameters) : null;
		}
	},

    watch:{
		'section' () {
			this.form = new Form(this.section);
			if(this.section.parameters) {
				this.form.parameters = this.section.parameters ? JSON.parse(this.section.parameters) : null;
			}
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`sections/${this.section.id}`)
			.then(()=>{
				 this.isLoading = false;
                 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Section successfully updated!',
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
				this.$emit('reload');

			})
			.catch(()=>{
				this.isLoading = false;
			});
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

		getCategories() {
			axios.get('/categories')
			.then(({data})=>{
				this.categories = data;
			});
		},

		getTags() {
			axios.get('/tags?active=1')
			.then(({data})=>{
				this.tags = data;
			});
		},
	}
}
</script>
