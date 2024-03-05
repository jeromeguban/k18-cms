import { WebRTCAdaptor } from '@antmedia/webrtc_adaptor/dist/es/webrtc_adaptor.js'
import Vue from 'vue'
import Modal from '../plugins/Modal/ModalPlugin'
Vue.use(Modal);

class Stream {
    constructor(data) {
        this.data = data
        this.ws = null
        this.stream_id = data.stream_id
        this.stream_type = data.stream_type
        this.webRTCAdaptor = {
            localVideoId: null
        }
        this.devices = new Array
        this.video_input_devices = []
        this.active_video_input_device = sessionStorage.getItem(`active-video-input-${this.stream_id}`) || null
        this.audio_input_devices = []
        this.active_audio_input_device = sessionStorage.getItem(`active-audio-input-${this.stream_id}`) || null
        this.current_video_dimensions = {
            width: 0,
            height: 0
        }

        this.audio_contraints = {
            // channelCount: {
            //     exact: 2
            // },
            // sampleRate: {
            //     ideal: 48000
            // },
            channelCount: 2,
            // sampleRate: 48000,
            autoGainControl: false,
            noiseSuppression: false,
            echoCancellation: false,
            // latency: 0,
            // codec: {
            //     name: "opus",
            //     mimeType: "audio/opus",
            //     clockRate: 48000,
            //     channels: 2,
            //     bitrate: 128000
            // }
        }
        this.stream_stats = null
        /**
         * If publishing stops for any reason, it tries to republish again.
         */
        this._autoRepublishEnabled = true
        /**
         * Timer job that checks the WebRTC connection
         */
        this._autoRepublishIntervalJob = null
        this.is_streaming = false
        this.can_stream = true
        this.active_streamer = null
        this.is_validated_active_streamer = false
        this.is_muted = false
        this.ice_servers = data.ice_servers

        this.#validateIfStreamingDevicesExist()
        setTimeout(()=>{
            let is_currently_streaming = sessionStorage.getItem(`stream-${this.stream_id}`) || false
            this.#initWebRTCAdaptor(is_currently_streaming, this._autoRepublishEnabled);

        }, 100)


        this.#initializeWebsocket()

    }

