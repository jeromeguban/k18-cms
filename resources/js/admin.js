require("./bootstrap");
require("./component");
import MobileMenu from "./layouts/mobile-menu.vue";
import Sidebar from "./layouts/sidebar.vue";
import Navbar from "./layouts/navbar.vue";
import Container from "./layouts/container.vue";
import router from "./components/routes.js";

import Modal from "./plugins/Modal/ModalPlugin.js";
Vue.use(Modal);

import "dtoaster/dist/dtoaster.css";
import DToaster from "dtoaster";
import ToasterPresets from "./toast_presets.json"; //Your predefined toasts presets (optionally)

Vue.use(DToaster, {
    presets: ToasterPresets,
    position: "top-right", //toasts container position on the screen
    containerOffset: "100px", //toasts container offset from top/bottom of the screen
});

import Snotify, { SnotifyPosition } from "vue-snotify";

import VueFontAwesomePicker from "vfa-picker";
Vue.use(VueFontAwesomePicker);


import VueDatePicker from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';
Vue.use(VueDatePicker);

import VueFullscreen from 'vue-fullscreen'
Vue.use(VueFullscreen);


import loader from "vue-ui-preloader";
Vue.use(loader);

const options = {
    toast: {
        position: SnotifyPosition.rightTop,
    },
};

Vue.use(Snotify, options);


import Vuex from 'vuex'
import Stream from './Core/Stream'
Vue.use(Vuex)

import VueSimpleHotkey from 'vue-simple-hotkey'
Vue.use(VueSimpleHotkey)

// import CustomGoogleAutocomplete from 'vue-custom-google-autocomplete'
// Vue.use(CustomGoogleAutocomplete)

const store = new Vuex.Store({
    state: {
        steam_id: null,
        stream: {
            isStreaming: () => false,
            isMuted: () => false,
            getCurrentVideoDimensions: () => {
                return {
                    height: null,
                    width: null
                }
            },
            webRTCAdaptor: {
                localVideoId: null
            },
            active_streamer: null,
            is_validated_active_streamer: false
        },
    },
    getters: {
        stream(state) {
            return state.stream
        },
        webRTCAdaptor(state) {
            return state.stream.webRTCAdaptor
        }
    },
    mutations: {
        setStream(state, payload) {
            state.stream = new Stream(payload)
            state.steam_id = payload.stream_id
        },
        muteMic(state) {
            state.stream.muteMic()
        },
        unmuteMic(state) {
            state.stream.unmuteMic()
        },
    }
})

new Vue({
    el: "#app",
    components: { Navbar, Sidebar, Container, MobileMenu },
    router,
    store,
    watch: {
        '$route': {
            immediate: true,
            handler() {
                if (this.$route.path.includes("events") && this.$route.path.includes("live-selling")) {
                    sessionStorage.setItem('isLiveSellingControllerRoute', 'true');
                } else {
                    sessionStorage.removeItem('isLiveSellingControllerRoute');
                }
            }
        } ,
    }
});
