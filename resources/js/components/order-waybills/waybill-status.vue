<template>
<div ref="close" id="waybill-update-status" class="modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary-1 text-theme-2">
				<h2 class="font-medium text-base mr-auto">Update Waybill Status</h2>
			</div>
			<form class="">
				<div class="modal-body p-10">
					<div class="input-form">
						<label class="form-label w-full flex flex-col sm:flex-row">
							Status
							<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
								v-if="form.errors.get('status')" v-html="form.errors.get('status')" />
						</label>
						<multiselect searchable="true" 
									:options="delivery_statuses" 
									name="delivery_status"
									v-model="delivery_status" 
									:custom-label="getInHouseStatusCustomLabel"
									customMaxWidth="100%"/>
					</div>
				</div>
				<vue-snotify></vue-snotify>
				<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
					opacity="5" disableScrolling="false" name="dots"></loader>
				<!-- BEGIN: Modal Footer -->
				<div class="modal-footer text-right">
					<button type="button" data-dismiss="modal"
						class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
					<button @click.prevent="submit()" class="btn btn-primary w-20">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
</template>
<script>
export default {
	components: {},
	props : ['order_waybill'],
	data(){
		return {
			form: new Form({
				delivery_status_id : '',
				status : '',
				order_id : '',
				tracking_number : '',
			}),
			isLoading : false,
			delivery_status : null,
			delivery_statuses : [],
		}
	},
	created() {
		this.form.order_id = this.order_waybill.order_id;
		this.form.tracking_number = this.order_waybill.tracking_number;
		this.getDeliveryStatuses();
	},

	watch : {
		'delivery_status'() {
			this.form.status = this.delivery_status.code;
			this.form.delivery_status_id = this.delivery_status.id;
		},
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/update-order-waybill/${this.order_waybill.id}/status`)
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Status successfully created!',
						config: {
						timeout: 2000,
						showProgressBar: true,
						closeOnClick: false,
						pauseOnHover: true
						}
					}), 1000);
				}));
				 // this.isLoading = false;
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
				if(this.order_waybill.waybill_status != null) {
					this.getDeliveryStatus();
				}
			});
		},

		getDeliveryStatus() {
			if(this.delivery_statuses && this.order_waybill.waybill_status) {
				this.delivery_status = this.delivery_statuses.find((delivery_status) =>{
					return delivery_status.id == this.order_waybill.delivery_status_id
				});
			}
		},

		getInHouseStatusCustomLabel({code, description}) {
			return `${code} - ${description}`;
		},
	}
}
</script>