import 'regenerator-runtime/runtime'
import Ws from '@adonisjs/websocket-client/index'
if(!window.ws) {
    window.ws = Ws(window.simulcast_bidding_url, {
        path: 'simulcast-ws',
        reconnectionAttempts: 100000
    }).connect()
}

import Vue from 'vue'
import Modal from '../plugins/Modal/ModalPlugin'
Vue.use(Modal);

class LiveSelling {
	constructor(channel) {
		this.channel = channel
		this.ws = null
		this.current_bid = 0
		this.asking_bid = 0
		this.bid_increment = 0
		this.bid_increment_multiplier = 1
		this.posting = null
		this.posting_id = null
		this.customer_id = null
		this.winning_customer_id = null
		this.bidder_number_id = null
		this.bidder_number = null
		this.location = null
		this.bidded = false
		this.bid_histories = []
		this.notify = null
		this.hold_bid = null
		this.auto_bid_acceptance = 0


		this.initializeWebsocket()

		document.addEventListener("unload", this.closeConnection)

		// document.addEventListener('visibilitychange', () => {

		// 	if(document.visibilityState === 'hidden')
		//    		this.closeConnection()

		// 	if(document.visibilityState === 'visible')
		// 		this.initializeWebsocket()

		// })
	}

	initializeWebsocket() {

		if(!this.channel) return console.error('No bid channel found.')

		try {
			this.ws = ws.subscribe(`live-selling:event:${this.channel}`)
		} catch (e) {
			this.ws = ws.getSubscription(`live-selling:event:${this.channel}`)
		}

		if(this.ws){
			this.intializeEvents()
		}
	}

	intializeEvents() {
		this.ws.emit('getActiveProduct')

        this.ws.on('updateActiveProduct', (posting) => {
			this.posting = posting
			this.posting_id = posting.posting_id
		})

        this.ws.on('updateQuantity', (product) => {
            app.$emit('updateQuantity', product)
		})

        this.ws.on('receiveParticipant', (participant) => {
            app.$emit('receiveParticipant', participant)
		})

		this.ws.on('errorMessage',(message)=>{
			app.$modal.error({
				message,
				title: 'Ooops',
				timer: 1700
			})
		})

		ws.on('open', () => {
			this.initializeWebsocket()
		})

		ws.on('close', () => {
			this.bidded = false
		})
	}
	setActiveProduct(posting_id) {
        console.log(posting_id)
		this.ws.emit('sendActiveProduct', {
			posting_id
		})
	}

	setPostingId(posting) {
		this.posting_id = posting
	}

	publishMessage(message) {
		this.ws.emit('broadcastMessage', {
			message
		})
	}

	refreshListing() {
		this.ws.emit('refreshListing')
	}


	moneyFormat(amount) {
		return app.$options.filters.moneyFormat(amount)
	}

	closeConnection() {
		if(this.ws._state == 'open')
			this.ws.close()
	}


}

export default LiveSelling
