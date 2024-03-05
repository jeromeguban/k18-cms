<template>
	<div id="payable-store-items" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Payable Items </h2>
					<span class="px-4 py-3 rounded-full bg-theme-10  text-white mr-1"> {{store.store_code}} -
						{{store.store_name}} </span>
				</div>

				<form class="">
					<div class="modal-body p-10">
						<div class="overflow-x-auto mt-6">
							<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
								<table class="table table-report -mt-2">
									<thead>
										<tr>
											<th>Item Name</th>
											<th>Sub Total Amount</th>
											<th>Total Sold Amount</th>
											<th>Discount Total Amount</th>
											<th>Total Commission</th>
											<th>Payable Amount</th>
											<!-- <th>Remarks</th> -->
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item, index) in items" v-if="items.length > 0">
											<td>{{ item.name }}</td>
											<td>{{ item.sub_total_amount | moneyFormat }}</td>
											<td>{{ item.total_sold_amount | moneyFormat }}</td>
											<td>{{ item.discount_total_amount | moneyFormat }}</td>
											<td>{{ item.total_commission | moneyFormat}}</td>
											<td>{{ item.payable_amount | moneyFormat }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<vue-snotify></vue-snotify>
					<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
						opacity="5" disableScrolling="false" name="dots"></loader>
					<!-- BEGIN: Modal Footer -->
					<div class="modal-footer text-right">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline-secondary w-20 mr-1">Close</button>
					</div>
					<!-- END: Modal Footer -->
				</form>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ['store'],
	data() {
	return {
		items 		: [],
		isLoading : false,
	}
	},

	created() {
        this.store
		this.getStoreItems();
	},

	watch:{
	
	'store'() {
	     this.getStoreItems();
	}
	
	},
	
	methods: {		
		getStoreItems() {
		this.isLoading = true;
		axios.get(`/stores/${this.store.store_id}/payable/${this.store.id}/items?store_id=${this.store.id}`)
		.then(({data})=>{
			this.items = data;
			this.isLoading = false;
		});
	},
	}
}
</script>