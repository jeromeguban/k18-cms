<template>
	<div ref="close" id="header-properties-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Property</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>

				<form enctype="multipart/form-data">
					<div class="modal-body p-10">
						<div class="flex flex-col w-full">
							<div class="w-full ml-4">
								<label class="form-label w-full flex flex-col sm:flex-row mt-2">
									Label
								</label>
								<input v-model="properties.label" type="text"
									class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 w-full"
									placeholder="Label">
							</div>
							<div class="w-full ml-4">
								<label class="form-label w-full flex flex-col sm:flex-row mt-2">
									Icon
								</label>
								<vfa-picker v-model="properties.icon">
									<template v-slot:activator="{ on }">
										<input v-model="properties.icon" @click="on" type="text" name="icon"
											class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full"
											placeholder="Icon">
									</template>
								</vfa-picker>
							</div>
							<div class="w-full ml-4">
								<label class="form-label w-full flex flex-col sm:flex-row mt-2">
									Link
								</label>
								<input v-model="properties.link" type="text"
									class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 w-full"
									placeholder="Link">
							</div>
						</div>
					</div>

					<vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader>
					<!-- BEGIN: Modal Header -->
					<div class="modal-footer text-right">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
						<button @click.prevent="submit" class="btn btn-primary w-20">Update</button>
					</div>
					<!-- END: Modal Header -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ['property'],

	data() {
		return {
			properties: {},
			isLoading: null,
		}
	},

	created() {
		this.properties = new Form(this.property);
	},

	watch: {
		'property'() {
			this.properties = new Form(this.property);
		},

	},

	methods: {
		'submit'() {
			this.$emit('updated', this.properties);
			this.closeModal();
		},
		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	},
}
</script>