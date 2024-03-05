<template>
     <fullscreen v-model="fullscreen" class=" overflow-auto" >
        <div  class="grid grid-cols-12 relative pb-12" ref="liveSellingContainer">
            <button type="button" class="mb-6 absolute top-2 right-2" @click="toggle" v-hotkey="['alt+m','ctrl+m','alt+shift+m']">
                <img v-if="!fullscreen" class="outline-none" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E" alt="" style="height: 18px; width: 18px;">
                <img v-if="fullscreen" class="outline-none" src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M4%204H0v2h6V0H4v4zm10%200V0h-2v6h6V4h-4zm-2%2014h2v-4h4v-2h-6v6zM0%2014h4v4h2v-6H0v2z%22/%3E%3C/svg%3E" alt="" style="height: 18px; width: 18px;">
            </button>
            <!-- START OF FIRST COLUMN -->
            <div class="col-span-3 bg-gray-200 hidden lg:p-2 xl:p-4 lg:block">
                <!-- START OF PRODUCTS -->
                    <div class="flex justify-between items-center mb-3">

                        <h2 class="lg:text-lg xl:text-xl font-semibold text-black">Products</h2>
                        <a href="javascript:;" data-toggle="modal" data-target="#add-posting" class="lg:p-1 bg-primary-2 rounded-full text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" data-lucide="plus" class="lucide lucide-plus stroke-1.5 mx-auto block"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </a>
                    </div>

                    <div class="mb-2">
                        <div class="flex items-center justify-between gap-1">
                            <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('onQueueProducts')">
                                <h6 class="font-medium lg:text-xs xl:text-sm 2xl:text-sm" :class="`${tabPanelName === 'onQueueProducts' ? 'text-primary-2' : 'text-gray-500'}`">On Queue</h6>
                                <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'" class=" rounded-full font-bold total-number" :class="`${tabPanelName === 'onQueueProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="on_queue_products">{{ on_queue_products.length }}</span>
                            </div>

                            <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('previousProducts')">
                                <h6 class="font-medium lg:text-xs xl:text-sm 2xl:text-md" :class="`${tabPanelName === 'previousProducts' ? 'text-primary-2' : 'text-gray-500'}`">Previous</h6>
                                <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'"  class="rounded-full font-bold total-number" :class="`${tabPanelName === 'previousProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="previous_products">{{ previous_products.length }}</span>
                            </div>

                            <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('allProducts')">
                                <h6 class="font-medium lg:text-xs xl:text-sm 2xl:text-md" :class="`${tabPanelName === 'allProducts' ? 'text-primary-2' : 'text-gray-500'}`">All Products</h6>
                                <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'"  class="rounded-full font-bold total-number" :class="`${tabPanelName === 'allProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="all_products">{{ all_products.length }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <input placeholder="Search Product Here..." type="text" class="products-box-shadow mb-2 border border-solid border-gray-300 focus:border-indigo-300 px-8 py-2 rounded-3xl outline-none w-full flex-0 h-10 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" data-lucide="search" class="lucide lucide-search stroke-1.5 absolute left-3 top-4 text-gray-500"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>

                    <!-- START OF ONQUEUE PRODUCTS -->
                    <OnqueueProducts
                        v-if="tabPanelName === 'onQueueProducts' && on_queue_products"
                        :on_queue_products="on_queue_products"
                        :current_product="current_product"
                        @setActiveProduct="setActiveProduct"
                        @refreshOnQueueProducts="refreshOnQueueProducts"/>
                    <!-- START OF ONQUEUE PRODUCTS -->


                    <!-- START OF PREVIOUS -->
                    <PreviousProducts
                        v-if="tabPanelName === 'previousProducts' && previous_products"
                        :previous_products="previous_products"
                        :current_product="current_product"/>
                    <!-- START OF PREVIOUS -->


                    <!-- START OF ALL PRODUCTS -->
                    <AllProducts
                        v-if="tabPanelName === 'allProducts' && all_products"
                        :all_products="all_products"
                        :current_product="current_product"
                        :isProductsLoading="isProductsLoading"
                    />
                    <!-- START OF ALL PRODUCTS -->

                    <!-- START OF LOADER -->
                    <Preloader class="mb-24" v-if="isProductsLoading" />
                    <!-- END OF LOADER -->
                <!-- END OF PRODUCTS -->

                <!-- START OF PARTICIPANTS -->
                <participants></participants>
                <!-- END OF PARTICIPANTS -->
            </div>
            <!-- END OF FIRST COLUMN -->

            <!-- START OF SECOND COLUMN -->
            <div class="col-span-12 lg:col-span-6 p-4 bg-white">
                <!-- START STREAMING -->
                <stream
                    v-if="event"
                    :event="event"
                    :currentTab="currentTab"
                    @change-tab="changeCurrentTab"
                />
                <!-- END STREAMING -->

                <!-- START OF LOADER -->
                <Preloader class="mb-24 mt-24" v-if="isEventLoading && !event" />
                <!-- END OF LOADER -->

                <!-- START OF CURRENT PRODUCT -->
                <div class="border p-4 rounded-lg" v-if="current_product && currentTab == 'current product'">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <div class="flex items-center gap-2 w-2/3">
                            <span class="w-24 lg:w-36 text-center font-semibold bg-theme-6 text-xs lg:text-sm  text-white  py-0.5 px-1.5 lg:py-0.5 rounded-lg now-selling-text">Now Selling</span>

                            <span
                                class="w-24 lg:w-full flex-1 font-semibold text-xs lg:text-sm  text-red  py-0.5 px-1.5 lg:py-0.5 rounded-lg now-selling-text"
                                v-if="current_product && current_product.items && current_product.items.length && current_product.items[0].quantity > 0">{{ current_product.items[0].quantity }} item(s) left in stock</span>
                            <span
                                v-if="current_product && current_product.items && current_product.items.length && current_product.items[0].quantity == 0"
                                class="flex items-center justify-center font-semibold gap-1.5 bg-theme-33 text-xs lg:text-sm text-white py-0.5 px-1.5 lg:py-0.5 rounded-lg w-32 lg:w-40">
                                <span>This Item was Sold</span>
                            </span>
                        </div>

                        <!-- <div class="flex items-center justify-end gap-2 w-1/3">
                            <input type="checkbox" class="show-code form-check-switch">

                            <label class="text-xs lg:text-sm">
                                Auto Next
                            </label>
                        </div> -->
                    </div>



                    <div class="grid grid-cols-12 lg:gap-3 xl:gap-2">
                        <img class="col-span-3 h-32" :src="current_product.banner" alt="">

                        <div class="col-span-9">
                            <div class="flex flex-col gap-2" >
                                <h6 class="text-black font-semibold md:text-sm lg:text-md col-span-12">{{ current_product.name }}</h6>
                            </div>

                            <div class="flex flex-col gap-2 col-span-12 mb-2">
                                <div class="flex gap-2">
                                    <div class=" text-theme-6 font-semibold text-sm 2xl:text-base">{{ current_product && parseFloat(current_product.suggested_retail_price) < parseFloat(current_product.unit_price) ? current_product.suggested_retail_price : current_product.unit_price | moneyFormat }}</div>

                                    <div :style="'font-size: 12px'" class="relative text-theme-31 font-semibold current-product-discounted-price"
                                        v-if="current_product && parseFloat(current_product.suggested_retail_price) < parseFloat(current_product.unit_price)">
                                        {{ current_product.unit_price | moneyFormat }}
                                        <span class="absolute top-2 left-0 right-0 h-0.5 bg-theme-31 bottom-0"></span>
                                    </div>
                                </div>

                                <p class="italic text-xs lg:text-sm text-gray-600" v-html="isViewFullDetails ? current_product.description :  limitProductDescription(current_product.description, 360)">
                                   <!-- {{ isViewFullDetails ? current_product.description :  limitProductDescription(current_product.description, 360) }} -->
                                </p>
                            </div>
                        </div>

                        <span @click="viewFullDetails" class="cursor-pointer col-span-12 italic text-center text-primary-9 underline lg:text-xs xl:text-sm font-normal mb-4">{{ isViewFullDetails ? "Hide Full Details" : "Show Full Details" }}</span>


                        <div class="grid grid-cols-12 col-span-12 flex justify-between items-center">
                            <button @click.prevent="previous()" class="flex items-center justify-center gap-1 border text-gray-600 px-2 pr-2.5 py-1 lg:px-2 lg:py-2 rounded-lg col-span-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-left" data-lucide="arrow-left" class="lucide lucide-arrow-left stroke-1.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                <span class="text-xs prev-product-text">Previous Product</span>
                            </button>

                            <button @click.prevent="next()" class="flex items-center justify-center gap-1 border text-gray-600 px-0.5 py-1 lg:px-2 lg:py-2 rounded-lg col-end-13 col-span-3 ">
                                <span class="text-xs next-product-text">Next Product</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right" class="lucide lucide-arrow-right stroke-1.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- START OF LOADER -->
                <Preloader class="mb-24 mt-56" v-if="isProductsLoading && !current_product" />
                <!-- END OF LOADER -->
                <!-- END OF CURRENT PRODUCT -->
            </div>
            <!-- END OF SECOND COLUMN -->

            <!-- START OF THIRD COLUMN -->
            <div class="col-span-3 bg-white pt-4 pr-4 pb-4  hidden lg:block">
                <!-- START OF MESSAGES -->
                <Messages
                    :user="user"
                    :username="username"/>
                <!-- END OF MESSAGES -->

                <!-- START OF NOTIFICATIONS -->
                <notifications
                    :user="user"
                    :username="username"/>
                <!-- END OF NOTIFICATIONS -->
            </div>
            <!-- END OF THIRD COLUMN -->
        </div>
        <create @refreshProducts="refreshProducts"></create>


        <!-- START OF MOBILE TOGGABLE CONTENT -->
        <div :class="{ 'translate-y-full': !isOpenProducts }" class="bg-white fixed overflow-auto left-0 bottom-0 right-0 w-full h-3/4 transform  transition-transform duration-300 top-box-shadow">
            <div class="flex justify-end pr-2 pt-2">
                <div>
                    <button @click="toggleProducts" class="text-lg"><i class="fa-sharp fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <div class="px-12">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base lg:text-lg xl:text-xl font-semibold text-black">Products</h2>
                    <a href="javascript:;" data-toggle="modal" data-target="#add-posting" class="p-1 bg-primary-2 rounded-full text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" data-lucide="plus" class="lucide lucide-plus stroke-1.5 mx-auto block"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </a>
                </div>

                <div class="mb-2">
                    <div class="flex items-center justify-between gap-1">
                        <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('onQueueProducts')">
                            <h6 class="font-medium text-xs xl:text-sm 2xl:text-base" :class="`${tabPanelName === 'onQueueProducts' ? 'text-primary-2' : 'text-gray-500'}`">On Queue</h6>
                            <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'" class=" rounded-full font-bold total-number" :class="`${tabPanelName === 'onQueueProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="on_queue_products">{{ on_queue_products.length }}</span>
                        </div>

                        <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('previousProducts')">
                            <h6 class="font-medium text-xs xl:text-sm 2xl:text-base" :class="`${tabPanelName === 'previousProducts' ? 'text-primary-2' : 'text-gray-500'}`">Previous</h6>
                            <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'"  class="rounded-full font-bold total-number" :class="`${tabPanelName === 'previousProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="previous_products">{{ previous_products.length }}</span>
                        </div>

                        <div class="flex items-center gap-1 cursor-pointer" @click="changeTab('allProducts')">
                            <h6 class="font-medium text-xs xl:text-sm 2xl:text-base" :class="`${tabPanelName === 'allProducts' ? 'text-primary-2' : 'text-gray-500'}`">All Products</h6>
                            <span :style="'padding: 1.5px 4.5px 0.5px 4px; font-size: 7.5px;'"  class="rounded-full font-bold total-number" :class="`${tabPanelName === 'allProducts' ? 'bg-theme-21 text-primary-8' : 'bg-gray-300 text-gray-500'}`" v-if="all_products">{{ all_products.length }}</span>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <input placeholder="Search Product Here..." type="text" class="products-box-shadow mb-2 border border-solid border-gray-300 focus:border-indigo-300 px-8 py-2 rounded-3xl outline-none w-full flex-0 h-10 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" data-lucide="search" class="lucide lucide-search stroke-1.5 absolute left-3 top-4 text-gray-500"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>

                <!-- START OF ONQUEUE PRODUCTS -->
                <OnqueueProducts
                    v-if="tabPanelName === 'onQueueProducts' && on_queue_products"
                    :on_queue_products="on_queue_products"
                    :current_product="current_product"
                    @setActiveProduct="setActiveProduct"
                    @refreshOnQueueProducts="refreshOnQueueProducts"/>
                <!-- START OF ONQUEUE PRODUCTS -->


                <!-- START OF PREVIOUS -->
                <PreviousProducts
                    v-if="tabPanelName === 'previousProducts' && previous_products"
                    :previous_products="previous_products"
                    :current_product="current_product"/>
                <!-- START OF PREVIOUS -->


                <!-- START OF ALL PRODUCTS -->
                <AllProducts
                    v-if="tabPanelName === 'allProducts' && all_products"
                    :all_products="all_products"
                    :current_product="current_product"
                    :isProductsLoading="isProductsLoading"
                />
                <!-- START OF ALL PRODUCTS -->

                <!-- START OF LOADER -->
                <Preloader class="mb-24" v-if="isProductsLoading && !current_product && !on_queue_products && !previous_products && !all_products" />
                <!-- END OF LOADER -->
            </div>
        </div>


        <div :class="{ 'translate-y-full': !isOpenParticipants }" class="bg-white fixed overflow-auto left-0 bottom-0 right-0 w-full h-3/4 transform  transition-transform duration-300 top-box-shadow">
            <div class="flex justify-end pr-2 pt-2">
                <div>
                    <button @click="toggleParticipants" class="text-lg"><i class="fa-sharp fa-solid fa-xmark"></i></button>
                </div>
             </div>

             <div class="px-12">
                <participants></participants>
             </div>
        </div>


        <div :class="{ 'translate-y-full': !isOpenMessages }" class="bg-white fixed overflow-auto left-0 bottom-0 right-0 w-full h-3/4 transform  transition-transform duration-300 top-box-shadow">
            <div class="flex justify-end pr-2 pt-2">
                <div>
                    <button @click="toggleMessages" class="text-lg"><i class="fa-sharp fa-solid fa-xmark"></i></button>
                </div>
             </div>

             <div class="px-12">
                <messages
                    :user="user"
                    :username="username"/>
            </div>
        </div>

        <div :class="{ 'translate-y-full': !isOpenNotifications }" class="bg-white fixed overflow-auto left-0 bottom-0 right-0 w-full h-3/4 transform  transition-transform duration-300 top-box-shadow">
            <div class="flex justify-end pr-2 pt-2">
                <div>
                    <button @click="toggleNotifications" class="text-lg"><i class="fa-sharp fa-solid fa-xmark"></i></button>
                </div>
             </div>

             <div class="px-12">
                <notifications
                    :user="user"
                    :username="username"/>
            </div>
        </div>

        <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white mobile-toggler-box-shadow">
            <div class="grid grid-cols-4">
                <div class="p-4 text-center cursor-pointer border-r" :class="{ 'bg-gray-300': isOpenProducts }" @click="toggleProducts">
                    <button class="outline-none">PRODUCTS</button>
                </div>

                <div class="p-4 text-center cursor-pointer border-r" :class="{ 'bg-gray-300': isOpenParticipants }"  @click="toggleParticipants">
                    <button class="outline-none">PARTICIPANTS</button>
                </div>

                <div class="p-4 text-center cursor-pointer border-r" :class="{ 'bg-gray-300': isOpenMessages }" @click="toggleMessages">
                    <button class="outline-none">MESSAGES</button>
                </div>

                 <div class="p-4 text-center cursor-pointer" :class="{ 'bg-gray-300': isOpenNotifications }" @click="toggleNotifications">
                    <button class="outline-none">NOTIFICATIONS</button>
                </div>
            </div>
        </div>
        <!-- END OF MOBILE TOGGABLE CONTENT -->
    </fullscreen>
</template>

<script>
import Stream from './stream';
import Participants from './participants';
import Messages from './messages';
import Notifications from './notifications';
import OnqueueProducts from "./tab-components/on-queue-products.vue";
import PreviousProducts from "./tab-components/previous-products.vue";
import AllProducts from "./tab-components/all-products.vue";
import Preloader from "./preloader.vue"
import Create from "../event-postings/create";
import LiveSelling from '../../Core/LiveSelling'
export default {
    name: 'live-selling-controller',
    components: {
    Stream,
    OnqueueProducts,
    PreviousProducts,
    AllProducts,
    Participants,
    Messages,
    Preloader,
    Create,
    Messages,
    Notifications
},
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
    data() {
        return {
            event: null,
            live_selling: new LiveSelling(this.$route.params.id),
            fullscreen: false,
            tabPanelName: "onQueueProducts",
            products: null,
            on_queue_products: null,
            previous_products: null,
            all_products: null,
            current_product : null,
            isProductsLoading: false,
            isEventLoading: false,
            isViewFullDetails: false,
            isOpenProducts: false,
            isOpenParticipants: false,
            isOpenMessages: false,
            isOpenNotifications: false,
            currentTab: "current product",
            current_product_index: 0
        }
    },

    watch: {
        'live_selling.posting_id': {
            handler() {
                if(this.live_selling.posting_id) {
                    if(!this.current_product || (this.current_product && (this.current_product.posting_id != this.live_selling.posting_id)))
					    this.getCurrentProduct()
                }
            },
            immediate: true
        },
        'products' : {
            handler() {
                if(this.products && this.products.length) {

                    if(!this.current_product)
				        this.getCurrentProduct()
                }

            },
            immediate: true,
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
    created() {
        this.getEvent()
        this.getProducts()
        this.setTabId()

        this.current_product_index = localStorage.getItem('current-product-index') ? parseInt(localStorage.getItem('current-product-index')) : 0

        app.$on('updateQuantity', (product) => {
            if(this.current_product && this.current_product.items.length)
                for(const item of this.current_product.items) {
                    if(item.id == product.posting_item_id)
                        item.quantity = product.quantity
                }

            for(const listedProduct of this.products) {
                if(listedProduct.posting_id == product.posting_id){

                    if(listedProduct && listedProduct.items.length){

                        for(const item of listedProduct.items) {
                            if(item.id == product.posting_item_id)
                                item.quantity = product.quantity
                        }

                    }

                }
            }
        })
    },

    methods: {
        previous() {
         if(this.current_product_index > 0){
            this.current_product_index--
            window.localStorage.setItem('current-product-index',  parseInt(this.current_product_index))
            this.current_product = this.products[this.current_product_index]
            this.$emit('setActiveProduct', this.current_product)
         }
        },

        next() {
         if(this.current_product_index < this.products.length - 1 ){
            this.current_product_index++
            window.localStorage.setItem('current-product-index',  parseInt(this.current_product_index))
            this.current_product = this.products[this.current_product_index]
            this.$emit('setActiveProduct', this.current_product)
         }
        },

        changeCurrentTab(tab) {
            this.currentTab = tab;
        },

        toggleProducts() {
            this.isOpenProducts = !this.isOpenProducts;
            this.isOpenParticipants = false;
            this.isOpenMessages = false;
            this.isOpenNotifications = false;
        },

        toggleParticipants() {
            this.isOpenParticipants = !this.isOpenParticipants;
            this.isOpenProducts = false;
            this.isOpenMessages = false;
            this.isOpenNotifications = false;
        },

        toggleMessages() {
            this.isOpenMessages = !this.isOpenMessages;
            this.isOpenParticipants = false;
            this.isOpenProducts = false;
            this.isOpenNotifications = false;
        },

        toggleNotifications() {
            this.isOpenNotifications = !this.isOpenNotifications;
            this.isOpenMessages = false;
            this.isOpenParticipants = false;
            this.isOpenProducts = false;
        },

        viewFullDetails() {
            this.isViewFullDetails = !this.isViewFullDetails
        },

        limitProductDescription(text, limit) {
            const limitedText = text.slice(0, limit).trim();

            return limitedText + "...";
        },

        getProducts() {
            this.isProductsLoading = true;
		    	axios.get(`/events/${this.$route.params.id}/postings`)
			.then(({data})=>{
                this.isProductsLoading = false;
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

			}).catch(e => {
                this.isProductsLoading = false;
                console.log("ERROR: ", e)
            })
		},

        refreshOnQueueProducts(products) {
            this.on_queue_products = products
            this.getCurrentProduct()
            this.publishRefreshListing()
        },

        refreshProducts() {
            this.getProducts()
            this.publishRefreshListing()
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
        setActiveProduct(product) {
            this.current_product = product
            // this.bid.setActiveLot(this.current_lot.posting_id)
        },
        changeTab(panelName) {
            this.tabPanelName = panelName;
        },

        getEvent() {
                this.isEventLoading = true;
                axios.get('/events/' + this.$route.params.id)
                .then(({data}) => {
                    this.isEventLoading = false;
                    this.event = data
                })
                .catch((error) => {
                    this.isEventLoading = false;
                    console.log(error)
                })
        },
        setTabId() {

            window.addEventListener("beforeunload",  (e) => {
                return null
            })

            window.addEventListener("load",  (e) => {
                if (!window.sessionStorage.tabId)
                    window.sessionStorage.tabId = Date.now()
                return null
            })
        },

        toggle () {
            this.fullscreen = !this.fullscreen
        },

        publishRefreshListing() {
            this.live_selling.refreshListing()
        },

        scrollToLiveSellingContainer() {
            const divElement = this.$refs.liveSellingContainer;
            const topPosition = divElement.offsetTop;

            // Scroll to the top area of the div
            window.scrollTo({ top: topPosition, behavior: 'smooth' });
        }
    },
    beforeMount() {
        // sessionStorage.setItem('isLiveSellingControllerRoute', 'true');
    },
    mounted() {
        this.scrollToLiveSellingContainer()
    },
    beforeDestroy() {
        // sessionStorage.removeItem('isLiveSellingControllerRoute');
    }
}

</script>
<style scoped>
.box-shadow {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
}

.top-box-shadow {
  box-shadow: 0px -5px 10px rgba(0, 0, 0, 0.2);
}

.mobile-toggler-box-shadow {
  box-shadow: 0px -1px 10px rgba(0, 0, 0, 0.2);
}

.page-builder-sidebar {
    width: 300px; /* Adjust the width as needed */
    padding: 1rem;
    z-index: 99999999999;
  }


@media (min-width: 768px) {
    /* START OF PRODUCTS */
    .total-number {
        font-size: 11px !important;
        padding: 2px 5.5px 1px 5px !important;
    }
    /* END OF PRODUCTS */

}


@media (min-width: 1280px) {
    /* START OF PRODUCTS */
    .total-number {
        font-size: 11px !important;
        padding: 2px 5.5px 1px 5px !important;
    }
    /* END OF PRODUCTS */

}

@media (min-width: 1536px) {
    /* START OF PRODUCTS */
    .current-product-discounted-price {
        font-size: 14px !important;
    }
    /* END OF PRODUCTS */
}
</style>


