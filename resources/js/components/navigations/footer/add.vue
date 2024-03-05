
<template>
	<div ref="close" id="add-column" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Properties</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>
				<form enctype="multipart/form-data">
					<div class="modal-body p-10">
						<div class="mt-0">
							<draggable v-model="form.properties" @change="updateSequence()" group="properties"
								:options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
								class="w-full" style="max-height: 900px;">
								<div class="flex w-full" v-for="(properties, index) in form.properties">
									<div class="flex justify-center w-full ">
										<a href="#" class="flex w-8 py-3 pl-3 mt-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
												viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
												stroke-linecap="round" stroke-linejoin="round" icon-name="move"
												data-lucide="move" class="lucide lucide-move w-4 h-4">
												<polyline points="5 9 2 12 5 15"></polyline>
												<polyline points="9 5 12 2 15 5"></polyline>
												<polyline points="15 19 12 22 9 19"></polyline>
												<polyline points="19 9 22 12 19 15"></polyline>
												<line x1="2" y1="12" x2="22" y2="12"></line>
												<line x1="12" y1="2" x2="12" y2="22"></line>
											</svg>
										</a>
										<div class="flex w-8 py-3 pl-3 mt-2">
											<span>{{ index + 1 }}</span>
										</div>
										<div class="w-full ml-4">
											<input v-model="properties.label" type="text"
												class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
												placeholder="Label">

											<span class="text-theme-6 my-2 flex items-center text-2xs"
												v-if="form.errors.has('properties.' + index + '.label')"
												v-text="form.errors.get('properties.' + index + '.label')" />
										</div>

										<div class="w-full ml-4">
											<vfa-picker v-model="properties.icon">
												<template v-slot:activator="{ on }">
													<input v-model="properties.icon" @click="on" type="text" name="icon"
														class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full"
														placeholder="Icon">
												</template>
											</vfa-picker>
										</div>

										<div class="w-full ml-4">
											<input v-model="properties.link" type="text"
												class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
												placeholder="Link">

											<span class="text-theme-6 my-2 flex items-center text-2xs"
												v-if="form.errors.has('properties.' + index + '.link')"
												v-text="form.errors.get('properties.' + index + '.link')" />
										</div>

										<div class="flex flex-col w-full mt-4">
											<a href="">
												<div class="flex items-center justify-center text-theme-6 "
													@click.prevent="deleteRow(index)">
													<svg class="fill-current w-4 h-4 mr-1" viewBox="0 0 320 512">
														<path
															d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
													</svg>
													<span>Remove</span>
												</div>
											</a>
										</div>

									</div>
								</div>
							</draggable>
							<div class="flex w-full mt-4">
								<a href="" @click.prevent="addRow()"
									class="btn btn-primary btn-primary-shadow w-full mt-4 md:mt-0 ">
									<svg class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 512 512">
										<path
											d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
									</svg>New Properties</a>
							</div>
							<span class="text-theme-6 my-2 flex items-center text-2xs"
								v-if="form.errors.has('properties')" v-text="form.errors.get('properties')" />
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
import draggable from 'vuedraggable';
export default {
	props: ['navigation'],
	components: { draggable },
	data() {
		return {
			form: {},
			isLoading: false,

		}
	},
	created() {
		this.form = new Form(this.navigation);
		this.form.properties = JSON.parse(this.navigation.properties)
	},

	watch: {
		'navigation'() {
			this.form = new Form(this.navigation);
			this.form.properties = JSON.parse(this.navigation.properties)
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`navigations/${this.navigation.id}`)
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Navigation successfully updated!',
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
				.catch(() => {

					this.isLoading = false;

				});
		},

		updateSequence() {
			this.form.patch(`navigations/${this.navigation.id}`)
				.then(() => {
					this.$emit('updated');
				})
				.catch(() => {
				});
		},

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

		addRow() {
			this.form.properties.push({
				label: null,
				icon: null,
				link: null,
			})
		},

		deleteRow(index) {
			this.form.properties.splice(index, 1)
		},


	}
}
</script>
