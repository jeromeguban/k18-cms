<template>
 <div class="bg-white rounded-lg p-3 participants-box-shadow">
    <div class="flex items-center gap-2 mb-4">
        <h2 class="text-base lg:text-lg xl:text-xl font-semibold text-black">Participants</h2>
        <span class="px-3 py-1 bg-theme-32 rounded-md text-white font-bold" v-if="participants.length">
        {{ participants.length }}
        </span>

    </div>
    <div class="lg:overflow-y-auto participants-container">
        <div class="flex gap-2 justify-between mb-3" v-for="participant in participants" :key="participant.participant_id">
            <div class="flex items-center gap-2">
                <div v-if="!participant.avatar" class="block w-8 h-8 rounded-full bg-theme-9 border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center">
                {{ getInitials(participant.customer_firstname, participant.customer_lastname) }}
                </div>
                <img v-if="participant.avatar" :src="participant.avatar" alt="avatar" 
                      class="block w-8 h-8 rounded-full border-4 mr-1 hover:border-slate-200 border-slate-300 dark:border-darkmode-800/80 text-theme-2 flex items-center justify-center self-center"/>
                <div>
                    <div class="lg:text-xs xl:text-sm text-gray-600">{{participant.email}}</div>
                    <h6 class="text-primary-2 font-semibold lg:text-md">{{ participant.customer_firstname }} {{ participant.customer_lastname }}</h6>
                </div>
            </div>


            <div class="self-center">
                <div class="flex flex-row">

                <button
                    v-tooltip="'Restrict Message'"
                    @click.prevent="blockParticipantMessages(participant.participant_id)"
                    v-if="!participant.message_restricted_at"
                    class="text-white bg-gray-600 rounded btn border border-gray-300 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </button>

                <button
                    v-tooltip="'Unrestrict Message'"
                    @click.prevent="unblockParticipantMessages(participant.participant_id)"
                    v-if="participant.message_restricted_at"
                    class="text-white bg-theme-6 rounded btn border border-gray-300 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </button>

                <button
                    v-tooltip="'Restrict Cart'"
                    @click.prevent="blockParticipantCart(participant.participant_id)"
                    v-if="!participant.cart_restricted_at"
                    class="text-white bg-gray-600 rounded btn border border-gray-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                </button>

                <button
                    v-tooltip="'Unrestrict Cart'"
                    @click.prevent="unblockParticipantCart(participant.participant_id)"
                    v-if="participant.cart_restricted_at"
                    class="text-white bg-theme-6 rounded btn border border-gray-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                </button>

                <button
                    v-tooltip="'Delete All Message'"
                    @click.prevent="deleteAllParticipantMessages(participant.customer_id)"
                    class="text-white bg-theme-6 rounded btn border border-gray-300 flex items-center justify-center ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-1.5 block mx-auto"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>


            </div>
                <!-- <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="#afaeae"
                    stroke="#afaeae"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    icon-name="more-vertical"
                    data-lucide="more-vertical"
                    class="text-gray-500 lucide lucide-more-vertical stroke-1.5 mx-auto block"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> -->
            </div>

        </div>
    </div>
</div>
</template>
<script>
import Vue from 'vue'
import VTooltip from 'v-tooltip'

Vue.use(VTooltip)

