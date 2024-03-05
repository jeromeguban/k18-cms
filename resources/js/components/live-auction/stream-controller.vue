<template>
	<div class="w-full">
        <div class="flex justify-between">
            <h1 class="text-xl my-5">{{ auction.auction_number }} - Live Stream Controller</h1>
            <router-link :to="'/live-auction/'+$route.params.id" class="bg-white border border-gray-300 px-4 h-10 flex items-center rounded hover:shadow-md  transition-all mt-3">
                <i class="fa-light fa-gear mr-1"></i>
                Go To Clerk Controller
            </router-link>
        </div>
        <div class="w-full">
            <vue-content-loading :width="100" :height="65" class="mt-4" v-if="!stream.is_validated_active_streamer || is_loading">
                <rect width="100" height="100" />
            </vue-content-loading>
            <div class="flex gap-4 relative" :class="{'hidden' : !stream.is_validated_active_streamer || is_loading}">
                <div class="flex flex-col stream-settings-container gap-y-4" :class="{ 'shrink-setting-container' : stream && stream.isStreaming()}">
                    <div class="bg-white rounded shadow-sm p-6">
                        <div class="flex flex-col ">
                            <h2 class="text-lg">Select a video source</h2>
                            <div class="flex gap-x-2 mt-2">
                                <!-- <div class="rounded flex flex-col items-center justify-center border border-gray-300 w-1/2 ">
                                    <div class="w-20 h-20 bg-primary rounded-full"></div>
                                    <span class="mt-2 font-medium">Camera</span>
                                </div>
                                <div class="rounded flex flex-col items-center justify-center border border-gray-300 w-1/2">
                                    <div class="w-20 h-20 bg-primary rounded-full"></div>
                                    <span class="mt-2 font-medium">Streaming Software</span>
                                </div> -->
                                <input type="radio" id="camera" name="video_src" value="camera" v-model="stream_type" checked>
                                <label for="camera" class="custom-box rounded flex flex-col items-center justify-center border border-gray-300 w-1/2 h-40 cursor-pointer">
                                    <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fa-light fa-camera fa-3x text-white"></i>
                                    </div>
                                    <span class="mt-2 font-medium">Camera</span>
                                </label>

                                <input type="radio" id="streaming-software" name="video_src" value="streaming_software" v-model="stream_type">
                                <label for="streaming-software" class="custom-box rounded flex flex-col items-center justify-center border border-gray-300 w-1/2 h-40 cursor-pointer">
                                    <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-key fa-3x text-white"></i>
                                    </div>
                                    <span class="mt-2 font-medium">Streaming Software</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded shadow-sm p-6" v-if="stream_type == 'camera'">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-lg">Select a video source</h2>
                            <span class="text-gray-700">Before going live, make sure your camera and microphone inputs are operational.</span>

                            <div class="flex flex-col gap-y-3">
                                <div class="flex mt-2">
                                    <div class="w-16 flex items-center justify-center">
                                        <i class="fa-solid fa-camera fa-xl"></i>
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
                                        <i class="fa-solid fa-microphone fa-xl"></i>
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
                            <h2 class="text-lg">Preview Link</h2>
                            <span class="text-gray-700">To see what your viewers see, click on the link below.</span>

                            <div class="flex mt-2">
                                <div class="w-16 flex items-center justify-center">
                                    <i class="fa-solid fa-link fa-xl"></i>
                                </div>
                                <input type="text" class="flex-1 text-gray-600 form-control mr-2"
                                    :value="url+'/auctions/'+auction.slug+'/simulcast#/'">

                                <button class="rounded bg-primary text-white px-6 py-2" @click.prevent="viewNow()">View Now</button>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="video-output-container" :class="{ 'expand-output-container' : stream && stream.isStreaming()}">
                    <div class="bg-white rounded shadow-sm p-6">
                        <div class="flex flex-col gap-y-2">
                            <div class="flex justify-between">
                                <h2 class="text-lg">Video</h2>
                                <div class="flex gap-x-2">
                                    <button
                                        v-if="!stream.isMuted()"
                                        @click.prevent="muteMic()"
                                        class="rounded btn btn-danger flex items-center justify-center">
                                        <i class="fa-regular fa-microphone-slash mr-2"></i>
                                        Mute Mic
                                    </button>
                                    <button
                                        v-if="stream.isMuted()"
                                        @click.prevent="unmuteMic()"
                                        class="rounded btn btn-primary flex items-center justify-center">
                                        <i class="fa-regular fa-microphone mr-2"></i>
                                        Unmute Mic
                                    </button>
                                    <button
                                        v-if="stream && !stream.isStreaming()"
                                        @click.prevent="stream.can_stream ? startStream() : null"
                                        :disabled="!stream.can_stream"
                                        class="rounded btn border border-gray-300 flex items-center justify-center">
                                        <i class="fa-solid fa-signal-stream mr-2"></i>
                                        Go Live
                                    </button>


                                    <button
                                        v-if="stream.isStreaming()"
                                        @click.prevent="stream.stopStream()"
                                        class="rounded btn btn-danger flex items-center justify-center">
                                        <i class="fa-solid fa-signal-stream-slash mr-2"></i>
                                        End Live Now
                                    </button>
                                </div>
                            </div>

                            <div class="bg-black rounded flex items-center justify-center py-6 relative">
                                <div
                                    v-if="viewer_count > 0"
                                    class="absolute top-0 left-0 px-2 py-1 text-white w-20 rounded flex items-center justify-center mt-2 ml-2"
                                    style="background-color: rgba(255,255,255,0.4)">
                                    <i class="fa-solid fa-users mr-2"></i>
                                    {{ viewer_count }}
                                </div>
                                <video class="w-full bg-cover bg-center" id="stream-video" style="height: 22rem;" autoplay muted controls playsinline></video>
                                <!-- <iframe class="w-full bg-cover bg-center" style="height: 22rem;" src="http://127.0.0.1:5080/LiveApp/play.html?id=TEST-12345"></iframe> -->
                            </div>

                            <div class="flex justify-between mt-1">
                                <div class="flex flex-col items-center w-1/2 border-r border-gray-200">
                                    <span class="text-lg font-semibold">1080p</span>
                                    <span>Max Resolution</span>
                                </div>

                                <div class="flex flex-col items-center w-1/2">
                                    <span class="text-lg font-semibold" v-if="!current_source_resolution.height || !current_source_resolution.width">-</span>
                                    <span class="text-lg font-semibold" v-else>{{ current_source_resolution.width }} x {{ current_source_resolution.height }}</span>
                                    <span>Video Resolution</span>
                                </div>

                            </div>


                            <div class="bg-gray-100 border border-gray-200 rounded p-4 mt-2 gap-3 flex flex-col" v-if="stream && stream.isStreaming() && stream_stats">
                                <h2 class="text-lg">Stream Metrics</h2>
                                <div class="grid grid-cols-9 gap-3 w-full">
                                    <div class="col-span-3">
                                        <span class="font-semibold">Average Bitrate(kbps):</span>
                                        <span>{{ stream_stats.averageOutgoingBitrate}} kbits/sec</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">Latest Bitrate(Kbps):</span>
                                        <span>{{ stream_stats.currentOutgoingBitrate }} kbits/sec</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">PacketsLost:</span>
                                        <span>{{ stream_stats.packetLost }}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">Jitter(Secs):</span>
                                        <span>{{ stream_stats.jitter }}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">Audio Level:</span>
                                        <span>0.00</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">Round Trip Time(Secs):</span>
                                        <span>{{ stream_stats.rtt }}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">Source WidthxHeight:</span>
                                        <span>{{ stream_stats.resWidth }} x {{ stream_stats.resHeight}}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">On-going WidthxHeight:</span>
                                        <span>{{ stream_stats.frameWidth }} x {{ stream_stats.frameHeight}}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-semibold">On-going FPS:</span>
                                        <span>{{ stream_stats.currentFPS }}</span>
                                    </div>
                                </div>
                            </div>

                            <span class="text-black font-semibold mt-1.5">Who can watch your live stream?</span>
                            <div class="flex mt-1">
                                <div class="w-16 flex items-center justify-center">
                                    <i class="fa-solid fa-user fa-xl"></i>
                                </div>
                                <select
                                    class="flex-1 form-select text-gray-600"
                                    @change.prevent="updateStreamType()"
                                    v-model="form.stream_type">
                                    <option value="Private">Registered Bidders Only</option>
                                    <option value="Public">Open To Everyone</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded shadow-sm p-6 mt-4" v-if="stream && stream.isStreaming()">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-lg">Preview Link</h2>
                            <span class="text-gray-700">To see what your viewers see, click on the link below.</span>

                            <div class="flex mt-2">
                                <div class="w-16 flex items-center justify-center">
                                    <i class="fa-solid fa-link fa-xl"></i>
                                </div>
                                <input type="text" class="flex-1 text-gray-600 form-control mr-2"
                                    :value="url+'/auctions/'+auction.slug+'/simulcast#/'">
                                <button class="rounded bg-primary text-white px-6 py-2" @click.prevent="viewNow()">View Now</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="is_someone_streaming"
                    class="absolute top-0 left-0 w-full h-full flex items-center justify-center text-gray-600 text-4xl" style="background-color: rgba(255,255,255);">
                    <i class="fa-solid fa-lock-keyhole mr-4"></i>
                    <span>Sorry, someone is currently broadcasting at the moment</span>
                </div>
            </div>
        </div>

	</div>
