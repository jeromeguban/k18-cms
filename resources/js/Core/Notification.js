class Notification {
	constructor(channel) {
		this.channel = channel
		this.ws = null
        this.notifications = []
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
			this.ws = ws.subscribe(`notification:event:${this.channel}`)
		} catch (e) {
			this.ws = ws.getSubscription(`notification:event:${this.channel}`)
		}

		if(this.ws){
			this.intializeEvents()
		}
	}

	intializeEvents() {
        this.ws.emit('getNotifications')

        this.ws.on('receiveNotification',(notification)=>{
            if(this.notifications.indexOf(notification) === -1){
                this.notifications.unshift(notification)
            }

        })

        this.ws.on('receiveNotifications',(notifications)=>{
            this.notifications = notifications.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            })
        })

        this.ws.on('successNotification',(notification)=>{
			if(window.innerWidth <= 768){
				app.$toaster.show({
					notification,
					type: 'success',
					position: 'top-right',
					duration: 3000,
				});
			}
			if(window.innerWidth > 768){
				app.$modal.success({
					notification,
					title: 'Success',
					timer: 1700
				});
			}

		});

        this.ws.on('errorNotification',(notification)=>{

			if(window.innerWidth <= 768){
				app.$toaster.show({
					notification,
					type: 'danger',
					position: 'top-right',
					duration: 3000,
				});
			}
			if(window.innerWidth > 768){
				app.$modal.error({
					notification,
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

	closeConnection() {
		if(this.ws._state == 'open')
			this.ws.close()
	}


}

export default Notification
