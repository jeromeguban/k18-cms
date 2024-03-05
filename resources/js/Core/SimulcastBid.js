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

class SimulcastBid {
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
			this.ws = ws.subscribe(`bid:simulcast-auction:${this.channel}`)
		} catch (e) {
			this.ws = ws.getSubscription(`bid:simulcast-auction:${this.channel}`)
		}

		if(this.ws){
			this.intializeEvents()
		}
	}

	intializeEvents() {
		this.ws.emit('getActiveLot')

		this.ws.on('updateActiveLot', (posting) => {
			this.posting = posting
			this.posting_id = posting.posting_id
		})

		this.ws.on('retrieveBidHistory', (bid_histories) => {
			this.bid_histories = bid_histories.filter((bid_history) => {
				return typeof bid_history.deleted_at === 'undefined'
			})
		})

		this.ws.on('updateBidHistory', (bid_history) => {
			if(this.bid_histories.indexOf(bid_history) === -1)
				this.bid_histories.push(bid_history)
		})

		this.ws.on('updateCurrentBidAmount', (bid_amount) => {
			this.current_bid = bid_amount && typeof bid_amount == 'string' ? parseFloat(bid_amount) : (bid_amount || 0.00)
		})

		this.ws.on('updateCurrentBidIncrement', (bid_increment) => {
			this.bid_increment = bid_increment
		})
		this.ws.on('updateCurrentBidIncrementMultiplier', (increment_multiplier) => {
			this.bid_increment_multiplier = increment_multiplier
		})

		this.ws.on('updateHoldBid', (hold_bid) => {
			this.hold_bid = hold_bid === '' ? 1 : parseInt(hold_bid)
		})

		this.ws.on('updateAutoBidAcceptance', (auto_bid_acceptance) => {
			this.auto_bid_acceptance = auto_bid_acceptance === '' ? 0 : parseInt(auto_bid_acceptance)
		})

		this.ws.on('updateCurrentWinner', (winner) => {
			this.customer_id = winner.customer_id && typeof winner.customer_id == 'string' ? parseInt(winner.customer_id)
				: winner.customer_id
			this.bidder_number_id = winner.bidder_number_id && typeof winner.bidder_number_id == 'string' ? parseInt(winner.bidder_number_id)
				: winner.bidder_number_id
			this.bidder_number = winner.bidder_number
			this.location = winner.location
			this.notify = winner.notify
		})

		this.ws.on('updateAskingBid', (asking_bid) => {
			this.asking_bid = asking_bid && typeof asking_bid == 'string' ? parseFloat(asking_bid) : (asking_bid || 0.00)
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
	setActiveLot(posting_id) {
		this.ws.emit('sendActiveLot', {
			posting_id
		})
	}

	setPostingId(posting) {
		this.posting_id = posting
	}

	setBid(manual_bid = null) {
		let asking_bid = manual_bid || this.asking_bid
		this.ws.emit('sendFloorBid',{
			bid_amount: asking_bid,
			posting_id: this.posting_id,
			bidder_number_id: 0,
			customer_id: 0
		})
	}

	setPostingStatus(posting) {
		this.ws.emit('sendPostingStatus', posting)
	}

	setHoldBid() {
		this.hold_bid = this.hold_bid ? 0 : 1
		this.ws.emit('sendHoldBid',{
			posting_id: this.posting_id,
			hold_bid: this.hold_bid,
		})
	}

	setBidAcceptance() {
		this.auto_bid_acceptance = this.auto_bid_acceptance ? 0 : 1
		this.hold_bid = this.auto_bid_acceptance ? 0 : 1
		this.ws.emit('sendAutoBidAcceptance',{
			posting_id: this.posting_id,
			auto_bid_acceptance: this.auto_bid_acceptance,
			hold_bid: this.hold_bid,
		})
	}

	setAskingBid(data) {
		this.ws.emit('sendAskingBid', {
			asking_bid: data.asking_bid,
			bid_increment: data.bid_increment,
			bid_increment_multiplier: data.bid_increment_multiplier,
			posting_id: this.posting_id,
		})
	}

	removeBid(bid_history) {
		this.ws.emit('removeBid', {
			posting_id: bid_history.posting_id,
			customer_id: bid_history.customer_id,
			bid_amount: bid_history.bid_amount,
		})
	}

	publishMessage(message) {
		this.ws.emit('broadcastMessage', {
			message
		})
	}

	getCurrentBidAndWinner() {
		this.ws.emit('getCurrentBidAndWinner', {
			posting_id: this.posting_id
		})
	}

	getCurrentBidHistories() {
		this.ws.emit('getCurrentBidHistories', {
			posting_id: this.posting_id
		})
	}

	getOnHoldAndAutoBidAcceptance() {
		this.ws.emit('getOnHoldAndAutoBidAcceptance', {
			posting_id: this.posting_id
		})
	}

	moneyFormat(amount) {
		return app.$options.filters.moneyFormat(amount)
	}

	closeConnection() {
		if(this.ws._state == 'open')
			this.ws.close()
	}


}

export default SimulcastBid
