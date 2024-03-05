<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>List of Bid Histories</h4>
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

                    <div class="flex flex-col w-2/5 pr-1 mr-5 ml-5">
                    <label class="text-2xs font-semibold">Posting</label>
                    <multiselect
                        v-model="posting"
                        :options="postings"
                        :custom-label="getPostingLabel"
                        name="getPostingLabel"
                        customMaxWidth="100%"
                    ></multiselect>
                </div>

                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate </button>
                </div>
                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportBidHistory()"> Export </button>
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
                    lot_number        : 'Lot No.',
                    description       : 'Description',
                    starting_time     : 'Starting Time',
                    ending_time       : 'Ending Time',
                    customer_firstname: 'Bidder Firstname',
                    customer_lastname : 'Bidder Lastname',
                    bidder_number     : 'Bidder Number',
                    bid_amount        : 'Bid Amount',
                    created_at        : 'Bidded On',
                    max_bid_indicator : 'Max Bid Indicator',
                },

    			auction     : null,
    			auctions    : [],
    			auction_id  : '',
    			item        : null,
    			items       : [],
    			posting     : '',
    			postings    : [],
    			posting_id  : '',
    			show        : false,
                link        : '',
                print_link  : '',

                params: {
                    filter     : null,
                    auction_id : null,
                    posting_id : null,
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
                this.posting_id         = null;
                this.postings           = [];
                this.params.posting_id  = null;
                this.getAuctionPosting();
			},
			'posting'() {
				this.posting_id         = this.posting.posting_id;
                this.params.posting_id  = this.posting.posting_id;
			},

        },
    	methods: {
    		getAuctions() {
				axios.get('/auctions')
				.then(({data})=>{
					this.auctions =data;
				});
			},

			getAuctionPosting() {
				axios.get(`auctions/${this.auction_id}/postings`)
				.then(({data})=>{
					this.postings =data;
				});
			},

            getAuctionLabel({auction_number, name}) {
				return `${auction_number} - ${name}`;
			},

            getPostingLabel({lot_number, name}) {
            	return `${lot_number} - ${name}`;
            },

			generate() {
                this.link = 'bid-histories/generate'
                app.$emit('reload');
			},

            exportBidHistory() {

                let link = 'bid-histories/export?'

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
