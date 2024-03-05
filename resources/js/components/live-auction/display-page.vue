<template>
    <fullscreen v-model="fullscreen">
    <vue-content-loading :width="100" :height="65" class="mt-4" v-if="!lots.length">
        <rect width="100" height="100" />
    </vue-content-loading>
     <body class="fullscreen-wrapper mt-4" style="font-family: Oswald, sans-serif" v-if="lots.length">
        <div class="h-screen bg-white">
            <div class="flex flex-col h-full leading-normal  relative">
                <div class="flex flex-row justify-between px-4">
                    <img class="w-48 mt-2" src="https://hmr.ph/images/hmr-ph-logo.png" alt="">
                    <button type="button" class="mb-6" @click="toggle" v-hotkey="['alt+m','ctrl+m','alt+shift+m']">
                        <img v-if="!fullscreen" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E" alt="" style="height: 24px; width: 24px;">
                        <img v-if="fullscreen" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M4%204H0v2h6V0H4v4zm10%200V0h-2v6h6V4h-4zm-2%2014h2v-4h4v-2h-6v6zM0%2014h4v4h2v-6H0v2z%22/%3E%3C/svg%3E" alt="" style="height: 24px; width: 24px;">
                    </button>

                    <!-- <button @click="show_alert = !show_alert">test test</button> -->
                </div>
                <div class="flex h-full relative">
                    <div class="w-1/2 h-full pt-2">
                        <div class="w-full h-full relative">
                            <div class="bg-light-blue z-50 h-full absolute w-3/5 top-0 left-0"
                                style="border-top-right-radius: 50%; z-index: 50">
                            </div>
                            <div class="relative ml-24" style="z-index:99" v-if="current_lot">
                                <span class="inline-block bg-white px-6 mt-10 rounded-lg shadow-sm text-blue"
                                    style="font-size: 3.8vw">Lot
                                    #{{ current_lot.lot_number }}</span>
                                <img :src="current_lot.banner"
                                    class="rounded-lg block w-auto shadow-lg mt-8 "
                                    style="max-height:63vh; max-width: 92%; border: 8px solid white" alt="">
                            </div>
                            <div class="relative ml-24 mb-10" style="z-index:99"  v-if="!current_lot">
                                <vue-content-loading :width="100" :height="100">
                                    <rect x="1" y="5" rx="2" ry="2" width="30" height="15" />
                                    <rect x="1" y="23" rx="2" ry="2" width="92" height="80" />
                                </vue-content-loading>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-1/2 ml-6">
                        <div class="flex" style="font-size: 2.5vw;" v-if="bid.current_bid">
                            <span class="mr-2">Current Bid:</span>
                            <span class="font-medium underline">{{ bid.current_bid | moneyFormat }}</span>
                        </div>
                        <div class="flex" style="font-size: 2.5vw;" v-if="bid.current_bid">
                            <span class="mr-2">Current Bidder{{ !bid.bidder_number ? '' : '#' }}: </span>
                            <span class="font-medium underline">{{ !bid.bidder_number ? 'Floor Bidder' : bid.bidder_number }}</span>
                        </div>
                        <div class="text-blue mt-4" style="font-size: 3.2vw; ">Asking Bid</div>
                        <div class="block font-medium text-blue" style="font-size: 5.5vw">{{ asking_bid | moneyFormat }}</div>
                        <!-- <div class="flex">
                            <div class="flex items-center mr-10">
                                <i class="fa-sharp fa-solid fa-gauge mr-3" style="font-size: 2.0vw;"></i>
                                <span style="font-size: 2.2vw;">{{ getAttribute('Odometer') && getAttribute('Odometer').value ? getAttribute('Odometer').value : 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid fa-gas-pump fa-4x mr-3" style="font-size: 2.0vw;"></i>
                                <span style="font-size: 2.2vw;"> {{ getAttribute('Fuel Type') && getAttribute('Fuel Type').value ? getAttribute('Fuel Type').value : 'N/A' }}</span>
                            </div>
                        </div> -->

                        <div class="flex flex-col w-3/4 mt-8 rounded-tl rounded-tr shadow-xl lot-list">
                            <div class="flex bg-light-blue rounded-tl rounder-tr">
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Lot #</div>
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Bidder #</div>
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Hammer</div>
                            </div>
                            <vue-content-loading :width="100" :height="40"  v-if="is_bid_histories_loading">
                                <rect width="100" height="40" />
                            </vue-content-loading>
                            <div class="flex item" v-for="(lot_history, index) in lot_histories" v-if="!is_bid_histories_loading">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">{{ lot_history.lot_number }}</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">{{ lot_history.bidder_number ? lot_history.bidder_number : "-"}}</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">{{setMoneyFormat(lot_history.bid_amount)}}</div>
                            </div>
                            <!-- <div class="flex item rounded-bl rounder-br" v-if="!is_bid_histories_loading">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">{{ current_lot.lot_number }}</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">32</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">-</div>
                            </div> -->
                        </div>
                
                        <div class="flex flex-col border border-gray-200 bg-white p-4 w-3/4 mt-4 rounded shadow-md" v-if="next_lot">
                            <span class="mb-2" style="font-size: 1.4vw">Up Next...</span>
                            <vue-content-loading  class="flex flex-col" :width="100" :height="40" v-if="next_lot && is_next_lot_loading">
                                <rect width="100" height="40" />
                            </vue-content-loading>
                            <div class="flex gap-x-5">
                                <div class="w-44 h-32 bg-gray-100 flex items-center justify-center" v-if="next_lot && !is_next_lot_loading">
                                    <img :src="next_lot.banner" class="block w-auto max-w-full max-h-32" alt="">
                                </div>
                                <div class="flex flex-col" v-if="next_lot && !is_next_lot_loading">
                                    <span class="text-blue" style="font-size: 1.5vw">Lot # {{ next_lot.lot_number }}</span>
                                    <h3 class="underline" style="font-size: 2vw">{{ next_lot.name }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 text-white rounded-tr-xl text-center py-6 w-1/2"
                    style="font-size: 3.2vw; z-index:99; background-color: rgba(0,0,0,0.5);">
                    {{current_lot.name}}
                </div>
                <div class="absolute bottm-0 right-0"></div>
                <AlertBidder 
                    :show="show_alert"
                    :location="bid.location"
                    :bidder_number="bid.bidder_number"/>
            </div>
        </div>
    </body>
    
</fullscreen>
</template>
<style>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;500&display=swap');
body {
    font-family: 'Oswald', sans-serif;
}

.text-blue {
    color: #193D7A;
}

.bg-light-blue {
    background-color: #EDF3FF;
}

.lot-list .item {
    border-bottom: 1px dashed #E2E8F0;
}

.lot-list .item:last-child {
    border-bottom: 0;
}
.fullscreen-wrapper {
  background: #333;
  padding: 0px;
}

</style>
<script>
import VueContentLoading from 'vue-content-loading';
import SimulcastBid from '../../Core/SimulcastBid'
import AlertBidder from './alert-bidder.vue'
export default {
    name:'display-page',
    components: {
        VueContentLoading,
        AlertBidder
    },
    data() {
        return {
            fullscreen: false,
            lots:[],
            auction: {},
            bid: new SimulcastBid(this.$route.params.id),
            current_active_lot_index: 0,
            asking_bid: 0,
            lot_histories: [],
            current_lot: null,
            is_bid_histories_loading: true,
            is_bell_playable: false,
            show_alert: false,
            next_lot: null,
            is_next_lot_loading: true,
        }
	},

    created() {
        app.$on('close-alert', ()=>{
            this.show_alert = false
        })
        this.show();
    },

    computed: {
        // 'current_lot'() {
        //     if(this.lots.length)
        //         return this.lots[this.current_active_lot_index]
            
		// 	return null
		// },
        'attribute_data'() {
            if(this.current_lot)
                return Array.isArray(this.current_lot.attribute_data) ? this.current_lot.attribute_data : JSON.parse(this.current_lot.attribute_data)
            
            return null
        },
    },

    watch:{
        'bid.asking_bid':{
            handler() {
                this.asking_bid = this.bid.asking_bid || 0.00
            },
            immediate: true
        },
        'lots' : {
            handler() {
                if(this.lots.length) {

                    if(this.bid.posting_id) {
                        this.current_active_lot_index = this.lots.findIndex((lot)=>{
                            return this.bid.posting_id == lot.posting_id
                        })
                        
                        return
                    }

                    // let lot = this.lots[this.current_active_lot_index]
                    // this.bid.setPostingId(lot.posting_id)
                    
                }
            },
            immediate: true
        },
        'bid.customer_id'() {
            if(this.bid.customer_id > 0 && this.bid.notify)
                this.playNotificationBell()
        },
        'bid.posting_id': {
			// immediate: true,
			handler() {
				if(!this.current_lot || (this.current_lot && (this.current_lot.posting_id != this.bid.posting_id)))
					this.getCurrentLot()
			}
		},
        'bid.bidder_number': {
            handler() {
                this.show_alert = false
                if(this.bid.bidder_number && this.bid.bidder_number > 0 && this.bid.notify)
                    this.show_alert = true
            }
        },
		'lots'() {
            if(!this.current_lot)
			    this.getCurrentLot()
		},

    },
    
    methods: {
        toggle () {
            this.fullscreen = !this.fullscreen
        },

        getAttribute(column_name) {
            return this.attribute_data.find((attribute_data)=>{
                return attribute_data.column_name.toLowerCase() == column_name.toLowerCase()
            })
        },

        setMoneyFormat(bid_amount){
            return parseFloat(bid_amount) > 0 && bid_amount != null ? this.$options.filters.moneyFormat(parseFloat(bid_amount)) : 'PASSED'
        },

        show() {
            axios.get('/auctions/' + this.$route.params.id)
                .then((response) => {
                    this.auction = response.data;
                    this.showLots();
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        showLots(){
            axios.get('/simulcast-auctions/' + this.$route.params.id + '/postings')
                .then((response) => {
                    this.lots = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getLotHistory(){
            this.lot_histories = []
            this.is_bid_histories_loading = true
            axios.get('/simulcast-auction/' + this.current_lot.posting_id + '/history')
                .then(({data}) => {
                    this.lot_histories = data
                    this.is_bid_histories_loading = false
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getNextLot(){
            this.next_lot = null
            this.is_next_lot_loading = true
            axios.get('/simulcast-auction/' + this.current_lot.posting_id + '/next-lot')
                .then(({data}) => {
                    this.next_lot = data[0] ?? null
                    this.is_next_lot_loading = false
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getCurrentLot() {
            this.is_bell_playable = false
			if(this.bid.posting_id){
				if(!this.bid.posting) {
					axios.get(`/api/lots/${this.bid.posting_id}`)
						.then(({data}) => {
							this.current_lot = data
						})
						.catch()
				} else {
					this.current_lot = this.bid.posting
				}
				this.bid.getCurrentBidAndWinner()
                this.getLotHistory()
                this.getNextLot()
                setTimeout(()=>{
                    this.is_bell_playable = true
                },100)
				return
			}

			this.current_lot = this.lots && this.lots.length ? this.lots[0] : null
            // this.getLotHistory()
            setTimeout(()=>{
                this.is_bell_playable = true
            },100)
			
		},

        playNotificationBell() {
            if(this.is_bell_playable)
			    new Audio("/sound-effects/online-bid.mp3").play()
		}
      
    },
}
</script>