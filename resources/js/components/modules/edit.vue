<template>

	<div ref="close" id="modules-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Module</h2>

				</div>

				<form @keydown.enter.prevent="" @submit.prevent="submit()"
					@keydown="form.errors.clear($event.target.name)">
					<div class="modal-body p-10">

						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								Module Name
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
							</label>
							<input v-model="form.name" type="text" name="name" class="form-control" placeholder="">


							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Permission
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('module_permissions')"
									v-html="form.errors.get('module_permissions')" />
							</label>
							<input v-model="type" type="text" name="description" class="form-control"
								placeholder="Permission Type" v-on:keyup.13="storePermission()">
						</div>

						<div class="flex flex-wrap items-center mt-4" v-if="form.module_permissions.length > 0">
							<label class="mr-2 mb-2">Permission types: </label>
							<span class="flex items-center px-2 py-1 rounded-full bg-theme-1 text-white mr-1"
								v-for="(module_permission, index) in form.module_permissions">
								<span>{{ module_permission.permission }}</span>
								<span class="text-white cursor-pointer ml-2" @click="removePermission(index)">
									<svg class="fill-current w-3 h-3" xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 320 512">
										<path
											d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
									</svg>
								</span>
							</span>
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
	props:['module'],
	data() {
		return {
			type: '',
			form: {},
			isLoading : false,

		}
	},
	created() {
		this.form = new Form(this.module);
		this.show();
	},
	watch:{
		'module' () {
		this.form = new Form(this.module);
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/modules/${this.module.id}`)
			.then(() => {
			this.isLoading = false;
		    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Module Successfuly Updated!',
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
		show() {
				axios.get('/modules/' + this.module.id, {
                    params: {
                        relations: [ 'module_permissions' ]
                    }
                }).then((response) => {
                    this.form = new Form(response.data);
				})
				.catch((error) => {
					console.log(error);
				});
			},
    	storePermission() {
    		if(this.type == "") {
				return;
			}
			axios.post('/modules/' + this.module.id + '/permissions', {
				permission : this.type,
			}).then( response => {
				if(response.data.success == 1) {
					this.form.module_permissions.push(response.data.data);
					this.type = '';
				}
			}).catch((error) => {
				console.log(error);
			});
    	},
    	removePermission(index) {
			axios.delete('/modules/' + this.module.id + '/permissions/' + this.form.module_permissions[index].id)
				.then( response => {
					if(response.data.success == 1) {
						this.form.module_permissions.splice(index, 1);
					}
				}).catch((error) => {
					console.log(error);
				});
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
