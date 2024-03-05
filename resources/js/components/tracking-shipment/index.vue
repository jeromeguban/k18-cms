
<template>
	<div ref="close" id="tracking-number" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Shipment Status</h2>
					<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
				</div>

				<form enctype="multipart/form-data">
					<div class="modal-body p-10">
                        <div class="flex">
                            <div class="w-1/2 mr-2">
                                <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4 shadow-md ">
                                    <span class="font-medium pb-4 block text-lg">Shipment Details</span>

                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Tracking Number:</span>
                                        <span>{{ tracking_shipment ? tracking_shipment.tracking_number : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Courier:</span>
                                        <span>{{ tracking_shipment ? tracking_shipment.courier : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Courier Survice:</span>
                                        <span>{{ tracking_shipment ? tracking_shipment.courier_service : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Courier Status:</span>
                                        <span>{{ tracking_shipment ? tracking_shipment.courier_status : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Status:</span>
                                        <span>{{ tracking_shipment ? tracking_shipment.status : '' }}</span>
                                    </div>

                                </div>
                            </div>
                            <div class="w-1/2">
                                <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4 shadow-md ">
                                    <span class="font-medium pb-4 block text-lg">Order Details</span>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Order Number:</span>
                                        <span>{{ order_details.order_id ? order_details.order_id : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Reference Code:</span>
                                        <span>{{ order_details.reference_code ? order_details.reference_code : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Order Number:</span>
                                        <span>{{ order_details.order_id ? order_details.order_id : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Customer Name:</span>
                                        <span>{{ order_details.customer_name ? order_details.customer_name : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Contact Number:</span>
                                        <span>{{ order_details.contact_number ? order_details.contact_number : '' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                        <span>Email:</span>
                                        <span>{{ order_details.email ? order_details.email : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>

					<!-- <vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader> -->
					<!-- BEGIN: Modal Footer -->
					<div class="modal-footer text-right">
						<button
                            type="button"
                            data-dismiss="modal"
							class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ['order_details'],

	data() {
		return {
            tracking_shipment : null,
		}
	},

    created() {
        this.getTrackingNumber();
    },

    watch: {
        'order_details'() {
            this.clearTrackingNumber();

            this.getTrackingNumber();
        }
    },

	methods: {

        getTrackingNumber() {

            axios.get('/track-shipment', {
                params: {
                    tracking_number : this.order_details.tracking_number
                }
            }).then(({data})=>{
                this.tracking_shipment = data;
            });
        },

        clearTrackingNumber() {
            this.tracking_shipment = null
        }
	}
}
</script>
