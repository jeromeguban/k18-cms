<template>
	<div>
		<div ref="close" id="product-details" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Product Details</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
                        <div class="modal-body p-10">
                            <div class="input-form">
                                <div class="flex w-full">
                                    <div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4 w-full">
                                        <div v-if="product" class="flex justify-center border-b border-dashed border-gray-300 py-4">
                                            <img
                                                alt="Banner"
                                                :src="product.banner"
                                                class="w-1/2 h-1/2 image-fit"
                                            />
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Name : {{product.name}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Description : {{product.description | limitDescription(500)}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Quantity : {{product.quantity}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>Unit Price : {{product.unit_price | moneyFormat}}</span>
                                            <span></span>
                                        </div>
                                        <div class="flex justify-between border-b border-dashed border-gray-300 py-4">
                                            <span>SRP : {{product.suggested_retail_price | moneyFormat}}</span>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Close</button>
							<!-- <button @click.prevent="submit()" class="btn btn-primary w-20">Save</button> -->
						</div>
						<!-- END: Modal Footer -->
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
    props: {
        product: {
            type: Object,
            required: true
        },
    },
    components: {
    	
    },
	data() {
		return {
		}
	},
	methods: {
        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
