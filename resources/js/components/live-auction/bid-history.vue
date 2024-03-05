<template>
    <div class="flex flex-col">
        <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center mt-24" v-if="is_loading">
            <svg width="20" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="rgb(30, 41, 59)" class="w-8 h-8">
                <rect y="10" width="15" height="120" rx="6">
                    <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                </rect>
                <rect x="30" y="10" width="15" height="120" rx="6">
                    <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                </rect>
                <rect x="60" width="15" height="140" rx="6">
                    <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                </rect>
                <rect x="90" y="10" width="15" height="120" rx="6">
                    <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                </rect>
                <rect x="120" y="10" width="15" height="120" rx="6">
                    <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                </rect>
            </svg>
            <div class="text-center text-xs mt-2">Loading ...</div>
        </div>
        <div v-if="!is_loading">
            <div class="flex border-b border-gray-200 pb-2">
                <div class="w-3/12">
                    <span class="font-semibold">Bidder Number</span>
                </div>
                <div class="w-3/12">
                    <span class="font-semibold">Bid</span>
                </div>
                <div class="w-4/12">
                    <span class="font-semibold">Time</span>
                </div>
                <div class="w-2/12"></div>
            </div>

            <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center mt-24" v-if="!is_loading  && !bid_histories.length">
                <span> No Current Bids Yet</span>
            </div>

            <div class="flex border-b border-gray-200 py-4" v-if="bid_histories" v-for="(bid_history, index) in sorted_bid_histories">

                <div class="flex items-center w-3/12">
                    <span>{{bid_history.bidder_number == 0 ? 'Floor Bid' : bid_history.bidder_number}}</span>
                </div>
                <div class="flex items-center w-3/12">
                    <span class="font-semibold">{{bid_history.bid_amount | moneyFormat}}</span>
                </div>
                <div class="flex items-center w-4/12">
                    <span class="italic text-gray-600">{{ bid_history.server_time | MMMM D, YYYY - h:mm:ss A }}</span>
                </div>
                <div class="w-2/12">
                    <button class="rounded border border-gray-300 bg-transparent px-6 py-1 hover:shadow-md"
                        v-if="index == 0"
                        @click.prevent="removeBid(bid_history)">
                        <i class="fa-solid fa-xmark text-danger"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'bid-history',
    props: {
		lot: {
			type: Object,
			default() {
                return null
            }
		},
        bid: {
            type: Object,
        }
	},

    data() {
		return {
            bid_histories:[],
            is_loading: true
		}
	},

    computed: {
        'sorted_bid_histories'() {
            return this.bid_histories.sort((a,b) => {
                return  parseFloat(b.bid_amount) -  parseFloat(a.bid_amount)
            })
        }
    },

    watch: {
        'bid.bid_histories': {
            deep: true,
            handler() {
                this.is_loading = true
                this.bid_histories = this.bid.bid_histories
                this.is_loading = false

            }
        }
    },

    methods: {
        bidHistory(){
            this.is_loading = true
            this.bid_histories = []
            axios.get('/posting/' + this.lot.posting_id + '/bid-histories')
                .then(({data}) => {
                    this.is_loading = false
                    this.bid_histories = data
                })
                .catch((error) => {
                    this.is_loading = false
                    console.log(error);
                });
        },
        removeBid(exlcuded_bid_history) {
            let exlcuded_bid_history_index = this.bid_histories.findIndex((bid_history) => {
                    return bid_history.customer_id == exlcuded_bid_history.customer_id
                        && bid_history.bid_amount == exlcuded_bid_history.bid_amount
                })

            if(exlcuded_bid_history_index !== -1){
                this.bid_histories.splice(exlcuded_bid_history_index, 1)
                setTimeout(()=>{
                    this.bid.removeBid(exlcuded_bid_history)
                }, 100)
            }
        }
    }
}
</script>
