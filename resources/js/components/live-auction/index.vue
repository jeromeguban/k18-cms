<template>
    <div>
        <stream-controller 
            :class="{'hidden' : $route.path != '/live-auction/'+ $route.params.id +'/stream-settings'}" 
            :auction="auction"
            :url="url"/>
        <clerk-controller 
            :class="{'hidden' : $route.path != '/live-auction/'+ $route.params.id}" 
            :auction="auction" 
            :bid="bid"
            :url="url"/>
    </div>
</template>
<script>
import ClerkController from './clerk-controller'
import StreamController from './stream-controller'
import SimulcastBid from '../../Core/SimulcastBid'
export default {
    name:'live-auction',
    components: {
        ClerkController,
        StreamController
    },
    props: {
        url: {
			type: String,
			default: null
		},
    },
    data() {
        return {
            auction: {},
            bid: new SimulcastBid(this.$route.params.id)
        }
    },
    created() {
        this.show()
        this.setTabId()
    },
    methods: {
        show() {
                axios.get('/auctions/' + this.$route.params.id)
                .then(({data}) => {
                    this.auction = data
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setTabId() {
            // window.addEventListener("beforeunload",  (e) => {
            //     window.sessionStorage.tabId = window.tabId
            //     return null
            // })
            
            // window.addEventListener("load",  (e) => {
            //     if (window.sessionStorage.tabId)
            //     {
            //         window.tabId = window.sessionStorage.tabId
            //         window.sessionStorage.removeItem("tabId")
            //     }
            //     else
            //     {
            //         window.tabId = Date.now()
            //     }

            //     return null
            // })
            window.addEventListener("beforeunload",  (e) => {
                // window.sessionStorage.tabId = window.tabId
                return null
            })

            window.addEventListener("load",  (e) => {
                if (!window.sessionStorage.tabId)
                    window.sessionStorage.tabId = Date.now()
                return null
            })
        }
    }
}
</script>