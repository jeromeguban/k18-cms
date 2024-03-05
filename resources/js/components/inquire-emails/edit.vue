<template>
	<div ref="close" id="inquire_emails-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Contact Us Mailer Settings</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">

						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								Name
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
							</label>
							<input v-model="form.name" type="text" name="name" class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row mt-3">Mail for</label>
							<select
								class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
								name="category" v-model="form.category">
								<option value="Retail">Retail</option>
								<option value="Auction">Auction</option>
							</select>

							<div class="flex w-1/2 mt-4">
								<a href="" @click.prevent="addRow()"
									class="btn btn-primary btn-primary-shadow mt-4 md:mt-0 "><svg
										class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 512 512">
										<path
											d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
									</svg>Add Email(s)</a>
							</div>

							<span class="text-red-500 my-2 flex items-center text-2xs" v-if="form.errors.has('emails')"
								v-text="form.errors.get('emails')" />

							<div class="sm:mt-6 mt-0">
								<div class="flex w-full" v-for="(emails, index) in form.emails">
									<div class="flex justify-center w-full ">
										<div class="flex w-8 py-3 pl-3">
											<span>{{ index + 1 }}</span>
										</div>
										<div class="w-full ml-4">
											<input v-model="form.emails[index]" type="text"
												class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
												placeholder="Email">

											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
												v-if="form.errors.get('emails.'+index)"
												v-html="form.errors.get('emails.'+index)" />

										</div>
										<div class="flex flex-col w-full mt-4">
											<a href="">
												<div class="flex items-center justify-center text-blue-600 "
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
	components: {},
	props:['inquire_email'],
	data() {
		return {
			form: {},
			isLoading : false,
		}
	},
	created() {
		this.form = new Form(this.inquire_email);

		if(this.inquire_email.emails){
			this.form.emails = this.inquire_email.emails ? JSON.parse(this.inquire_email.emails) : null;
		}
	},
	watch:{
		'inquire_email' () {
			this.form = new Form(this.inquire_email);

			if(this.inquire_email.emails){
				this.form.emails = this.inquire_email.emails ? JSON.parse(this.inquire_email.emails) : null;
			}
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/inquire-emails/${this.inquire_email.id}`)
			.then(() => {
			 this.isLoading = false;
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Store Successfuly Updated!',
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
