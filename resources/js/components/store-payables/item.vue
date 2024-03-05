<template>
	<div id="exclude-store-items" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<!-- BEGIN: Modal Header -->
				<div class="modal-header bg-primary-1 text-theme-2">
					<h2 class="font-medium text-base mr-auto">Exclude Items {{store.code}} - {{store.store_name}} </h2>
					<span class="px-4 py-3 rounded-full bg-theme-10  text-white mr-1"> Check Items that are not included
						for this payable</span>
				</div>
				<form class="">
					<div class="modal-body p-10">
						<div class="flex flex-row justify justify-left">
							<div v-show="store.store_company_code == 'HASI'" class="flex flex-col w-2/5 pr-1">
								<label class="text-2xs font-semibold">Auction</label>
								<multiselect :multiple="true" v-model="auction" :options="auctions"
									:custom-label="getAuctionLabel" name="getAuctionLabel" customMaxWidth="100%">
								</multiselect>
							</div>

							<div v-show="store.store_company_code == 'HRH'" class="flex flex-col w-2/5 pr-1">
								<label class="text-2xs font-semibold">Search</label>
								<input v-model="search" type="text" name="description" class="form-control mt-2"
									placeholder="Sku | Item Name" />
							</div>

							<div class="flex flex-col w-2/5 pr-1 mt-1 ml-4">
								<label class="text-2xs font-semibold">Limit</label>
								<select class="mt-1 block x-4 text-gray-600 form-select box w-20 h-10" v-model="limit">
									<option index="0" value="100">100</option>
									<option index="1" value="1000">1000</option>
									<option index="2" value="5000">5000</option>
									<option index="3" value="10000">10000</option>
									<option index="4" value="50000">All</option>
								</select>
							</div>
						</div>
						<div class="overflow-x-auto mt-6">
							<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
								<table class="table table-report -mt-2">
									<thead>
										<tr>
											<th>
												<div class="relative block pl-6 min-h-6 mt-1">
													<input type="checkbox" v-model="select_all_items" id="item">
													<label
														class="custom-checkbox-label relative mb-0 align-top font-semibold"
														for="item" />
												</div>
											</th>
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
										<tr v-for="(item, index) in searchableItems" v-if="items.length > 0">
											<td>
												<div class="relative block pl-6 min-h-6 mt-4">
													<input type="checkbox" :id="item.order_posting_id" :value="item"
														v-model="form.items">
													<label
														class="custom-checkbox-label relative mb-0 align-top font-semibold"
														:for="item.order_posting_id"></label>
												</div>
											</td>
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
	props: ['store','from','to'],
	data() {
	return {
		items		: [],
		auction     : [],
		auctions    : [],
		limit		: 100,
		search		: '',
		form		: {
			items	: []
		},
		select_all_items: false,
		isLoading: false,
		params: {
			auctions  : null,
			limit     : []
		},
		item_lists : this.items
	}
	},

	created() {
		this.getAuctions();
		this.getStoreItems();
	},

	watch: {
	'search'() {
		
	},
	
	'store'() {
		this.params.auctions = []
		this.auction = []
		this.getStoreItems();
	 },
		
	'select_all_items'() {

		if(this.select_all_items && this.store.store_company_code == 'HRH')
			this.form.items = this.searchableItems

		if(this.select_all_items && this.store.store_company_code == 'HASI')
			this.form.items = this.items

		if(!this.select_all_items)
			this.form.items = []
		},
	
		'form.items'() {
		
		app.$emit('setItems', this.form.items)
	},

	'auction'() {
		this.params.auctions = this.auction.map(auction => auction.auction_id)
		this.getStoreItems();
	},

	'limit'() {
		this.params.limit = this.limit
		this.getStoreItems();
	 },
	
	},

	computed: {
		searchableItems() {
			return this.items.filter(item => {
				return item.name.toLowerCase().includes(this.search.toLowerCase())
			})
		}
	},
	
	methods: {		
		getStoreItems() {
			this.isLoading = true;
			axios.get(`/stores/${this.store.id}/for-payables-items`,{
					params: this.getParameters()
			}).then(({data})=>{
				this.items = data;
				this.isLoading = false;
			});
		},

		getAuctions() {
			axios.get('/auctions')
			.then(({data})=>{
				this.auctions =data;
			});
		},

		getAuctionLabel({auction_number, name}) {
			return `${auction_number} - ${name}`;
		},

		getParameters() {
			let parameters = {
				from: this.from,
				to: this.to,
				auctions: this.auction.map(auction => auction.auction_id),
				limit: this.limit,
			}
			
			return parameters
		},
	}
}
</script>