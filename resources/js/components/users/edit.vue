<template>

	<div ref="close" id="users-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit User</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
                    <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                </button> -->
				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<label for="validation-form-1" class="form-label w-full flex flex-col sm:flex-row">
								First Name
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('first_name')" v-html="form.errors.get('first_name')" />
							</label>
							<input v-model="form.first_name" id="validation-form-1" type="text" name="first_name"
								class="form-control" placeholder="">


							<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Last Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('last_name')" v-html="form.errors.get('last_name')" />
							</label>
							<input v-model="form.last_name" id="validation-form-2" type="text" name="last_name"
								class="form-control" placeholder="">


							<label for="validation-form-3" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Email <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('email')" v-html="form.errors.get('email')" />
							</label>
							<input v-model="form.email" id="validation-form-3" type="email" name="email"
								class="form-control" placeholder="">


							<label for="validation-form-4" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Phone <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('phone')" v-html="form.errors.get('phone')" />
							</label>
							<input v-model="form.phone" id="validation-form-4" type="number" name="phone"
								class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">
								Password <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('password')" v-html="form.errors.get('password')" />
							</label>
							<input v-model="password" type="password" name="phone" class="form-control" placeholder="">

							<label for="validation-form-6" class="form-label w-full flex flex-col sm:flex-row mt-3">
								Roles <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('roles')" v-html="form.errors.get('roles')" />
							</label>
							<multiselect :multiple="true" v-model="form.roles" :options="roles" label="name"
								name="roles" customMaxWidth="100%"></multiselect>

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">
								Stores <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('stores')" v-html="form.errors.get('stores')" />
							</label>
							<multiselect
                                :multiple="true"
                                v-model="form.stores"
                                :options="stores"
                                :custom-label="storeCustomLabel"
                                name="stores"
                                customMaxWidth="100%" />
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
	props:['user'],
	data() {
		return {
			form 	    : new Form(),
            password    : null,
			role 	    : [],
			roles 	    : [],
			store   	: [],
			stores		: [],
			isLoading   : false,
		}
	},

	created() {
		this.form = new Form(this.user);
		this.getStore();
		new Promise((resolve, reject) => {
			this.getRoles();
			resolve();
		}).then(() => {
			this.show();
		})
	},

	watch:{

		'user' () {
            this.password = null;
			this.form = new Form(this.user);
			this.getStore();
			new Promise((resolve, reject) => {
				this.getRoles();
				resolve();
			}).then(() => {
				this.show();
			})
		},

        'password' () {
            this.form.password = this.password;
        }
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/users/${this.user.id}`)
			.then(()=>{
			  this.isLoading = false;
		       this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'User Successfuly Updated!',
						config: {
						timeout: 2000,
						showProgressBar: true,
						closeOnClick: false,
						pauseOnHover: true
						}
					}), 1000);
				}));

				this.form = new Form(this.user);
				// Reload page
                this.closeModal();
				 app.$emit('reload');
			})
			.catch(()=>{
				this.isLoading = false;
			});
		},

		getRoles() {
			axios.get('/roles')
			.then(({data})=>{
				this.roles = data;
				this.getRole();
			});
		},

		show() {
			axios.get('/users/' + this.user.id, {
				params: {
					relations: [ 'roles', 'stores']
				}
			})
			.then((response) => {
				this.form = new Form(response.data);
                this.form.password = null;
			})
			.catch((error) => {
				console.log(error);
			});
		},

		getStore() {
			axios.get('/stores')
			.then(({data})=> {
				this.stores =data;
			});
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

        storeCustomLabel({ code, store_company_code}) {
            return `${code} - ${store_company_code}`
        },

	}
}
</script>
