<template>
	<div ref="close" id="email_templates-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Email Template</h2>
				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<label class="form-label w-full flex flex-col sm:flex-row">
								Auction
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('auction_id')" v-html="form.errors.get('auction_id')" />
							</label>
							<multiselect v-model="auction" :options="auctions" :custom-label="getAuctionLabel"
								name="getAuctionLabel" customMaxWidth="100%"></multiselect>

							<label class="form-label w-full flex flex-col sm:flex-row">
								Title
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('title')" v-html="form.errors.get('title')" />
							</label>
							<input v-model="form.title" type="text" name="title" class="form-control" placeholder="">

							<label class="form-label w-full flex flex-col sm:flex-row">
								Body
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('body')" v-html="form.errors.get('body')" />
							</label>
							<vue-editor v-model="form.body"></vue-editor>

							<label class="form-label w-full flex flex-col sm:flex-row">
								Type
								<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
									v-if="form.errors.get('type')" v-html="form.errors.get('type')" />
							</label>
							<select
								class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
								name="type" v-model="form.type">
								<option value="Approved">Approved</option>
								<option value="Declined">Declined</option>
							</select>
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
	props : ['email'],
	data() {
		return {
			form : {},
			auction : null,
			auctions: [],
			isLoading : false,
		}
	},
	created() {
		this.form = new Form(this.email);
		this.getAuctions();
	},
	watch:{
		'email' () {
			this.form = new Form(this.email);
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/access-request-email-templates/${this.email.id}`)
			.then(() => {
			 this.isLoading = false;
		     this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Email Template Type Successfuly Updated!',
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

		getAuctions() {
			axios.get('/auctions')
			.then(({data})=>{
				this.auctions =data;
				this.getAuction()
			});
		},

		getAuctionLabel({auction_number, name}) {
			return `${auction_number} - ${name}`;
		},

		getAuction() {
			if(this.auctions && this.form.auction_id) {
				this.auction = this.auctions.find((auction)=>{
					return auction.auction_id == this.form.auction_id
				});
			}
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
