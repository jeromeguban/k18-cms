<template>
    <fullscreen v-model="fullscreen">
        <div class="relative">
            <vue-content-loading :width="100" :height="65" class="mt-4" v-if="!products.length && !currentProduct">
                <rect width="100" height="100" />
            </vue-content-loading>
            <div class="flex flex-col absolute inset-0 justify-center items-center z-50">
                <div class="overflow-hidden mx-auto">
                    <img alt="No Result" class="rounded-md w-36 h-36"
                        src="/images/no-result.png">
                </div>
                <div class="mt-2">
                    <div class="font-medium" v-if="!products.length && !currentProduct && validateProductCount">Loading ...</div>
                    <div class="font-medium" v-if="!products.length && !currentProduct && !validateProductCount">No Products to Show.</div>
                </div>
            </div>
        </div>
        <body class="fullscreen-wrapper mt-4" style="font-family: Oswald, sans-serif" v-if="!isProductsLoading && products && current_product">
            <div class="h-screen bg-white relative">
                <div class="flex flex-col h-full leading-normal relative">
                    <div class="flex flex-row justify-between px-4">
                        <img class="w-48 mt-2" src="https://hmr.ph/images/hmr-ph-logo.png" alt="">
                        <button type="button" class="mb-6" @click="toggle" v-hotkey="['alt+m','ctrl+m','alt+shift+m']">
                            <img v-if="!fullscreen" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E" alt="" style="height: 24px; width: 24px;">
                            <img v-if="fullscreen" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M4%204H0v2h6V0H4v4zm10%200V0h-2v6h6V4h-4zm-2%2014h2v-4h4v-2h-6v6zM0%2014h4v4h2v-6H0v2z%22/%3E%3C/svg%3E" alt="" style="height: 24px; width: 24px;">
                        </button>
                    </div>

                    <div class="flex h-full relative">
                        <div class="w-1/2 h-full pt-2">
                            <div class="w-full h-full relative">
                                <div class="bg-light-blue z-50 h-full absolute w-3/5 top-0 left-0"
                                    style="border-top-right-radius: 50%; z-index: 50">
                                </div>

                                <div class="relative mx-10" style="z-index:99" >
                                    <div class="flex items-center justify-between pt-10 mt-10">
                                        <span class="inline-block bg-white px-6 rounded-lg shadow-sm text-blue"
                                            style="font-size: 2.8vw">Now Selling
                                        </span>

                                        <div class="flex gap-1">
                                            <div class="text-blue" style="font-size: 2.5vw; ">Price:</div>
                                            <div class="block font-medium text-blue relative" style="font-size: 2.5vw">
                                                {{ current_product && parseFloat(current_product.suggested_retail_price) < parseFloat(current_product.unit_price) ? current_product.suggested_retail_price : current_product.unit_price | moneyFormat}}

                                                <div class=" absolute -top-12 right-0" v-if="current_product && parseFloat(current_product.suggested_retail_price) < parseFloat(current_product.unit_price)">
                                                    <div :style="'font-size: 2vw'" class="relative text-theme-6 font-semibold"
                                                        v-if="current_product && parseFloat(current_product.suggested_retail_price) < parseFloat(current_product.unit_price)">
                                                        {{ current_product.unit_price | moneyFormat }}
                                                        <span class="absolute top-8 left-0 right-0 h-0.5 bg-theme-6 bottom-0"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <img
                                        :src="current_product.banner"
                                        class="rounded-lg block w-auto shadow-lg mt-8 "
                                        style="max-height:63vh; width: 100%; border: 8px solid white" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="pb-64 flex flex-col w-1/2 justify-center items-center">
                            <span class="text-blue" style="font-size: 2.5vw"><i class="fa-solid fa-users"></i> Total Participants: {{ participants.length }}</span>
                            <div class="flex flex-col border border-gray-200 bg-white p-4 mt-4 rounded shadow-md" style="width: 90%;">
                                <span class="mb-2 text-blue" style="font-size: 2vw">Messages</span>
                                <div class="flex flex-col gap-y-2 overflow-y-scroll pr-5 comment-container h-full"
                                    style="max-height: 460px;"
                                    ref="commentContainer"
                                    @scroll="handleScroll"
                                >
                                    <div :class="{ 'bg-theme-34': comment.user_id == user }" class="flex justify-between mb-1 border rounded-lg p-2" v-for="(comment, index) in message.messages" :key="index">
                                        <div  class="flex justify-between gap-1  text-gray-600 w-full">
                                            <div class="flex gap-2">
                                                <div>
                                                  <div v-if="!comment.avatar" class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"
                                                  :class="{ 'bg-theme-6': comment.user_id == user,  'bg-theme-9': comment.user_id != user }"
                                                  >
                                                  {{ getUserMessageInitials(comment.fullname, comment.customer_id ? 'Customer' : 'User') }}
                                                  </div>
                                                  <img v-if="comment.avatar" :src="comment.avatar" alt="avatar"
                                                  class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"/>
                                                </div>

                                                <div>
                                                    <h6
                                                        class="text-primary-2 font-semibold lg:text-xs xl:text-medium"
                                                        v-if="comment.user_id && comment.user_id == user">YOU</h6>
                                                    <h5
                                                        class="text-primary-2 font-semibold lg:text-xs xl:text-sm 2xl:text-medium"
                                                        v-else>{{ comment.fullname }}</h5>
                                                    <!-- <p :style="'font-size: 11px'" class="text-gray-600 message">{{ comment.message }}</p> -->
                                                    <see-more :comment="comment.message" :selfChat="check_if_self_chat(comment)"/>

                                                </div>
                                            </div>

                                            <div class="flex flex-col justify-between">
                                                <div :style="'font-size: 10px'" class="text-xs italic text-gray-500 text-right message-time">{{ comment.created_at | LT }}</div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                                <span
                                    class="justify-center items-center text-center bg-theme-9 text-white text-xs shadow-md rounded-full px-4 py-1 cursor-pointer"
                                    @click="focusOnLastItem"
                                    v-if="total_unread_comments">
                                        {{ total_unread_comments }} New Comments
                                </span>
                            </div>
                            <!-- <div class="flex flex-col border mt-4 border-gray-200 bg-white p-4 rounded shadow-md" style="width: 90%;">
                                <span class="mb-2 text-blue" style="font-size: 2vw">Notifications</span>
                                <span class="mb-2" style="font-size: 1.4vw">{{ limitProductDescription(current_product.description, 360) }}</span>
                                <div class="lg:overflow-y-auto notifications-container">
                                    <div class="flex flex-col mb-2 rounded-lg gap-2 lg:overflow-y-auto">
                                        <div class="flex gap-2 text-gray-600" v-for="(notification, index) in notification.notifications" :key="index">
                                            <div class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 bg-theme-9 text-theme-2 flex items-center justify-center self-center">
                                            {{ getUserNotificationsInitials(notification.fullname) }}
                                            </div>
                                            <div class="w-3/4 ml-2">
                                                <p class="text-gray-600 lg:text-xs xl:text-sm">
                                                    <span class="italic text-primary-2 font-medium">{{ notification.fullname }}</span> {{ notification.notification }}.
                                                </p>

                                                <p class="italic text-gray-500">{{ notification.created_at | LT }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 text-white rounded-tr-xl text-center py-2 w-1/2"
                        style="font-size: 3.0vw; z-index:99; background-color: rgba(0,0,0,0.5);">
                        {{ current_product.name }}
                    </div>
                    <AlertNotification
                        :show="show_alert"/>
                </div>
            </div>
        </body>
    </fullscreen>
</template>
<style scoped>
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
  background: #ffffff;
  padding: 0px;
}
.comment-container::-webkit-scrollbar {
    width: 0.5em;
}