</template>
<style scoped>
    .bg-primary {
        background-color: #1C3FAA;
    }

    input[type="radio"] {
        opacity: 0;
        position: absolute;
    }

    input[type="radio"]:checked + .custom-box {
        border: 1px solid #1C3FAA;
    }

    input[type="radio"]:checked  + .custom-box div {
        background-color: #1C3FAA;
    }

    .stream-settings-container {
        flex-basis: 50%;
        transition: flex-basis 500ms ease-in-out;
    }
    .video-output-container {
        flex-basis: 50%;
        transition: flex-basis 500ms ease-in-out;
    }

    .shrink-setting-container {
        flex-basis: 0px;
        visibility: hidden;
        width: 0px;
    }

    .expand-output-container {
        flex-basis: 100%;
    }



</style>
<script>
import VueContentLoading from 'vue-content-loading';
export default {
    name:'stream-controller',
    props: {
        auction: {
			type: Object,
			required: true
		},
        url: {
			type: String,
			default: null
		},
    },
    components: {
        VueContentLoading
    },
    data() {
        return {
            stream_type: 'camera',
            payload: {
                element: 'stream-video',
                stream_id: this.auction.stream_id,
                ice_servers: []
            },
            is_someone_streaming: null,
            viewer_count: 0,
            is_retrieving_viewer_count: false,
            is_loading: true,
            form: new Form({
                stream_type: this.auction.stream_type
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
            return this.auction ? this.auction.rtmp_url.replace(this.auction.stream_id, '') : null
        },
    },
    watch: {
        'auction'(){
            this.payload.stream_id = this.auction.stream_id
            this.form.stream_type = this.auction.stream_type
            this.payload.stream_type = this.auction.stream_type
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
        startStream() {
            this.stream.startStream()
        },
        muteMic() {
            this.$store.commit('muteMic')
        },
        unmuteMic() {
            this.$store.commit('unmuteMic')
        },
        viewNow(){
            window.open(this.url+'/auctions/'+this.auction.slug+'/simulcast#/' , "_blank")
        },
        getStreamStats() {
            if(!this.stream.isStreaming() || this.is_someone_streaming || this.is_retrieving_viewer_count) return
            this.is_retrieving_viewer_count = true
            axios.get(`/streams/${this.auction.stream_id}/count`)
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
            this.form.patch(`/auctions/${this.auction.auction_id}/stream-type`)
                .then(()=>{
                    this.stream.changeStreamType({
                        stream_type: this.form.stream_type
                    })
                })
        }

    }
}
</script>
