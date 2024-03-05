
<template>
	<div ref="close" id="header-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Header</h2>
				</div>

				<form enctype="multipart/form-data">
					<div class="modal-body p-10">

						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								Type
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('type')" v-html="form.errors.get('type')" />
							</label>
							<select
								class="border border-solid border-gray-300 mb-2 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-9 bg-gray-300"
								name="type" v-model="form.type" disabled>
								<option value="Header">Header</option>
								<option value="Sidebar">Sidebar</option>
								<option value="Footer">Footer</option>
							</select>

							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Label
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
									v-if="form.errors.get('label')" v-html="form.errors.get('label')" />
							</label>
							<input v-model="form.label" type="text" name="label" class="form-control" placeholder="">

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
								Link
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('link')" v-html="form.errors.get('link')" />
							</label>
							<input v-model="form.link" type="url" name="link" class="form-control" placeholder="">

						</div>
					</div>

					<vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader>
					<!-- BEGIN: Modal Header -->
					<div class="modal-footer text-right">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
						<button @click.prevent="submit()" class="btn btn-primary w-20">Update</button>
					</div>
					<!-- END: Modal Header -->
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
							body: 'Header successfully updated!',
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

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

		addRow() {
			this.form.properties.push({
				label: null,
				icon: null,
				link: null,
				sequence: 0,
			})
		},

		deleteRow(index) {
			this.form.properties.splice(index, 1)
		},


	}
}
</script>
