<template>
	<div ref="close" id="password-reset" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Password Reset</h2>
				</div>
				<form class="">
					<div class="modal-body p-10 overflow-auto  sideform-container--height">
						<div class="input-form">
							<div>

								<div>
									<label class="form-label w-full flex flex-col sm:flex-row">
										Generate Password
									</label>
									<div class="flex flex-row">
										<input v-on:focus="$event.target.select()" ref="clone" readonly
											v-model="generated_password" type="text" name="username"
											class="form-control" placeholder="">
										<button @click.prevent="copy()" class="btn btn-primary w-20 ml-4">Copy</button>
										<button @click.prevent="generatePassword()"
											class="btn btn-primary ml-4 w-20">Generate</button>
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
						<button @click.prevent="resetPassword()" class="btn btn-primary w-20">Reset</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
import { VueEditor } from "vue2-editor";
import Datepicker from 'vuejs-datepicker';
export default {
	components: { VueEditor, Datepicker },
	props:['customer'],
	data() {
		return {
			form		: {},
			isLoading   : false,
            generatedPassword: 'GeneratedPassword',
            passwordLength: 16,
            generated_password: ''
		}
	},

	computed: {
  		
    },
    

	created() {
        this.form = new Form(this.customer);
        this.generatePassword();
	},

	watch:{
		'customer' () {
			this.form = new Form(this.customer);
		},

	},

	methods: {

		resetPassword() {
			this.isLoading = true;
			this.form.patch(`/password-reset/${this.customer.customer_id}`)
			.then(() => {
			 this.isLoading = false;
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Customer Successfuly Updated!',
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

        generatePassword() {
            if (!this.passwordLength) return
           
            let password = ""
            let characters = "abcdefghijklmnopqrstuvwxyz"
            characters += "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~"
            for (let i = 0; i < this.passwordLength; i++) {
                password += characters.charAt(Math.floor(Math.random() * characters.length))
            }
            this.generated_password = password
            this.form.password = password
        },

        copy() {
            this.$refs.clone.focus();
            document.execCommand('copy');
        }

	}
}
</script>
