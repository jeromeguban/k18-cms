<template>
	<div ref="close" id="terms-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Terms</h2>
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

							<label class="form-label w-full flex flex-col sm:flex-row">
								Type
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('type')" v-html="form.errors.get('type')" />
							</label>
							<select
								class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
								name="type" v-model="form.type">
								<option value="Retail">Retail</option>
								<option value="Auction">Auction</option>
								<option value="Auction">Voucher</option>
							</select>

							<label class="form-label w-full flex flex-col sm:flex-row">
								Terms
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('terms')" v-html="form.errors.get('terms')" />
							</label>
							<vue-editor v-model="form.terms"></vue-editor>
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
import { VueEditor } from "vue2-editor";
export default {
	components: { VueEditor },
	props : ['term'],
	data() {
		return {
			form: {},
			isLoading : false,
		}
	},
	created() {
		this.form = new Form(this.term);
	},
	watch:{
		'term' () {
			this.form = new Form(this.term);
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/terms/${this.term.id}`)
			.then(() => {
			 this.isLoading = false;
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Terms Successfuly Updated!',
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
