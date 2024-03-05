<template>

    <div class="mb-4 border rounded-lg p-3 box-shadow">
        <div class="flex items-center mb-4 gap-2">
            <h2 class="lg:text-lg xl:text-xl font-semibold text-black">Notifications</h2>
            <span class="lg:px-3 lg:py-1 bg-theme-32 rounded-md text-white font-bold">
                {{ notification.notifications.length }}
            </span>
        </div>
        <div class="lg:overflow-y-auto notifications-container">
            <div class="flex flex-col mb-2 rounded-lg gap-2 lg:overflow-y-auto">
                <div class="flex gap-2 text-gray-600" v-for="(notification, index) in notification.notifications" :key="index">
                    <div v-if="!notification.avatar" class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 bg-theme-9 text-theme-2 flex items-center justify-center self-center">
                    {{ getInitials(notification.fullname) }}
                    </div>
                    <img v-if="notification.avatar" :src="notification.avatar" alt="avatar" 
                      class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"/>
                    <div class="w-3/4 ml-2">
                        <p class="text-gray-600 lg:text-xs xl:text-sm">
                            <span class="italic text-primary-2 font-medium">{{ notification.fullname }}</span> {{ notification.notification }}.
                        </p>

                        <p class="italic text-gray-500">{{ notification.created_at | LT }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import Notification from '../../Core/Notification'
export default {
    name: 'notifications',
    username: {
        type: String,
        default: null
    },
    user: {
        type: String,
        default: null
    },
    data() {
		return {
            notification: new Notification(this.$route.params.id),
		}
	},
    computed: {
        'sorted_notifications'() {
            return this.notification.notifications.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        }
    },

    methods: {

        getInitials(fullName) {
            const namesArray = fullName.split(" ");
            const firstName = namesArray[0].charAt(0);
            const lastName = namesArray[1].charAt(0);
            return `${firstName}${lastName}`
        },

    }
}
</script>
<style scoped>
.box-shadow {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
}

@media (min-width: 1024px) {
    .notifications-container {
        max-height: 300px;
    }
}
</style>
