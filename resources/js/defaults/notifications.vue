<template>
	<div class="intro-x dropdown mr-auto sm:mr-6">
		<div class="dropdown-toggle notification cursor-pointer" role="button" @click.prevent="toggle()" :class="{
		    'notification--bullet': total_unviewed_notifications
		}" aria-expanded="false" data-tw-toggle="dropdown">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="bell"
				data-lucide="bell" class="lucide lucide-bell notification__icon dark:text-slate-500">
				<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
				<path d="M13.73 21a2 2 0 01-3.46 0"></path>
			</svg>
		</div>
		<div class="notification-content bg-white rounded-md pt-2 dropdown-menu border-gray-300 border-solid shadow-md "
			data-popper-placement="bottom-end"
			style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 20px, 0px);">
			<div class="notification-content__box dropdown-content overflow-x-auto h-80">
				<div class="notification-content__title">Notifications</div>
				<div v-if="!isLoaded">
					<loader object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793" opacity="5" name="dots">
					</loader>
				</div>
				<div v-if="isLoaded && notifications.length <= 0" class="h-full flex items-center">
					<div class="mx-auto text-center">
						<div class="overflow-hidden mx-auto">
							<img alt="No-Result" class="rounded-md w-36 h-36" src="images/no-result.png">
						</div>
						<div class="mt-2">
							<div class="font-medium">No Notifications to Show.</div>
						</div>
					</div>
				</div>
				<div class="cursor-pointer relative flex w-full items-center"
					v-for="(notification, index) in notifications" :key="index">
					<div class="mt-4 w-full hover:text-theme-10">
						<div class="flex items-center w-full">
							<a :href="notification.redirect_link" class="font-medium truncate mr-5">{{
								notification.title
							}}</a>
							<div class="mt-2 text-xs text-slate-400 ml-auto whitespace-nowrap">{{
								notification.created_at | elapsedTime
							}}</div>
						</div>
						<div class="w-full truncate text-slate-500 mt-0.5 leading-loose border-b border-gray-300"
							v-html="notification.message" />

					</div>
				</div>
			</div>
			<div v-if="notifications.length > 0" class="justify-center mb-2 px-2">
				<router-link :to="'/store-inquiries'"
					class="intro-y mt-4 w-full block text-center rounded-md py-4 border border-dotted border-slate-500 border-gray-500 dark:border-darkmode-300 text-slate-500 hover:bg-theme-22">View
					More
				</router-link>
			</div>
		</div>
	</div>
</template>
<script>
import { io } from "socket.io-client"
const socket = io(window.notification_server_url)
export default {
	name: 'notifications',
	data() {
		return {
			show: false,
			notifications: [],
			total_unviewed_notifications: null,
			notification_channels: window.Laravel.notifications,
			store: window.Laravel.store,
			isLoaded: false
		}
	},

	computed: {
		'unviewed_notification_ids'() {
			return this.notifications.filter((notification) => {
				return !notification.viewed
			}).map(({ id }) => {
				return id
			})
		}
	},

	created() {
		this.getUnviewedNotificationsCount()
	},

	mounted() {
		this.setNotificationChannels()

		app.$on('notification:viewed', () => {
			this.total_unviewed_notifications = 0
		})
	},
	methods: {
		toggle() {
			this.show = !this.show

			if (this.show) {
				this.getNotifications()
				this.setAllNotificationsAsViewed()
			}
		},
		hide() {
			this.show = false
		},
		getNotifications() {
			axios.get('/system-notifications', {
				params: {
					limit: 10
				}
			})
				.then(({ data }) => {
					this.notifications = data
					this.isLoaded = true
				})
		},

		getUnviewedNotificationsCount() {
			axios.get('/system-notifications/unviewed/count')
				.then(({ data }) => {
					this.total_unviewed_notifications = data
				})
		},

		setAllNotificationsAsViewed() {
			axios.post('/system-notifications/viewed')
				.then(({ data }) => {
					this.total_unviewed_notifications = 0
				})
		},

		setNotificationChannels() {

			if (this.notification_channels.length) {
				for (let channel of this.notification_channels) {
					socket.on(`system:${channel}:${this.store}`, (data) => {
						this.total_unviewed_notifications++
						this.playNotificationBell()
					})
				}
			}

		},

		playNotificationBell() {
			new Audio("/sound-effects/notification-bell.ogg").play()
		}
	}
}
</script>