<template>
<div>
    <div class="mb-4 border  rounded-lg p-3 box-shadow pb-16 lg:pb-3 " >
        <div class="flex items-center mb-2 gap-2">
            <h2 class="text-base lg:text-lg xl:text-xl font-semibold text-black">Messages</h2>
            <span class="px-3 py-1 bg-theme-32 rounded-md text-white font-bold">
            {{ message.messages.length}}
            </span>
        </div>

        <div class="lg:overflow-y-auto messages-container">
        <div :class="{ 'bg-theme-34': comment.user_id == user }" class="flex justify-between mb-1 border rounded-lg p-2" v-for="(comment, index) in message.messages" :key="index">

            <div  class="flex justify-between gap-1  text-gray-600 w-full">
                <div class="flex gap-2">
                    <div>
                      <div v-if="!comment.avatar" class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"
                        :class="{ 'bg-theme-6': comment.user_id == user,  'bg-theme-9': comment.user_id != user }"
                        >
                        {{ getInitials(comment.fullname, comment.customer_id ? 'Customer' : 'User') }}
                      </div>
                      <img v-if="comment.avatar" :src="comment.avatar" alt="avatar" 
                      class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"/>
                    </div>

                    <div>
                        <h6
                            class="text-primary-2 font-semibold lg:text-xs xl:text-medium"
                            v-if="comment.user_id && comment.user_id == user">YOU</h6>
                        <h6
                            class="text-primary-2 font-semibold lg:text-xs xl:text-sm 2xl:text-medium"
                            v-else>{{ comment.fullname }}</h6>
                        <!-- <p :style="'font-size: 11px'" class="text-gray-600 message">{{ comment.message }}</p> -->
                        <see-more :style="'font-size: 11px'" class="text-gray-600 message" :comment="comment.message" :selfChat="check_if_self_chat(comment)"/>

                        <!-- <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 384 512" class="stroke-1.5  text-primary-2 svg-pin"><path d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z"/></svg>
                            <a href="" :style="'font-size: 10px'" class="font-normal text-primary-2 underline pinned-message">Pinned Message</a>
                        </div> -->
                    </div>
                </div>

                <div class="flex flex-col justify-between">
                    <div :style="'font-size: 10px'" class="text-xs italic text-gray-500 text-right message-time">{{ comment.created_at | LT }}</div>
                    <button
                        v-tooltip="'Delete'"
                        @click.prevent="deleteMessage(comment.id)"
                        class="text-theme-6 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </div>


            </div>
        </div>
        </div>

        <div class="flex gap-2 mt-2">
            <div class="w-full">
                <input
                    v-model="comment"
                    type="text"
                    @keyup.enter.prevent="sendMessage()"
                    class="px-2 py-1 border rounded-lg w-full outline-none italic text-base" placeholder="Type your message..">
            </div>


            <div>
                <button
                    @click.prevent="sendMessage()"
                    class="bg-primary-5 text-white h-full flex gap-2 justify-center items-center rounded-full px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send stroke-1.5 mx-auto block"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import Message from '../../Core/Message';
import SeeMore from './see-more-text';
export default {
    name: 'messages',
    props: {
        username: {
            type: String,
            default: null
        },
        user: {
            type: String,
            default: null
        }
    },

    components: {
        SeeMore,
    },
    data() {
		return {
            message: new Message(this.$route.params.id, {
                user_id: this.user,
                fullname: this.username
            }),
            comment: null
		}
	},
    computed: {
        'sorted_comments'() {
            return this.message.messages.sort((a, b) => {
                return new Date(a.created_at) - new Date(b.created_at);
            });
        },

        'check_if_self_chat'() {
            return (comment) => {
                if(this.user && this.user.customer_id == comment.customer_id) {
                    return true
                }

                return false
            }
        }
    },

    mounted() {
        app.$on('deleteUserMessages',(customer) => {
            this.message.deleteUserMessages(customer)
        })
    },

    methods: {
        sendMessage() {
            if(!this.comment) return
            this.message.sendMessage(this.comment)
            this.comment = null
        },
        getInitials(fullName, type) {
            const namesArray = type == 'Customer' ? fullName.split(" ") : fullName.split(" - ");
            const firstName = namesArray[0].charAt(0);
            const lastName = namesArray[1].charAt(0);
            return `${firstName}${lastName}`
        },
        deleteMessage(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to delete this message?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                this.message.deleteMessage(id)
            }).catch();

        }
    }
}
</script>

<style scoped>
.box-shadow {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
}

@media (min-width: 1024px) {
    .messages-container {
        max-height: 363px;
    }
}

@media (min-width: 1280px) {
    .message {
        font-size: 13px !important;
    }

    .message-time {
        font-size: 11px !important;
    }
    .pinned-message {
        font-size: 12px !important;
    }

    .svg-pin {
        width: 16 !important;
        height: 16 !important;
    }
}
</style>
