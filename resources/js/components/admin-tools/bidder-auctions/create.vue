<template>
	<div>
		<div ref="close" id="banks-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Add New Bidder to Auction</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">
								<label class="text-2xs font-semibold">Search Customer By : <span class="text-red-500">*</span></label>
                                <div class="col-sm-10">
                                    <select  class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1" name="filter" v-model="filter">
                                        <option value="last_name">Last Name</option>
                                        <option value="first_name">First Name</option>
                                        <option value="email">Email</option>
                                        <option value="mobile_no">Mobile No.</option>
                                    </select>
                                </div>

                                <div v-if="filter">
                                    <label class="mt-6 font-semibold">Search Customer By {{ filter }}: <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <input  type="text" class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"  v-model="q" @keypress.enter="searchCustomers">
                                    </div>
                                    <span class="font-semibold w-64"> Select Customer <span class="text-red-500">*</span></span>
                                    <multiselect
                                        v-model="customer"
                                        :options="customers"
                                        name="customer"
                                        :custom-label='customerCustomLabel'
                                        customMaxWidth="100%"
                                    ></multiselect>
                                </div>

                                <label class="form-label w-full flex flex-col sm:flex-row">
									Auction
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('auction_id')"
										v-html="form.errors.get('auction_id')" />
								</label>
								<multiselect :options="auctions" name="auction" v-model="auction"
									:custom-label="auctionLabel" customMaxWidth="100%"></multiselect>
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
						<!-- END: Modal Footer -->
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
            form:new Form({
				auction_id : '',
                customer_id : '',
			}),
			isLoading : false,
            customer : null,
			customers : [],
            auction : null,
            auctions : [],
            filter : null,
            q : null,
		}
	},

    watch : {
		'customer'() {
			this.form.customer_id = this.customer.customer_id;
		},

		'auction'() {
			this.form.auction_id = this.auction.auction_id;
		},
	},

    created() {
		this.getAuctions();
	},

	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/auction-bidders')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Bidder Added Successfully!',
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

        searchCustomers() {
            axios.get('/customers',{
                params: {
                    search_customer: this.q,
                    filter: this.filter
                }
            }).then(({data}) => {
                this.customers = data;
            }).catch((error) => {
                console.log(error);
            });
        },

        getAuctions() {
			axios.get('/auctions')
			.then(({data})=>{
				this.auctions = data;
			});
		},

        customerCustomLabel ({customer_firstname, customer_lastname, email}) {
            return `${customer_lastname}, ${customer_firstname} - ${email}`;
        },

        auctionLabel({name, auction_number}) {
			return `${name} - ${auction_number}`;
		},
	}
}
</script>