    #validateIfStreamingDevicesExist() {
        if (!navigator.mediaDevices?.enumerateDevices) {
            console.log("enumerateDevices() not supported.");
        } else {
        navigator.mediaDevices
            .enumerateDevices()
            .then((devices) => {
                if (devices.length === 0) {
                    console.log("No media devices found.")
                } else {

                    let existing_cameras = devices.filter((device) => {
                        return device.kind == 'videoinput'
                    })

                    let existing_mics = devices.filter((device) => {
                        return device.kind == 'audioinput'
                    })

                    if(!existing_cameras.length || !existing_mics.length){
                        this.can_stream = false
                        this.showErrorMessage('No input device found. Stream is not possible.')
                    }

                }
            })
            .catch((err) => {
                console.error(`${err.name}: ${err.message}`)
            })
        }
    }

    #initWebRTCAdaptor(publishImmediately, autoRepublishEnabled) {
        const websocket_url = window.ams_url

        let mediaConstraints = {
            video: true,
            audio: this.audio_contraints,
        }

        let userAgent = navigator.userAgent;


        if(this.active_video_input_device) {
            mediaConstraints.video = {
                deviceId: this.active_video_input_device
            }
            // if(userAgent.match(/firefox|fxios/i)) {

            //     mediaConstraints.video = {
            //         deviceId: this.active_video_input_device
            //     }
            // } else {
            //     mediaConstraints.video = {
            //         mandatory: {},
            //         optional: [{
            //             sourceId: this.active_video_input_device
            //         }]
            //     }

            // }
        }


        if(this.active_audio_input_device ) {
            this.audio_contraints.deviceId = this.active_audio_input_device
            mediaConstraints.audio = this.audio_contraints
            // if(userAgent.match(/firefox|fxios/i)) {

            //     mediaConstraints.audio = {
            //         deviceId: this.active_audio_input_device
            //     }
            // } else {
            //     mediaConstraints.audio = {
            //         mandatory: {},
            //         optional: [{
            //             sourceId: this.active_audio_input_device
            //         }]
            //     }
            // }
        }


        this.webRTCAdaptor = new WebRTCAdaptor({
            websocket_url,
            mediaConstraints,
            peerconnection_config: {
                'iceServers': this.ice_servers
            },
            sdp_constraints: {
                OfferToReceiveAudio : false,
                OfferToReceiveVideo : false,
            },
            localVideoId: this.data.element, // `<video id="id-of-video-element" autoplay muted>``</video>`
            // bandwith: 'unlimited',
            // bandwith: 'unlimited',
            // dataChannelEnabled: true|false, // enable or disable data channel
            callback : (info, obj) => {
                if (info == "initialized") {
					console.log("initialized");
					if (publishImmediately) {
						this.startStream()
					}

                } else if (info == "publish_started") {
                    this.is_streaming = true
                    this.setAsActiveStreamer()
                    this.ws.emit('changeStreamType', {
                        stream_type: this.stream_type
                    })
                    console.log("publish started")
                    if (autoRepublishEnabled && this._autoRepublishIntervalJob == null) {
                        this._autoRepublishIntervalJob = setInterval(() => {
                            this.#checkAndRepublishIfRequired()
                        }, 3000);
                    }
                    this.webRTCAdaptor.enableStats(obj.streamId);

                    if(this.active_video_input_device)
                        sessionStorage.setItem(`active-video-input-${this.stream_id}`, this.active_video_input_device )

                    if(this.active_audio_input_device )
                        sessionStorage.setItem(`active-audio-input-${this.stream_id}`, this.active_audio_input_device)

                    sessionStorage.setItem(`stream-${this.stream_id}`, true)
                } else if (info == "publish_finished") {
                    this.is_streaming = false
                    this.removeAsActiveStreamer()
                    console.log("publish finished")
                    sessionStorage.removeItem(`stream-${this.stream_id}`)
                    sessionStorage.removeItem(`active-audio-input-${this.stream_id}`)
                    sessionStorage.removeItem(`active-video-input-${this.stream_id}`)
                }
                else if (info == "closed") {
					//console.log("Connection closed");
					if (typeof obj != "undefined") {
						console.log("Connecton closed: " + JSON.stringify(obj));
					}
				}
				else if (info == "pong") {
					//ping/pong message are sent to and received from server to make the connection alive all the time
					//It's especially useful when load balancer or firewalls close the websocket connection due to inactivity
				}
                else if (info == "refreshConnection") {
                    this.#checkAndRepublishIfRequired()
                }
                else if (info == "ice_connection_state_changed") {
					console.log("iceConnectionState Changed: ", JSON.stringify(obj));
				}
                else if (info == "updated_stats") {
					//obj is the PeerStats which has fields
					//averageOutgoingBitrate - kbits/sec
					//currentOutgoingBitrate - kbits/sec
                    this.stream_stats = obj
					console.log("Average outgoing bitrate " + obj.averageOutgoingBitrate + " kbits/sec"
						+ " Current outgoing bitrate: " + obj.currentOutgoingBitrate + " kbits/sec"
						+ " video source width: " + obj.resWidth + " video source height: " + obj.resHeight
						+ "frame width: " + obj.frameWidth + " frame height: " + obj.frameHeight
						+ " video packetLost: " + obj.videoPacketsLost + " audio packetsLost: " + obj.audioPacketsLost
						+ " video RTT: " + obj.videoRoundTripTime + " audio RTT: " + obj.audioRoundTripTime
						+ " video jitter: " + obj.videoJitter + " audio jitter: " + obj.audioJitter);

                    this.stream_stats.packetLost = parseInt(obj.videoPacketsLost) + parseInt(obj.audioPacketsLost)
                    this.stream_stats.jitter = ((parseFloat(obj.videoJitter) + parseInt(obj.audioJitter)) / 2).toPrecision(3)
                    this.stream_stats.rtt = ((parseFloat(obj.videoRoundTripTime) + parseFloat(obj.audioRoundTripTime)) / 2).toPrecision(3)

				}
                else if (info == "available_devices"){
                    if(typeof obj != 'undefined' && Symbol.iterator in Object(obj)) {
                        let cameraIndex = 0
                        let micIndex = 0
                        let deviceIndex = 0

                        for(let device of obj) {
                            let label = device.label;
                            let existing_device = false
                            for(let same_device of this.devices) {
                                if(same_device.deviceId == device.deviceId)
                                    existing_device = true
                                if (same_device.label == device.label) {
                                    deviceIndex++;
                                    label = device.label + " - " + deviceIndex
                                }
                            }

                            if(existing_device)
                                continue

                            if (device.kind == "videoinput") {
                                if (typeof label == "undefined" || label == "") {
                                    label = "Cam-" + cameraIndex;
                                }
                                cameraIndex++;

                                this.video_input_devices.push({
                                    deviceId: device.deviceId,
                                    groupId: device.groupId,
                                    kind: device.kind,
                                    label: label,
                                })
                            }
                            else if (device.kind == "audioinput") {
                                if (typeof label == "undefined" || label == "") {
                                    label = "Mic-" + micIndex;
                                }
                                micIndex++;
                                this.audio_input_devices.push({
                                    deviceId: device.deviceId,
                                    groupId: device.groupId,
                                    kind: device.kind,
                                    label: label,
                                })
                            }

                            this.devices.push({
                                deviceId: device.deviceId,
                                groupId: device.groupId,
                                kind: device.kind,
                                label: label,
                            })
                        }
                        if(! this.active_video_input_device )
                            this.active_video_input_device = this.video_input_devices.length ? this.video_input_devices[0].deviceId : null
                        if(! this.active_audio_input_device )
                            this.active_audio_input_device = this.audio_input_devices.length ? this.audio_input_devices[0].deviceId : null
                    }
                    console.log( info + " notification received")
                } else if (info == "session_restored") {
					this.is_streaming = true;
                    if(!this.active_streamer)
                        this.setAsActiveStreamer()
                    // this.active_streamer = window.sessionStorage.tabId
				}
				else {
					console.log(info + " notification received");
				}
            },
            callbackError: (error, message) => {

                console.log("error callback: " + JSON.stringify(error));

                let errorMessage = JSON.stringify(error);
                if (typeof message != "undefined") {
                    errorMessage = message;
                }

                errorMessage = JSON.stringify(error);

                if (error.indexOf("WebSocketNotConnected") != -1) {
                    errorMessage = "WebSocket Connection is disconnected.";
                }
                else if (error.indexOf("not_initialized_yet") != -1) {
                    errorMessage = "Server is getting initialized.";
                }
                else if (error.indexOf("data_store_not_available") != -1) {
                    errorMessage = "Data store is not available. It's likely that server is initialized or getting closed";
                }
                else {
                    if (error.indexOf("NotFoundError") != -1) {
                        errorMessage = "Camera or Mic are not found or not allowed in your device";
                    }
                    else if (error.indexOf("NotReadableError") != -1 || error.indexOf("TrackStartError") != -1) {
                        errorMessage = "Camera or Mic are already in use and they cannot be opened. Choose another video/audio source if you have on the page below ";
                    }
                    else if (error.indexOf("OverconstrainedError") != -1 || error.indexOf("ConstraintNotSatisfiedError") != -1) {
                        errorMessage = "There is no device found that fits your video and audio constraints. You may change video and audio constraints"
                    }
                    else if (error.indexOf("NotAllowedError") != -1 || error.indexOf("PermissionDeniedError") != -1) {
                        errorMessage = "You are not allowed to access camera and mic.";
                    }
                    else if (error.indexOf("TypeError") != -1) {
                        errorMessage = "Video/Audio is required";
                    }
                    else if (error.indexOf("getUserMediaIsNotAllowed") != -1) {
                        errorMessage = "You are not allowed to reach devices from an insecure origin, please enable ssl";
                    }
                    else if (error.indexOf("ScreenSharePermissionDenied") != -1) {
                        errorMessage = "You are not allowed to access screen share";
                    }
                    else if (error.indexOf("UnsecureContext") != -1) {
                        errorMessage = "Please Install SSL(https). Camera and mic cannot be opened because of unsecure context. ";
                    }
                    else {
                        errorMessage = error
                    }

                    if(errorMessage != 'streamIdInUse')
                        this.showErrorMessage(errorMessage)

                }
                console.error(errorMessage);
                if (message !== undefined) {
                    console.error(message);
                }
            }, // check error callbacks bellow
        })
        this.#setVideoSourceDimensions()
    }

    #initializeWebsocket() {
        window.addEventListener("beforeunload",  (event) => {
            // event.preventDefault()
            if(this.is_streaming)
                return event.returnValue = 'Confirm'
            // return null
        })
        window.addEventListener('unload', (event) => {
            if (event.persisted) {
                console.log('Page is being reloaded...')
            } else {
                this.removeAsActiveStreamer()
                console.log('Tab is being closed...')
            }
        })
		if(!this.stream_id) return console.error('No stream channel found.')

		try {
			this.ws = window.ws.subscribe(`stream:${this.stream_id}`)
		} catch (e) {
			this.ws = window.ws.getSubscription(`stream:${this.stream_id}`)
		}

		if(this.ws){
			this.#intializeEvents()
		}
	}

    #intializeEvents() {
        this.ws.emit('getStreamStatus')

        this.ws.on('receiveStreamStatus', (streamer) => {
            setTimeout(()=>{
                this.is_validated_active_streamer = true
                this.active_streamer = streamer
            }, 100)
        })
    }

    changeStreamType(payload) {
        this.stream_type = payload.stream_type
        this.ws.emit('changeStreamType',payload)
    }

    setAsActiveStreamer() {
        this.ws.emit('setAsActiveStreamer', window.sessionStorage.tabId)
    }

    removeAsActiveStreamer() {
        this.ws.emit('removeAsActiveStreamer', window.sessionStorage.tabId)
    }

    broadcastViewerCount(count) {
        this.ws.emit('broadcastViewerCount', count)
    }

    #setVideoSourceDimensions() {
        setTimeout(()=>{
            let video_player = document.querySelector(`#${this.data.element}`)
            if(video_player) {
                this.current_video_dimensions.height = video_player.videoHeight
                this.current_video_dimensions.width = video_player.videoWidth
            }
        }, 1000)
    }

    startStream() {


        this.webRTCAdaptor.publish(this.stream_id, "", "", "", "", "")

    }

    stopStream() {
        if (this._autoRepublishIntervalJob != null) {
			clearInterval(this._autoRepublishIntervalJob);
			this._autoRepublishIntervalJob = null;
		}
        this.webRTCAdaptor.stop(this.stream_id)
    }

    switchVideoSource(source) {
        this.active_video_input_device = source
        this.webRTCAdaptor.switchVideoCameraCapture(this.stream_id, source)
        this.#setVideoSourceDimensions()
    }

    switchAudioSource(source) {
        this.active_audio_input_device = source
        this.webRTCAdaptor.switchAudioInputSource(this.stream_id, source)
        this.webRTCAdaptor.mediaManager.setAudioInputSource(this.stream_id, {
            audio: this.audio_contraints
        }, null, true, source)

            // this.webRTCAdaptor.mediaManager.applyConstraints()

    }

    muteMic() {
        this.is_muted = true
        this.webRTCAdaptor.muteLocalMic()
    }
    unmuteMic() {
        this.is_muted = false
        this.webRTCAdaptor.unmuteLocalMic()
    }

    #checkAndRepublishIfRequired() {
		let iceState = this.webRTCAdaptor.iceConnectionState(this.stream_id);
		console.log("Ice state checked = " + iceState);

		if (iceState == null || iceState == "failed" || iceState == "disconnected") {
			this.webRTCAdaptor.stop(this.stream_id);
			this.webRTCAdaptor.closePeerConnection(this.stream_id);
			this.webRTCAdaptor.closeWebSocket();
			this.#initWebRTCAdaptor(true, this._autoRepublishEnabled);
		}
	}

    getCurrentVideoDimensions() {
        return this.current_video_dimensions
    }

    getStreamStats() {
        return this.stream_stats
    }

    isStreaming() {
        return this.active_streamer
    }

    isMuted() {
        return this.is_muted
    }

    showErrorMessage(message) {
        app.$modal.error({
            message,
            title: 'Ooops',
            timer: 4000
        })
    }
}

export default Stream;