/* For Firefox and other browsers */
.comment-container {
    scrollbar-width: none; /* Hide the scrollbar in Firefox */
}

@media (min-width: 1024px) {
    .notifications-container {
        max-height: 200px;
    }
}

</style>
<script>
import VueContentLoading from 'vue-content-loading';
import LiveSelling from '../../Core/LiveSelling'
import Message from '../../Core/Message'
import SeeMore from './see-more-text'
import AlertNotification from './alert-notification'
import Notification from '../../Core/Notification'
export default {
    name:'display-page',
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
        AlertNotification,
        VueContentLoading,
    },
    data() {
        return {
            show_alert: false,
            event: null,
            live_selling: new LiveSelling(this.$route.params.id),
            message: new Message(this.$route.params.id, {
                user_id: this.user,
                fullname: this.username
            }),
            comment: null,
            isAtBottom: true,
            commentCount: 0,
            participants: [],
            notification: new Notification(this.$route.params.id),
            fullscreen: false,
            tabPanelName: "onQueueProducts",
            products: [],
            on_queue_products: null,
            previous_products: null,
            all_products: null,
            current_product : null,
            isProductsLoading: false,
            isCurrentProductLoading: false,
            isEventLoading: false,
            isViewFullDetails: false,
            isOpenProducts: false,
            isOpenParticipants: false,
            isOpenMessages: false,
            isOpenNotifications: false,
            validateProductCount: false,
            totalParticipants: null
        }
    },

    mounted() {
        this.$nextTick(() => {
            if(this.isAtBottom)
                setTimeout(() => {
                    this.focusOnLastItem();
                }, 6000);
        });
    },

    created() {
        this.getProducts();
        this.getParticipants()
        //Use this approach if data has duplicated values
        const receiveParticipantHandler = (data) => {
            this.participants.push(data);
            // Remove the event listener after it's triggered
            // app.$off('receiveParticipant', receiveParticipantHandler);
        };

        app.$on('receiveParticipant', receiveParticipantHandler);

        app.$on('close-alert-notification', ()=>{
            this.show_alert = false
        })
    },


    computed: {
        'sorted_notifications'() {
            return this.notification.notifications.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },
        'sorted_comments'() {
            return this.message.messages.sort((a, b) => {
                return new Date(a.created_at) - new Date(b.created_at);
            });
        },
        'total_unread_comments'() {
            if(this.commentCount)
                return this.message.messages.length - this.commentCount
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

    watch: {
        'notification.notifications': {
            handler() {
                this.show_alert = true;
                setTimeout(() => {
                    this.show_alert = false;
                }, 6000);
            }
        },
        'message.messages': {
            handler(newValue, oldValue) {
                this.$nextTick(() => {
                    if(this.isAtBottom)
                        this.focusOnLastItem();
                });
            }
        },
        'isAtBottom'() {
            if(!this.isAtBottom) this.commentCount = this.message.messages.length
            else this.commentCount = 0
        },
        'live_selling.posting_id': {
            handler() {
                if(this.live_selling.posting_id) {
                    if(!this.current_product || (this.current_product && (this.current_product.posting_id != this.live_selling.posting_id)))
					    this.getCurrentProduct()
                }
            },
            immediate: true
        },
        'current_product' : {
            handler() {
                if(this.current_product.posting_id != this.live_selling.posting_id){
                    this.live_selling.setActiveProduct(this.current_product.posting_id)
                    this.live_selling.setPostingId(this.current_product.posting_id)
                }
            }
        },
    },

    methods: {
        limitProductDescription(text, limit) {
            const limitedText = text.slice(0, limit).trim();

            return limitedText + "...";
        },

        getProducts() {
            this.isProductsLoading = true;
            this.validateProductCount = true;
		    	axios.get(`/events/${this.$route.params.id}/postings`)
			.then(({data})=>{
                this.isProductsLoading = false;
                this.validateProductCount = false;
				this.products = data;

                this.on_queue_products = this.products.filter((product) => {
                    return product.category == "Retail" &&  product.finalized_date == null;
                });

                this.previous_products =  this.products.filter((product) => {
                    return product.category == "Retail" &&  product.finalized_date != null;
                });

                this.all_products = this.products.filter((product) => {
                    return product.category == "Retail";
                });

                this.getCurrentProduct()

            }) .catch((error) => {
                this.isProductsLoading = false;
                this.validateProductCount = false;
                console.log(error);
            });

            setTimeout(() => {
                this.isProductsLoading = false;
                this.validateProductCount = false;
            }, 6000);
           
		},

        getCurrentProduct() {
            if(this.live_selling.posting_id){
				if(!this.live_selling.posting) {
					this.current_product = this.on_queue_products.find((product)=>{
                        return product.posting_id == this.live_selling.posting_id
                    })
				} else {
					this.current_product = this.live_selling.posting
				}

                if(this.current_product)
				    return
			}

			this.current_product = this.on_queue_products.length ? this.on_queue_products[0] : null
		},


        toggle () {
            this.fullscreen = !this.fullscreen
        },

        getUserMessageInitials(fullName, type) {
            const namesArray = type == 'Customer' ? fullName.split(" ") : fullName.split(" - ");
            const firstName = namesArray[0].charAt(0);
            const lastName = namesArray[1].charAt(0);
            return `${firstName}${lastName}`
        },
        getUserNotificationsInitials(fullName) {
            const namesArray = fullName.split(" ");
            const firstName = namesArray[0].charAt(0);
            const lastName = namesArray[1].charAt(0);
            return `${firstName}${lastName}`
        },
        focusOnLastItem() {
            const container = this.$refs.commentContainer;
            container.scrollTop = container.scrollHeight
        },
        handleScroll() {
            const container = this.$refs.commentContainer
            this.isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 1;
        },
        getParticipants() {
            axios.get(`events/${this.$route.params.id}/participants`)
                .then(({data}) => {
                    this.participants = data;
                });
        },
    },
}
</script>