export default {
    name: 'participants',
    props: {
	},

    data() {
		return {
            participants: [],
		}
	},

    created() {
        this.getParticipants()
        //Use this approach if data has duplicated values
        const receiveParticipantHandler = (data) => {
            this.participants.push(data);
            // Remove the event listener after it's triggered
            // app.$off('receiveParticipant', receiveParticipantHandler);
        };

        app.$on('receiveParticipant', receiveParticipantHandler);
    },

    computed: {

    },

    methods: {
        getParticipants() {
            axios.get(`events/${this.$route.params.id}/participants`)
                .then(({data}) => {
                    this.participants = data;
                });
        },

        getInitials(customerFirstName, customerLastName) {
            const firstName = customerFirstName.charAt(0);
            const lastName = customerLastName.charAt(0);
            return `${firstName}${lastName}`
        },

        blockParticipantMessages(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to block this customer from messaging?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                axios.patch(`/participants-message/${id}/block`)
                    .then(() => {
                        this.$modal.success({
                            message: 'Customer successfully blocked from messaging',
                        });
                        this.getParticipants()
                    })
                    .catch();
            }).catch();
        },

        unblockParticipantMessages(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to unblock this customer from messaging?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                axios.patch(`/participants-message/${id}/unblock`)
                    .then(() => {
                        this.$modal.success({
                            message: 'Customer successfully unblocked from messaging',
                        });
                        this.getParticipants()
                    })
                    .catch();
            }).catch();
        },

        blockParticipantCart(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to block this customer from adding items to cart?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                axios.patch(`/participants-cart/${id}/block`)
                    .then(() => {
                        this.$modal.success({
                            message: 'Customer successfully blocked from adding items to cart',
                        });
                        this.getParticipants()
                    })
                    .catch();
            }).catch();
        },

        unblockParticipantCart(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to unblock this customer from adding items to cart?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                axios.patch(`/participants-cart/${id}/unblock`)
                    .then(() => {
                        this.$modal.success({
                            message: 'Customer successfully unblocked from adding items to cart',
                        });
                        this.getParticipants()
                    })
                    .catch();
            }).catch();
        },
        deleteAllParticipantMessages(customer) {
            this.$modal.dialog({
                message: 'Are you sure you want to delete all the messages of this customer?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
               app.$emit('deleteUserMessages', {
                customer_id: customer
               })
            }).catch();
        }

    }
}
</script>
<style>
.box-shadow {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
}


@media (min-width: 1024px) {
    .participants-container {
        max-height: 300px;
    }
}

.products-box-shadow, .participants-box-shadow {
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}


.tooltip {
    display: block !important;
    z-index: 10000;
  }

  .tooltip .tooltip-inner {
    background: black;
    color: white;
    border-radius: 16px;
    padding: 5px 10px 4px;
  }

  .tooltip .tooltip-arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
    border-color: black;
    z-index: 1;
  }

  .tooltip[x-placement^="top"] {
    margin-bottom: 5px;
  }

  .tooltip[x-placement^="top"] .tooltip-arrow {
    border-width: 5px 5px 0 5px;
    border-left-color: transparent !important;
    border-right-color: transparent !important;
    border-bottom-color: transparent !important;
    bottom: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }

  .tooltip[x-placement^="bottom"] {
    margin-top: 5px;
  }

  .tooltip[x-placement^="bottom"] .tooltip-arrow {
    border-width: 0 5px 5px 5px;
    border-left-color: transparent !important;
    border-right-color: transparent !important;
    border-top-color: transparent !important;
    top: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }

  .tooltip[x-placement^="right"] {
    margin-left: 5px;
  }

  .tooltip[x-placement^="right"] .tooltip-arrow {
    border-width: 5px 5px 5px 0;
    border-left-color: transparent !important;
    border-top-color: transparent !important;
    border-bottom-color: transparent !important;
    left: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }

  .tooltip[x-placement^="left"] {
    margin-right: 5px;
  }

  .tooltip[x-placement^="left"] .tooltip-arrow {
    border-width: 5px 0 5px 5px;
    border-top-color: transparent !important;
    border-right-color: transparent !important;
    border-bottom-color: transparent !important;
    right: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }

  .tooltip.popover .popover-inner {
    background: #f9f9f9;
    color: black;
    padding: 24px;
    border-radius: 5px;
    box-shadow: 0 5px 30px rgba(black, .1);
  }

  .tooltip.popover .popover-arrow {
    border-color: #f9f9f9;
  }

  .tooltip[aria-hidden='true'] {
    visibility: hidden;
    opacity: 0;
    transition: opacity .15s, visibility .15s;
  }

  .tooltip[aria-hidden='false'] {
    visibility: visible;
    opacity: 1;
    transition: opacity .15s;
  }

</style>

