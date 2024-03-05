
class Message {
	constructor(channel, user = null) {
		this.channel = channel
		this.ws = null
		this.user = user
        this.messages = []
		this.initializeWebsocket()

		// document.addEventListener("unload", this.closeConnection)

		document.addEventListener('visibilitychange', () => {
			// if(document.visibilityState === 'hidden')
		   	// 	this.closeConnection()

			// if(document.visibilityState === 'visible')
			// 	this.initializeWebsocket()

		})
	}

	initializeWebsocket() {

		if(!this.channel) return console.error('No event channel found.')

		try {
			this.ws = ws.subscribe(`message:event:${this.channel}`)
		} catch (e) {
			this.ws = ws.getSubscription(`message:event:${this.channel}`)
		}

		if(this.ws){
			this.intializeEvents()
		}
	}

	intializeEvents() {
        this.ws.emit('getMessages')

        this.ws.on('receiveMessage',(message)=>{
            if(this.messages.indexOf(message) === -1)
                this.messages.push(message)
        })

        this.ws.on('receiveMessages',(messages)=>{
            this.messages = messages
        })

        this.ws.on('refreshMessages', ()=>{
            this.messages = []
            this.ws.emit('getMessages')
        })

        this.ws.on('successMessage',(message)=>{
			if(window.innerWidth <= 768){
				app.$toaster.show({
					message,
					type: 'success',
					position: 'top-right',
					duration: 3000,
				});
			}
			if(window.innerWidth > 768){
				app.$modal.success({
					message,
					title: 'Success',
					timer: 1700
				});
			}

		});

        this.ws.on('errorMessage',(message)=>{

			if(window.innerWidth <= 768){
				app.$toaster.show({
					message,
					type: 'danger',
					position: 'top-right',
					duration: 3000,
				});
			}
			if(window.innerWidth > 768){
				app.$modal.error({
					message,
					title: 'Ooops',
					timer: 1700
				});
			}
		});

        ws.on('open', () => {
			this.initializeWebsocket()
		})

		ws.on('close', () => {
			this.bidded = false
		})



	}

	sendMessage(message) {
		if(this.user){

            // app.$modal.dialog({
			// 	message: 'Are you sure you want to add this product to your cart?',
			// 	confirmButton: 'Okay',
			// 	cancelButton: 'Cancel',
			// 	title: 'Add To Cart'
			// }).then( () => {
                this.ws.emit('sendMessage',{
                    message,
                    fullname: this.user.fullname,
                    user_id: this.user.user_id
                })
            // }).catch()
		}
	}

    deleteUserMessages(data) {
        this.ws.emit('deleteUserMessages', {
            customer_id: data.customer_id
        })
    }

    deleteMessage(id) {
        this.ws.emit('deleteMessage', {
            id
        })
    }


	closeConnection() {
		if(this.ws._state == 'open')
			this.ws.close()
	}


}

export default Message
