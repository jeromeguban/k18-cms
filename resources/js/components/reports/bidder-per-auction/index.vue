<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>List of Bidder per Auction</h4>
            </template>

            <template slot="additionals">

                <div class="flex flex-col w-2/5 pr-1 mr-5 ml-5">
                    <label class="text-2xs font-semibold">Auction</label>
                    <multiselect
                        v-model="auction"
                        :options="auctions"
                        :custom-label="getAuctionLabel"
                        name="getAuctionLabel"
                        customMaxWidth="100%"
                    ></multiselect>
                </div>


                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate </button>
                </div>
                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportBidderPerAuction()"> Export </button>
                </div>
            </template>

            <template slot="bid_amount" slot-scope="props">
                    <span>{{ props.item.bid_amount | moneyFormat }}</span>
            </template>

        </table-template>
    </div>
</template>
<script>
    import TableTemplate from "../../../Table";

    export default {
        components: { TableTemplate },
    	data() {
    		return {
                fields : {
                    bidder_number        : 'Bidder Number',
                    customer_firstname	 : 'First Name',
                    customer_lastname 	 : 'Last Name',
                    email 				 : 'Email',
                    mobile_no 			 : 'Mobile No',
                    created_at 			 : 'Customer Created At' 
                },
    			auction     : null,
    			auctions    : [],
    			auction_id  : '',
    			show        : false,
                link        : '',
                print_link  : '',
                params: {
                    filter     : null,
                    auction_id : null,
                },
    		}
    	},

    	created() {
			this.getAuctions();
		},

		watch: {
			'auction'() {
				this.auction_id         = this.auction.auction_id;
                this.params.auction_id  = this.auction.auction_id;
			},

        },
    	methods: {
    		getAuctions() {
				axios.get('/auctions')
				.then(({data})=>{
					this.auctions =data;
				});
			},

            getAuctionLabel({auction_number, name}) {
				return `${auction_number} - ${name}`;
			},

			generate() {
                this.link = 'bidder-per-auctions/generate'
                app.$emit('reload');
			},

            exportBidderPerAuction() {

                let link = 'bidder-per-auctions/export?'

                let parameters = Object.keys(this.params)
                                        .filter(parameter => {
                                            return this.params[parameter]
                                        })
                                        .map(parameter => {
                                            return `${parameter}=${this.params[parameter]}`
                                        })
                                        .join("&")

                window.open(link + parameters, "_blank")
            },
    	}
    }
</script>
