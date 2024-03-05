<template>
    <div>
        <div class="relative mb-4">
            <video
                style="'height: 400px'"
                class="rounded-lg w-full livestream-img"
                id="stream-video"
                autoplay muted controls playsinline></video>
            <span
                v-if="stream && stream.isStreaming() && !is_someone_streaming"
                class="uppercase bg-primary-3 text-white absolute top-4 left-4 px-4 py-1 lg:text-xs xl:text-sm font-semibold rounded-md">Live</span>
    
            <!-- <span class="bg-white bg-opacity-80 absolute bottom-4 left-4 pl-4 lg:pr-24 xl:pr-32 py-4 rounded-md">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 384 512" class="stroke-1.5  text-primary-2"><path d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z"/></svg>
                        <a href="" class="lg:text-xs xl:text-sm font-normal text-primary-2 underline">Pinned Message</a>
                    </div>
    
                    <div class="w-full" :style="'padding-left: 23px;'">
                        <p class="italic lg:text-xs xl:text-sm font-medium mb-2 text-black" >The next item will be 60% Off.</p>
                        <button :style="'font-size: 10px'" class="bg-primary-3 text-white px-2 py-1 rounded-md remove-btn">Remove</button>
                    </div>
                </div>
            </span> -->
        </div>
        <!-- END OF LIVE STREAMING -->
    
        <!-- START OF LIVE STREAMING DETAILS -->
        <div class="mb-3">
            <div class="block sm:hidden lg:block xl:hidden">
                <div
                    class="flex gap-2 mb-2"
                    v-if="!is_someone_streaming">
                    <!-- <button class="flex items-center gap-1 border text-gray-600 px-4 py-1 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="mic-off" data-lucide="mic-off" class="lucide lucide-mic-off stroke-1.5 mx-auto block"><line x1="2" y1="2" x2="22" y2="22"></line><path d="M18.89 13.23A7.12 7.12 0 0 0 19 12v-2"></path><path d="M5 10v2a7 7 0 0 0 12 5"></path><path d="M15 9.34V5a3 3 0 0 0-5.68-1.33"></path><path d="M9 9v3a3 3 0 0 0 5.12 2.12"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
                        <span :style="'font-size: 12px '" class="turn-off-mic-text">Mute Mic</span>
                    </button> -->
    
                    <button
                        v-if="!stream.isMuted()"
                        @click.prevent="muteMic()"
                        class="flex items-center gap-1 border text-gray-600 px-4 py-1 rounded-lg">
                        <i class="fa-regular fa-microphone-slash mr-2"></i>
                        <span :style="'font-size: 12px '" class="turn-off-mic-text">Mute Mic</span>
                    </button>
                    <button
                        v-if="stream.isMuted()"
                        @click.prevent="unmuteMic()"
                        class="flex items-center gap-1 border text-gray-600 px-4 py-1 rounded-lg">
                        <i class="fa-regular fa-microphone mr-2"></i>
                        <span :style="'font-size: 12px '" class="turn-off-mic-text">Unmute Mic</span>
                    </button>
    
                    <button
                        v-if="stream && stream.isStreaming()"
                        @click.prevent="stream.stopStream()"
                        class="flex items-center gap-1 bg-primary-3 text-white px-4 py-1 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18   " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="stop-circle" data-lucide="stop-circle" class="lucide lucide-stop-circle stroke-1.5 mx-auto block"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>
                        <span :style="'font-size: 12px'" class="end-live-text">End Live</span>
                    </button>
    
                    <button
                        v-if="stream && !stream.isStreaming() && event.holded_at"
                        @click.prevent="stream.can_stream ? startStream() : null"
                        :disabled="!stream.can_stream"
                        class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg">
                        <div>
                            <i class="fa-solid fa-signal-stream "></i>
                        </div>
    
                        <div class="go-live-text">
                            Go Live
                        </div>
                    </button>
                    <button
                        v-if="stream && !stream.isStreaming() && !event.holded_at"
                        @click.prevent="lockProducts()"
                        :disabled="!stream.can_stream"
                        class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg">
                        <div>
                            <i class="fa-solid fa-lock-keyhole "></i>
                        </div>
    
                        <div class="go-live-text">
                            Lock Products
                        </div>
                    </button>
    
                    <button
                        v-if="stream && !stream.isStreaming() && event.holded_at"
                        @click.prevent="lockProducts()"
                        :disabled="!stream.can_stream"
                        class="flex items-center gap-2 bg-primary-3 text-white px-4 py-1 rounded-lg">
                        <div>
                            <i class="fa-solid fa-lock-keyhole-open"></i>
                        </div>
    
                        <div class="go-live-text">
                            Unlock Products
                        </div>
                    </button>
    
                </div>
    
            </div>
    
            <div class="flex justify-between items-center gap-2 mb-2">
                <div class="flexrelative">
                    <h2  class="font-semibold text-black mb-1 text-sm md:text-base xl:text-xl">{{ event.event_name }}</h2>
                    <!-- <p class="italic text-sm xl:text-base text-gray-600">{{ event.description }}</p> -->
                    <!-- <svg :style="'right: 24px'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit stroke-1.5 absolute -top-2 text-primary-2 svg-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> -->
                </div>
    
                <div class="hidden sm:block lg:hidden xl:block lg:mt-0  xl:mt-1"
                    v-if="!is_someone_streaming">
                    <div class="flex gap-2">
                        <button
                            v-if="!event.published_date && stream && !stream.isStreaming()"
                            @click.prevent="publishEvent()"
                            :disabled="!stream.can_stream"
                            class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-signal-stream"></i>
                            </div>
    
                            <div class="go-live-text">
                                Publish Event
                            </div>
                        </button>

                        <button
                            v-if="event.published_date  && stream && !stream.isStreaming()"
                            @click.prevent="unpublishEvent()"
                            class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-signal-stream"></i>
                            </div>
    
                            <div class="go-live-text">
                                Unpublish Event
                            </div>
                        </button>

                        <button
                            v-if="event.published_date"
                            @click.prevent="refreshEventCache()"
                            class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-refresh"></i>
                            </div>
    
                            <div class="go-live-text">
                                Refresh
                            </div>
                        </button>

                        <button
                            class="flex items-center gap-1 border btn btn-primary px-4 py-2 rounded-lg"
                            v-if="!stream.isMuted()"
                            @click.prevent="muteMic()">
                            <i class="fa-regular fa-microphone-slash mr-2"></i>
                            <span class="turn-off-mic-text">Mute Mic</span>
                        </button>
                        <button
                            class="flex items-center gap-1 border text-gray-600 px-4 py-2 rounded-lg"
                            v-if="stream.isMuted()"
                            @click.prevent="unmuteMic()">
                            <i class="fa-regular fa-microphone mr-2"></i>
                            <span class="turn-off-mic-text">Unmute Mic</span>
                        </button>
    
    
                        <button
                            v-if="stream && stream.isStreaming()"
                            @click.prevent="stream.stopStream()"
                            class="flex items-center gap-1 bg-primary-3 text-white px-4 py-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18   " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="stop-circle" data-lucide="stop-circle" class="lucide lucide-stop-circle stroke-1.5 mx-auto block"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>
                            <span class="end-live-text">End Live</span>
                        </button>
    
                        <button
                            v-if="stream && !stream.isStreaming() && event.holded_at"
                            @click.prevent="stream.can_stream ? startStream() : null"
                            :disabled="!stream.can_stream"
                            class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-signal-stream"></i>
                            </div>
    
                            <div class="go-live-text">
                                Go Live
                            </div>
                        </button>
    
                        <button
                            v-if="stream && !stream.isStreaming() && !event.holded_at"
                            @click.prevent="lockProducts()"
                            class="flex items-center gap-2 bg-primary-2 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-lock-keyhole"></i>
                            </div>
    
                            <div class="go-live-text">
                                Lock Products
                            </div>
                        </button>
    
                        <button
                            v-if="stream && !stream.isStreaming() && event.holded_at"
                            @click.prevent="unlockProducts()"
                            class="flex items-center gap-2 bg-primary-3 text-white px-4 py-1 rounded-lg ">
                            <div>
                                <i class="fa-solid fa-lock-keyhole-open"></i>
                            </div>
    
                            <div class="go-live-text">
                                Unlock Products
                            </div>
                        </button>
                    </div>
                </div>
            </div>
    
            <!-- START OF TAB -->
            <div class="mt-2 text-base flex gap-x-2">
                <div @click="changeTab('current product')" class="p-2 px-3 cursor-pointer bg-gray-200 text-gray-700 text-sm rounded" :class="{ 'bg-gray-600 text-white': currentTab == 'current product' }">Current Product</div>
                <div @click="changeTab('settings')" class="py-2 px-6 cursor-pointer bg-gray-200 text-gray-700 text-sm rounded" :class="{ 'bg-gray-600 text-white': currentTab == 'settings' }">Settings</div>
            </div>
            <!-- END OF TAB -->
    
            <div v-if="currentTab == 'settings'">
                <div class="bg-white rounded shadow-sm p-6" v-if="stream_type == 'camera'">
                    <div class="flex flex-col gap-y-2">
                        <h2 class="text-sm md:text-base lg:text-lg font-medium">Select a video source</h2>
                        <span class="text-gray-700">Before going live, make sure your camera and microphone inputs are operational.</span>
    
                        <div class="flex flex-col gap-y-3">
                            <div class="flex mt-2">
                                <div class="w-16 flex items-center justify-center">
                                    <div class="hidden lg:block">
                                        <i class="fa-solid fa-camera fa-xl"></i>
                                    </div>
    
                                    <div class="block lg:hidden">
                                        <i class="fa-solid fa-camera fa-lg"></i>
                                    </div>
                                </div>
                                <select
                                    class="flex-1 form-select text-gray-600"
                                    @change="stream.switchVideoSource($event.target.value)"
                                    :value="stream.active_video_input_device"
                                    >
                                    <option
                                        v-for="(device, index) in stream.video_input_devices"
                                        :key="index"
                                        :value="device.deviceId">{{ device.label }}</option>
                                </select>
                            </div>
                            <div class="flex">
                                <div class="w-16 flex items-center justify-center">
                                    <div class="hidden lg:block">
                                        <i class="fa-solid fa-microphone fa-xl"></i>
                                    </div>
    
                                    <div class="block lg:hidden">
                                        <i class="fa-solid fa-microphone fa-lg"></i>
                                    </div>
                                </div>
                                <select
                                    class="flex-1 form-select text-gray-600"
                                    @change="stream.switchAudioSource($event.target.value)"
                                    :value="stream.active_audio_input_device"
                                    >
                                    <option
                                        v-for="(device, index) in stream.audio_input_devices"
                                        :key="index"
                                        :value="device.deviceId">{{ device.label }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="bg-white rounded shadow-sm p-6" v-if="stream_type == 'streaming_software'">
                    <div class="flex flex-col gap-y-2">
                        <h2 class="text-lg">Streaming Software Setup</h2>
                        <span class="text-gray-700">Enter the stream key from your streaming software.</span>
    
                        <div class="flex flex-col gap-y-3">
                            <div class="flex">
                                <div class="w-16 flex items-center justify-center">
                                    <i class="fa-solid  fa-link fa-xl"></i>
                                </div>
                                <input
                                    type="text"
                                    class="flex-1 text-gray-600 form-control mr-2"
                                    style="cursor: pointer;"
                                    placeholder="Server URL"
                                    :value="rtmp_url"
                                    disabled>
                            </div>
                            <div class="flex mt-2">
                                <div class="w-16 flex items-center justify-center">
                                    <i class="fa-solid  fa-key fa-xl"></i>
                                </div>
                                <input
                                    type="text"
                                    class="flex-1 text-gray-600 form-control mr-2"
                                    style="cursor: pointer;"
                                    placeholder="Stream Key"
                                    :value="auction.stream_id"
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="bg-white rounded shadow-sm p-6" >
                    <div class="flex flex-col gap-y-2">
                        <h2 class="text-sm md:text-base lg:text-lg font-medium">Preview Link</h2>
                        <span class="text-gray-700">To see what your viewers see, click on the link below.</span>
    
                        <div class="flex mt-2">
                            <div class="w-16 flex items-center justify-center">
                                <div class="hidden lg:block">
                                    <i class="fa-solid fa-link fa-xl"></i>
                                </div>
    
                                <div class="block lg:hidden">
                                    <i class="fa-solid fa-link fa-lg"></i>
                                </div>
                            </div>
                            <input type="text" class="flex-1 text-gray-600 form-control mr-2"
                                :value="'https://hmr.ph/live-selling/'+event.slug">
                            <button class="rounded bg-theme-4 text-white py-1 px-4 lg:px-6 lg:py-2" @click.prevent="viewNow()">View Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF LIVE STREAMING DETAILS -->
    </div>
    </template>
    <script>
    import VueContentLoading from 'vue-content-loading';
    export default {
        name: 'stream',
        props: {
            event: {
                type: Object,
                required: true
            },
    
            currentTab: {
                type: String
            }
        },
        components: {
            VueContentLoading
        },
        computed: {
    
        },
        data() {
            return {
                stream_type: 'camera',
                payload: {
                    element: 'stream-video',
                    stream_id: this.event.stream_id,
                    ice_servers: []
                },
                is_someone_streaming: null,
                viewer_count: 0,
                is_retrieving_viewer_count: false,
                is_loading: true,
                form: new Form({
                    stream_type: this.event.stream_type
                }, false)
            }
        },
        computed: {
            'stream'() {
                return this.$store.getters.stream
            },
            'current_source_resolution'() {
                return this.stream.getCurrentVideoDimensions()
            },
            'stream_stats'() {
                return this.stream.getStreamStats()
            },
            'webRTCAdaptor'() {
                return this.$store.getters.webRTCAdaptor
            },
            'rtmp_url'() {
                return this.auction ? this.auction.rtmp_url.replace(this.event.stream_id, '') : null
            },
        },
        watch: {
            'event'(){
                this.payload.stream_id = this.event.stream_id
                this.form.stream_type = this.event.stream_type
                this.payload.stream_type = this.event.stream_type
                // Need to load  first the needed payload before to call setStream Function
    
            },
            'stream.active_streamer'() {
    
                if(!this.stream.is_validated_active_streamer || (this.stream.is_validated_active_streamer && this.stream.active_streamer === null)){
                    this.is_someone_streaming = false
                    return
                }
    
                // this.is_someone_streaming = this.stream.is_validated_active_streamer && this.stream.active_streamer && this.stream.active_streamer != window.sessionStorage.tabId
            },
            'viewer_count'() {
                this.stream.broadcastViewerCount(this.viewer_count)
            }
        },
        created() {
            // if(!this.webRTCAdaptor.localVideoId)
            //     this.$store.commit('setStream', this.payload)
    
    
            this.getIceServers()
            this.validateIfStreamAndIceServersExist()
            setTimeout(()=>{
                setInterval(()=>{
                    if(!this.is_loading)
                        this.getStreamStats()
                }, 1000)
            }, 1000)
        },
        activated() {
           document.querySelector('#stream-video').play()
        },
        methods: {
            changeTab(tab) {
                this.$emit('change-tab', tab);
            },
    
            startStream() {
                this.stream.startStream()
            },
            muteMic() {
                console.log('mute')
                this.$store.commit('muteMic')
            },
            unmuteMic() {
                console.log('unmute')
                this.$store.commit('unmuteMic')
            },
            viewNow(){
                window.open('https://hmr.ph/live-selling/'+this.event.slug, "_blank")
            },
            getStreamStats() {
                if(!this.stream.isStreaming() || this.is_someone_streaming || this.is_retrieving_viewer_count) return
                this.is_retrieving_viewer_count = true
                axios.get(`/streams/${this.event.stream_id}/count`)
                    .then(({data})=>{
                        this.viewer_count = data.data.count
                        this.is_retrieving_viewer_count = false
                    })
            },
            getIceServers() {
                axios.get('/ice-servers')
                    .then(({data})=>{
                        this.payload.ice_servers = data
                    })
            },
            validateIfStreamAndIceServersExist() {
                let interval = setInterval(()=>{
                    if(this.payload.stream_id && this.payload.ice_servers.length) {
                        if(!this.webRTCAdaptor.localVideoId){
                            this.$store.commit('setStream', this.payload)
                        }
                        this.is_loading = false
                        clearInterval(interval)
                    }
                }, 1000)
    
            },
            updateStreamType() {
                this.form.patch(`/events/${this.event.event_id}/stream-type`)
                    .then(()=>{
                        this.stream.changeStreamType({
                            stream_type: this.form.stream_type
                        })
                    })
            },
            lockProducts() {
                axios.post(`/events/${this.event.event_id}/hold-postings`)
                    .then(({data})=>{
                        this.event.holded_at = true
                    })
            },
            unlockProducts() {
                axios.delete(`/events/${this.event.event_id}/hold-postings`)
                    .then(({data})=>{
                        this.event.holded_at = null
                    })
            },
            publishEvent(){
                this.$modal.dialog({
                    message: 'Are you sure you want to Publish this event?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Publish'
                })
                .then(confirmed => {
                    axios.patch(`/events/${this.event.event_id}/publish`)
                    .then(()=>{
                        this.$modal.success({
                            message: 'You successfully publish a event',
                            title: 'Published'
                        });
                    // Reload page
                    setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch((error)=>{
                    if(error.response.status)
                    this.$modal.error({
                        message:error.response.data.message,
                    });
                });
                })
            },
            unpublishEvent() {
                this.$modal.dialog({
                    message: 'Are you sure you want to unpublish this event?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Unpublish'
                })
                .then(confirmed => {
                    axios.patch(`/events/${this.event.event_id}/unpublish`)
                    .then(()=>{
                        this.$modal.success({
                            message: 'You Unpublish this event',
                            title: 'Unpublished'
                        });
                        // Reload page
                        setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch((error)=>{
                    if(error.response.status)
                    this.$modal.error({
                        message:error.response.data.message,
                    });
                });
                })
            },

            refreshEventCache(){
                this.$modal.dialog({
                    message: 'Are you sure you want to Refresh this event?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Refresh'
                })
                .then(confirmed => {
                    axios.patch(`/refresh/${this.event.event_id}/cache`)
                    .then(()=>{
                        this.$modal.success({
                            message: 'Successfully Refreshed',
                            title: 'Refreshed'
                        });
                        // Reload page
                        setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch((error)=>{
                    if(error.response.status)
                    this.$modal.error({
                        message:error.response.data.message,
                    });
                });
                })
            },
        }
    }
    </script>
    
    
    <style scoped>
    @media (min-width: 100px) {
        /* START OF LIVESTREAMING */
        .turn-off-mic-text, .go-live-text, .end-live-text {
            font-size: 10px !important;
        }
    
        .livestream-img {
            width: 100% !important;
        }
        /* END OF LIVESTREAMING */
    
        /* START OF LIVESTREAMING DETAILS */
        .next-product-text, .prev-product-text {
            font-size: 10px !important;
        }
    
        .mark-as-sold-text, .now-selling-text {
            font-size: 11px !important;
        }
        /* END OF LIVESTREAMING DETAILS */
    }
    
    @media (min-width: 1024px) {
        /* START OF LIVESTREAMING */
        .livestream-img {
            height: 400px !important;
        }
        /* END OF LIVESTREAMING */
    
        /* START OF LIVESTREAMING DETAILS */
        .next-product-text, .prev-product-text {
            font-size: 12px !important;
        }
    
        .mark-as-sold-text, .now-selling-text {
            font-size: 12px !important;
        }
        /* END OF LIVESTREAMING DETAILS */
    }
    
    @media (min-width: 1280px) {
        /* START OF LIVESTREAMING */
        .turn-off-mic-text, .go-live-text, .end-live-text {
            font-size: 12px !important;
        }
    
        .livestream-img {
            height: 400px !important;
        }
    
        .remove-btn {
            font-size: 12px !important;
        }
        /* END OF LIVESTREAMING */
    
         /* START OF LIVESTREAMING DETAILS */
         .svg-edit {
            right: -2px !important;
        }
    
        .copy-link-textfield {
            width: 400px !important;
        }
         /* END OF LIVESTREAMING DETAILS */
    }
    
    
    
    @media (min-width: 1536px) {
        /* START OF LIVESTREAMING */
        .livestream-img {
            height: 465px !important;
        }
        /* END OF LIVESTREAMING */
    }
    </style>
    