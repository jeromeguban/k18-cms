<template>
	<div ref="close" id="courier-statuses-edit" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Edit Courier Status</h2>

				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="input-form">
							<div class="input-form">
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Inhouse Status
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('status_id')" v-html="form.errors.get('status_id')" />
									</label>
									<multiselect searchable="true" 
												:options="delivery_statuses" 
												name="delivery_status"
												v-model="delivery_status" 
												:custom-label="getInHouseStatusCustomLabel"
												customMaxWidth="100%"/>
								</div>
								<div class="w-full mr-1">
									<label class="form-label w-full flex flex-col sm:flex-row">
										Courier
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('courier_id')" v-html="form.errors.get('courier_id')" />
									</label>
									<multiselect searchable="true" 
												:options="couriers" 
												name="courier"
												v-model="courier" 
												label="name"
												customMaxWidth="100%"/>
								</div>
								<label class="form-label w-full flex flex-col sm:flex-row">
									Code
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('status_code')" v-html="form.errors.get('status_code')" />
								</label>
								<input v-model="form.status_code" type="text" name="status_code" class="form-control"
									placeholder="">

								<label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
									Description <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
										v-if="form.errors.get('description')" v-html="form.errors.get('description')" />
								</label>
								<input v-model="form.description" type="text" name="description" class="form-control"
									placeholder="">


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
	props:['courier_status'],
	data() {
		return {
			form: {},
			isLoading : false,
			delivery_status : null,
			delivery_statuses : [],
			courier : null,
			couriers : [],	
		}
	},
	created() {
		this.form = new Form(this.courier_status);
		this.getDeliveryStatuses();
		this.getCouriers();
	},
	watch:{
        'courier_status' () {
        	this.form = new Form(this.courier_status);
        },

        'delivery_status'() {
			this.form.status_id = this.delivery_status.id;
		},

		'courier'() {
			this.form.courier_id = this.courier.id;
		},
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/courier-statuses/${this.courier_status.id}`)
			.then(() => {
				this.isLoading = false;
		     	this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
				setTimeout(() => resolve({
					title: 'Success!',
					body: 'Courier Statuses Successfuly Updated!',
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

		getDeliveryStatuses() {
			axios.get('delivery-statuses')
			.then(({data})=>{
				this.delivery_statuses = data;
				this.getDeliveryStatus();
			});
		},

		getDeliveryStatus() {
			if(this.delivery_statuses && this.form.status_id) {
				this.delivery_status = this.delivery_statuses.find((delivery_status) => {
					return delivery_status.id == this.form.status_id
				});
			}
		},

		getCouriers() {
			axios.get('couriers')
			.then(({data})=>{
				this.couriers = data;
				this.getCourier();
			});
		},

		getCourier(){
			if(this.couriers && this.form.courier_id) {
				this.courier = this.couriers.find((courier) => {
					return courier.id == this.form.courier_id
				});
			}
		},

		getInHouseStatusCustomLabel({code, description}) {
			return `${code} - ${description}`;
		},
	}
}
</script>
