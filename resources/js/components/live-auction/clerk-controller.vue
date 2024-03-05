<template>
	<div class="w-full">
        <div class="flex justify-between">
            <h1 class="text-xl my-5">{{ auction.auction_number }}</h1>
            <div class="flex gap-x-3 mt-3">
                <button
                    v-if="stream.isStreaming() && !stream.isMuted()"
                    @click.prevent="muteMic()"
                    class="rounded px-4 bg-theme-6 text-white flex items-center px-4 h-10 justify-center hover:shadow-md  transition-all ">
                    <i class="fa-regular fa-microphone-slash mr-2"></i>
                    Mute Mic
                </button>
                <button
                    v-if="stream.isStreaming() &&  stream.isMuted()"
                    @click.prevent="unmuteMic()"
                    class="rounded px-4 bg-theme-1 text-white flex items-center px-4 h-10 justify-center hover:shadow-md  transition-all ">
                    <i class="fa-regular fa-microphone mr-2"></i>
                    Unmute Mic
                </button>

                <router-link :to="'/live-auction/' + $route.params.id +'/stream-settings'" class="bg-white border border-gray-300 px-4 h-10 flex items-center rounded hover:shadow-md  transition-all ">
                    <i class="fa-sharp fa-light fa-signal-stream mr-2"></i>
                    Go To Stream Controller
                </router-link>

                <button 
                    @click.prevent="publishAuction()"
                    v-if="!auction.published_date"
                    class="bg-white border border-gray-300 px-4 h-10 flex items-center rounded hover:shadow-md  transition-all">
                    <i class="fa-light fa-up mr-2"></i>
                    Published Auction
                </button>

                <button 
                    @click.prevent="unpublishAuction()"
                    v-if="auction.published_date"
                    class="bg-white border border-gray-300 px-4 h-10 flex items-center rounded hover:shadow-md  transition-all">
                    <i class="fa-light fa-down mr-2"></i>
                    Unpublished Auction
                </button>

                <button 
                    @click.prevent="refreshAuctionCache()"
                    v-if="auction.published_date"
                    class="bg-white border border-gray-300 px-4 h-10 flex items-center rounded hover:shadow-md  transition-all">
                    <i class="fa-light fa-rotate-right mr-2"></i>
                    Refresh Auction
                </button>

                <div class="dropdown">
                    <div class="dropdown-toggle flex w-10 h-10 bg-white rounded hover:shadow-md transition-all border border-gray-300 items-center justify-center cursor-pointer" role="button">
                        <i class="fa-light fa-gear "></i>
                    </div>
                    <div class="dropdown-menu w-72">
                        <div class="dropdown-menu__content box bg-white-26 dark:bg-dark-6 p-4 flex flex-col">
                            <h3 class="border-b border-dashed border-gray-300 font-semibold mb-2 pb-2">Clerk Controller Settings</h3>
                            <div class="flex flex-col gap-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="w-3/5 leading-relaxed">Hide Shortkey Button Label</span>
                                    <div class="w-2/5 flex items-center justify-end">
                                        <span class="font-medium text-gray-700 mb-1 text-black" v-if="show_shortcut_key">On</span>
                                        <span class="font-medium text-gray-700 mb-1 text-black" v-if="!show_shortcut_key">Off</span>
                                        <label for="settings" class="cursor-pointer">
                                            <input type="checkbox"
                                                class="form-check-switch mr-0 ml-3"
                                                :checked="show_shortcut_key"
                                                @change="show_shortcut_key = !show_shortcut_key">
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="w-3/5 leading-relaxed">Hold Bid</span>
                                    <div class="w-2/5 flex items-center justify-end">
                                        <span class="font-medium text-gray-700 mb-1 text-black">Hold Bid</span>
                                        <label for="settings" class="cursor-pointer">
                                            <input type="checkbox"
                                            class="form-check-switch mr-0 ml-3"
                                            :checked="bid.hold_bid"
                                            @change="toggleHoldBid()">
                                        </label>

                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="w-3/5 leading-relaxed">Bid Acceptance</span>
                                    <div class="w-2/5 flex items-center justify-end">
                                        <span class="font-medium text-gray-700 mb-1 text-black" v-if="!bid.auto_bid_acceptance">Manual</span>
                                        <span class="font-medium text-gray-700 mb-1 text-black" v-if="bid.auto_bid_acceptance">Auto</span>
                                        <label for="settings" class="cursor-pointer">
                                            <input type="checkbox"
                                                class="form-check-switch mr-0 ml-3"
                                                :checked="bid.auto_bid_acceptance"
                                                @change="toggleBidAcceptance()">
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex items-center justify-between mb-4">
            <div class="flex gap-x-2 items-center">
                <button class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-pause fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Pause</span>
                </button>

                <button
                    @click.prevent="publishLastCall"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-gavel fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Last Call</span>
                </button>

                <button
                    @click.prevent="publishWarning"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-triangle-exclamation fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Warning</span>
                </button>

                <a  v-if="bid.customer_id !== null && bid.customer_id === 0"
                    @click.prevent="setForApprovalStatus(1)"
                    href="javascript:;" data-toggle="modal" :data-target="'#set-bidder-number'"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-clipboard-list-check fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">For Approval</span>
                </a>

                <button
                    v-if="bid.customer_id !== null && bid.customer_id > 0"
                    @click.prevent="markAsForApproval()"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-clipboard-list-check fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">For Approval</span>
                </button>

                <a  v-if="bid.customer_id !== null && bid.customer_id == 0"
                    @click.prevent="setForApprovalStatus(0)"
                    href="javascript:;" data-toggle="modal" :data-target="'#set-bidder-number'"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-square-dollar fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Sell</span>
                </a>

                <button
                    v-if="bid.customer_id !== null && bid.customer_id > 0"
                    @click.prevent="markAsSold()"
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all">
                    <i class="fa-light fa-square-dollar fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Sell</span>
                </button>

                <button
                    class="border border-gray-400 h-10 w-32 rounded flex items-center justify-center text-md text-gray-700 hover:bg-gray-300 bg-gray-200 hover:shadow-md transition-all"
                    @click.prevent="setNextLot()">
                    <i class="fa-light fa-arrow-right fa-lg text-gray-600 mr-2"></i>
                    <span class="text-normal">Pass</span>
                </button>


            </div>
            <span class="blink text-white py-1 rounded text-lg" v-if="stream.isStreaming()">
                <img alt="" class="w-24" src="/images/live-stream.png">
            </span>
        </div>

        <div class="w-full">
            <div class="grid grid-cols-12 gap-2">
                <div class="bg-white rounded shadow-sm col-span-4">
                    <div class="flex flex-col p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <h2>Current Lot</h2>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-700 mb-1 text-black">Hold Bid</span>
                                <label for="settings" class="cursor-pointer">
                                    <input type="checkbox"
                                        class="form-check-switch mr-0 ml-3"
                                        id="hold-bid"
                                        :checked="bid.hold_bid"
                                        @change="toggleHoldBid()">
                                </label>
                            </div>
                        </div>

                        <!-- Loading icon -->
                        <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center" v-if="!current_lot">
                            <svg width="20" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="rgb(30, 41, 59)" class="w-8 h-8">
                                <rect y="10" width="15" height="120" rx="6">
                                    <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="30" y="10" width="15" height="120" rx="6">
                                    <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="60" width="15" height="140" rx="6">
                                    <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="90" y="10" width="15" height="120" rx="6">
                                    <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="120" y="10" width="15" height="120" rx="6">
                                    <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                                    <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
                                </rect>
                            </svg>
                            <div class="text-center text-xs mt-2">Loading ...</div>
                        </div>

                        <div class="flex p-4 bg-gray-100 border border-gray-200 rounded" v-if="current_lot">
                            <div class="flex items-center justify-center w-40 h-28 bg-gray-300 mr-4">
                                <img :src="current_lot.banner" class="block w-auto max-w-full max-h-28" alt="">
                            </div>
                            <div class="flex flex-col gap-y-1">
                                <span class="italic">Lot #{{ current_lot.lot_number }}</span>
                                <h3 class="font-semibold">{{ current_lot.name }}</h3>
                                <div class="flex gap-x-2">
                                    <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs">
                                        <i class="fa-solid fa-gauge-simple-high mr-1"></i>{{ getAttribute('Odometer') && getAttribute('Odometer').value ? getAttribute('Odometer').value : 'N/A' }}
                                    </span>
                                    <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs uppercase">
                                        <i class="fa-solid fa-gas-pump mr-1"></i>
                                        {{ getAttribute('Fuel Type') && getAttribute('Fuel Type').value ? getAttribute('Fuel Type').value : 'N/A' }}
                                    </span>
                                    <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs uppercase">
                                        {{ getAttribute('Transmission') && getAttribute('Transmission').value ? getAttribute('Transmission').value : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col p-4">
                        <div
                            class="flex p-4 bg-gray-100 border border-gray-200 rounded"
                            :class="{ 'current-bid-alert blink' : online_bid }"
                        >
                            <div class="flex flex-col items-center w-full">
                                <span>Current Bid</span>
                                <span class="text-lg font-semibold">{{ current_bid }}</span>

                            </div>
                        </div>
                        <span class="font-medium text-xs mt-2">Set Asking Bid</span>
                        <div class="flex mt-2">
                            <div class="w-12 bg-gray py-3 rounded-tl rounded-bl border-gray-200 bg-gray-100 font-semibold text-gray-700 text-center border-t border-b border-l">₱</div>
                            <input
                                class="w-full flex-1 border border-gray-200 py-3 text-md text-center placeholder-gray-600 text-gray-800"
                                placeholder="Asking Bid"
                                type="text"
                                v-model="asking_temp"
                            >
                            <div class="w-12 bg-gray py-3 rounded-tr rounded-br border-gray-200 bg-gray-100 font-bold text-gray-700 text-center border-t border-b border-r">.00</div>
                        </div>
                        <button
                            class="btn btn-primary py-3 mt-2"
                            @click="setManualAskingBid()"
                        >
                            Set Asking Bid
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded shadow-sm col-span-4">
                    <div class="flex flex-col p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="font-medium text-gray-700 mb-1 text-black">Asking Price</h2>
                            <button
                                v-if="(bid.current_bid + (current_increment * current_increment_multiplier)) < bid.asking_bid"
                                @click.prevent="resetAskingBid()"
                                class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded hover:shadow-md transition-all cursor-pointer hover:bg-gray-100">
                                <i class="fa-light fa-xmark fa-lg"></i>
                            </button>
                        </div>
                        <span class="text-2xl text-primary font-bold text-center w-full mt-1">{{ bid.asking_bid | moneyFormat }}</span>
                    </div>
                    <div class="flex flex-col p-4">
                        <div class="flex gap-x-2">
                            <div
                              class="flex flex-col items-center w-1/3"
                              :key="index"
                              v-for="(count, index) in 3">
                                <button
                                    class="flex flex-col items-center w-full py-4 bg-gray-500 hover:shadow-md rounded text-white"
                                    v-hotkey="['alt+'+(floor_bid_keys[index]), 'alt+shift+'+(floor_bid_keys[index]), 'ctrl+'+(floor_bid_keys[index])]"
                                    @click="setBid( bid.current_bid + ((count+1) * current_increment * current_increment_multiplier) )"
                                >
                                    <span class="mb-1">Set</span>
                                    <span class="text-md font-semibold italic">{{ bid.current_bid + ((count+1) * current_increment * current_increment_multiplier) | moneyFormat }}</span>
                                </button>
                                <div class="text-xs italic mt-1 text-gray-700" v-if="show_shortcut_key">Alt + <span class="uppercase">{{ floor_bid_keys[index]}} </span></div>
                            </div>
                        </div>
                        <div class="flex flex-col mt-4">
                            <div class="flex p-4 bg-gray-100 border border-gray-200 rounded justify-between items-center mb-3">
                                <span>Bid Acceptance:</span>
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-700 mb-1 text-black" v-if="!bid.auto_bid_acceptance">Manual</span>
                                    <span class="font-medium text-gray-700 mb-1 text-black" v-if="bid.auto_bid_acceptance">Auto</span>
                                    <label for="settings" class="cursor-pointer">
                                        <input type="checkbox"
                                            class="form-check-switch mr-0 ml-3"
                                            id="hold-bid"
                                            :checked="bid.auto_bid_acceptance"
                                            @change="toggleBidAcceptance()">
                                    </label>
                                </div>
                            </div>
                            <span class="font-medium text-xs mb-3">Set Manual Bid</span>
                            <div class="flex">
                                <div class="w-12 bg-gray py-3 rounded-tl rounded-bl border-gray-200 bg-gray-100 font-semibold text-gray-700 text-center border-t border-b border-l">
                                    ₱
                                </div>
                                <input
                                    class="w-full flex-1 border border-gray-200 py-3 text-md text-center placeholder-italic placeholder-gray-600 text-gray-800"
                                    placeholder="Manual Bid"
                                    type="text"
                                    v-model="manual_bid"
                                >
                                <div class="w-12 bg-gray py-3 rounded-tr rounded-br border-gray-200 bg-gray-100 font-bold text-gray-700 text-center border-t border-b border-r">.00</div>
                            </div>

                        </div>
                        <div class="flex gap-x-2 mt-2">
                            <button
                                @click.prevent="setManualBid()"
                                class="btn w-full btn-primary py-3">Set Floor Bid</button>
                            <button
                                @click.prevent="resetManualdBid()"
                                class="btn w-full border-gray-300 hover:bg-gray-100 bg-transparent transition-all py-3">Reset Floor Bid</button>
                        </div>
                        <div class="flex text-center justify-center p-4 rounded font-semibold mt-4" style="border: 1px solid #E11D48; color: #E11D48;" v-if="bid.hold_bid">
                            <i class="fa-light fa-triangle-exclamation fa-2x mr-2"></i>
                            <span class="text-lg">Online Bid is currently on hold.</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded shadow-sm col-span-4">
                    <div class="flex flex-col p-4">
                        <h2 class="font-medium text-gray-700 mb-2 text-black">Current Bids</h2>
                        <div class="flex flex-col gap-y-2 overflow-y-scroll mt-2" style="height: 416px;">
                            <bid-history
                                :lot="current_lot"
                                :bid="bid"/>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded shadow-sm col-span-4">

                    <div class="flex flex-col p-4">
                        <h2 class="font-medium text-gray-700 mb-2 text-black">Lots</h2>
                        <div class="mt-4">
                            <tabs
                                :tabs="tabs"
                                :currentTab="currentTab"
                                :wrapper-class="'default-tabs'"
                                :tab-class="'default-tabs__item'"
                                :tab-active-class="'default-tabs__item_active'"
                                :line-class="'default-tabs__active-line'"
                                @onClick="handleClick"
                            />
                            <div>
                                <!-- class="content" -->
                                <!-- Loading icon -->
                                <preloader v-if="lots && !lots.length"/>
                                <div v-if="currentTab === 'ongoing_lots'">
                                    <div class="flex flex-col gap-y-2 overflow-y-scroll mt-2" style="height: 416px;" v-if="lots && lots.length">
                                        <div class="flex justify-center p-4 bg-gray-100 border border-gray-200 rounded cursor-pointer"  v-if="!ongoing_lots.length">
                                            <div class="h-full flex items-center">
                                                <div class="mx-auto text-center">
                                                    <div class="overflow-hidden mx-auto">
                                                        <img alt="No-Result" class="rounded-md w-36 h-36"
                                                            src="/images/no-result.png">
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="font-medium">No Current Ongoing Lots</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="ongoing_lots.length">
                                            <lots
                                            v-for="(lot, index) in ongoing_lots"
                                            :key="index"
                                            :index="index"
                                            :lot="lot"
                                            :tab="currentTab"
                                            @setActiveLot="setActiveLot"/>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="currentTab === 'previous_lots'">
                                    <div class="flex flex-col gap-y-2 overflow-y-scroll mt-2" style="height: 416px;" v-if="lots && lots.length">
                                        <div class="flex justify-center p-4 bg-gray-100 border border-gray-200 rounded cursor-pointer"  v-if="!previous_lots.length">
                                            <div class="h-full flex items-center">
                                                <div class="mx-auto text-center">
                                                    <div class="overflow-hidden mx-auto">
                                                        <img alt="No-Result" class="rounded-md w-36 h-36"
                                                            src="/images/no-result.png">
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="font-medium">No Current Previous Lots</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="previous_lots.length">
                                            <lots
                                            v-for="(lot, index) in previous_lots"
                                            :key="index"
                                            :index="index"
                                            :lot="lot"
                                            :tab="currentTab"
                                            @setActiveLot="resetLotInListing(lot)"/>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="currentTab === 'lots'">
                                    <div class="flex flex-col gap-y-2 overflow-y-scroll mt-2" style="height: 416px;" v-if="lots && lots.length">
                                        <div class="flex justify-center p-4 bg-gray-100 border border-gray-200 rounded cursor-pointer"  v-if="!lots.length">
                                            <div class="h-full flex items-center">
                                                <div class="mx-auto text-center">
                                                    <div class="overflow-hidden mx-auto">
                                                        <img alt="No Result" class="rounded-md w-36 h-36"
                                                            src="/images/no-result.png">
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="font-medium">No Current Lots</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div  v-if="lots.length">
                                        <lots
                                            v-for="(lot, index) in lots"
                                            :key="index"
                                            :index="index"
                                            :lot="lot"
                                            :tab="currentTab"
                                            @setActiveLot=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded shadow-sm col-span-8">
                    <div class="flex flex-col p-4">
                        <div class="flex justify-between items-center">
                            <h2 class="font-medium text-gray-700 text-black">Current Increment</h2>
                            <div class="flex flex-col items-center">
                                <span class="text-xl font-semibold text-black">{{ current_increment | moneyFormat }}</span>
                                <span class="text-xs">Multiplier: <span>{{ current_increment_multiplier }}x</span></span>
                            </div>
                            <div class="flex">
                                <span @click="current_increment = current_increment / 2" class="border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-600 font-normal flex items-center px-5 py-1 rounded cursor-pointer hover:shadow-md transition-all">Half</span>
                                <span class="text-lg w-16 text-center">1x</span>
                                <span @click="current_increment = current_increment * 2" class="border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-600 font-normal flex items-center px-5 py-1 rounded cursor-pointer hover:shadow-md transition-all">Double</span>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded flex gap-x-6 mt-4">
                            <div class="flex flex-col w-full flex-1">
                                <span class="text-gray-700 font-semibold py-2">Asking Price:</span>
                                <span class="text-gray-700 font-semibold py-2">Increment:</span>
                                <span>&nbsp;</span>
                            </div>
                            <div
                                class="flex flex-col w-full flex-1 items-center"
                                v-for="(count, index) in 6"
                                :key="index"
                            >
                                <span class="text-gray-700 py-2">{{ (bid.current_bid) + (current_increment * count) | moneyFormat }}</span>
                                <span class="text-gray-700 py-2">{{ current_increment * count | moneyFormat }}</span>
                                <div class="flex flex-col">
                                    <button
                                        v-hotkey="['alt+'+(index+1), 'alt+shift+'+(index+1), 'ctrl+'+(index+1)]"
                                        :id="'multiplier-'+index"
                                        class="bg-theme-1 text-white font-semibold flex px-5 py-1 rounded cursor-pointer hover:shadow-md transition-all justify-center"
                                        @click="setAskingBid(bid.current_bid + (current_increment * count), count)"
                                    >Set</button>
                                    <div class="text-xs italic mt-2 text-gray-700" v-if="show_shortcut_key">Alt + Shift + {{ index + 1}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex mt-2 gap-x-2">
                            <div class="flex flex-col p-4 border border-gray-200 rounded w-1/2">
                                <h2 class="font-medium text-gray-700 text-black">Set Manual Increment</h2>
                                <div class="flex mt-2">
                                    <div class="w-12 bg-gray py-3 rounded-tl rounded-bl border-gray-200 bg-gray-100 font-semibold text-gray-700 text-center border-t border-b border-l">₱</div>
                                    <input
                                        class="w-full flex-1 border border-gray-200 py-3 text-md text-center placeholder-gray-600 text-gray-800"
                                        placeholder="Increment"
                                        type="text"
                                        v-model="increment_temp"
                                    >
                                    <div class="w-12 bg-gray py-3 rounded-tr rounded-br border-gray-200 bg-gray-100 font-bold text-gray-700 text-center border-t border-b border-r">.00</div>
                                </div>
                                <div class="flex gap-x-2 mt-2">
                                    <button @click="setManualIncrement()" class="btn w-full btn-primary py-3">Set Increment</button>
                                    <button @click="resetIncrement()" class="btn w-full border-gray-300 hover:bg-gray-100 bg-transparent transition-all py-3">Reset Increment</button>
                                </div>
                            </div>
                            <div class="flex flex-col p-4 border border-gray-200 rounded w-1/2">
                                <div class="flex justify-between items-center mb-2">
                                    <h2 class="font-medium text-gray-700 text-black">Broadcast Message</h2>
                                    <span
                                        class="btn-primary text-white font-semibold flex items-center px-5 py-1 rounded cursor-pointer hover:shadow-md transition-all"
                                        @click.prevent="publishMessage()"
                                        >Send</span>
                                </div>
                                <textarea
                                    cols="30"
                                    rows="5"
                                    placeholder="Type your message here"
                                    class="p-3 rounded border border-gray-200 bg-gray-100"
                                    v-model="message"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <video class="hidden" id="stream-video" autoplay muted controls playsinline></video>
        <div class="box-status fixed bottom-0 right-0 bg-white border border-gray-200 rounded-tl rounded-tr px-8 py-4 font-semibold mr-12 flex gap-x-3 cursor-pointer" >
            <span>Status: 69 Lots, 35 Sold</span>
            <span>|</span>
            <span class="flex items-center">
                <i class="text-primary fa-solid fa-gavel mr-1"></i>
                65 Bidders,
                <i class="text-primary fa-solid fa-users ml-3 mr-1"></i>
                120 Viewers
            </span>
        </div>
        <vue-snotify></vue-snotify>

        <bidder-number v-if="auction && auction.auction_id" :auction="auction" :for_approval="for_approval" @initiateFunction="markAsSold"/>
	</div>
</template>
<style scoped>
.content {
    background-color: #fff !important;
    padding: 0px !important;
    margin-top: 12px !important;
}

.blink {
    animation: blinker .7s linear infinite;
}

@keyframes blinker {
    50% {
        opacity: 40%;
    }
}

</style>
<style >

    .default-tabs {
        padding: 4px 6px !important;
        display: flex !important;
        border: 1px dashed #CBD5E0 !important;
        border-radius: 6px !important;
    }

    .default-tabs__item {
        flex: 1 !important;
        padding: 8px !important;
        border-radius: 4px !important;
        background-color: #fff !important;
        color: #4a5568 !important;
    }

    .default-tabs__item_active {
        background-color: #3251B2 !important;
        color: #fff !important;
    }

    .default-tabs__active-line {
        display: none;
    }

    .current-bid-alert {
        border: 1px solid #b91c1c;
        background-color: #fef2f2
    }

    #hold-bid:checked {
        background-color: rgb(225 29 72) !important;
        border-color: rgb(225 29 72) !important;
    }

</style>
<script>
import bidderNumber from "./bidder-number";
import lots from './lots'
import bidHistory from './bid-history'
import Tabs from 'vue-tabs-with-active-line';
import Preloader from './preloader'
import moment from 'moment';
import BidderNumber from './bidder-number.vue';
export default {
    name: 'clerk-controller',
    components: {
        lots,
        bidHistory,
        Tabs,
        Preloader,
        bidderNumber
    },
    props: {
        auction: {
			type: Object,
			required: true
		},
        bid: {
            type: Object
        },
        url: {
			type: String,
			default: null
		},
    },
    data() {
        return {
            asking_temp: 0,
            asking_bid: 0,
            manual_bid: 0,
            current_increment: 1000,
            current_increment_multiplier: 1,
            increment_temp: 0,
            current_active_lot_index: 0,
            tabs: [
                { title: 'Ongoing', value: 'ongoing_lots' },
                { title: 'Previous', value: 'previous_lots' },
                { title: 'All', value: 'lots' },
            ],
            currentTab: 'ongoing_lots',
            lots: [],
            current_lot: null,
            for_approval: false,
            message:null,
            ongoing_lots: [],
            previous_lots: [],
            online_bid: false,
            floor_bid_keys: ['q','w','e'],
            hold_bid: true,
            show_shortcut_key: true
        }
    },
    computed: {
        'stream'() {
            return this.$store.getters.stream
        },
        // 'current_lot'() {

        //     if(this.ongoing_lots.length && this.ongoing_lots[this.current_active_lot_index] && !this.bid.posting_id)
        //         return this.ongoing_lots[this.current_active_lot_index]

		// 	return this.lots.length ? this.lots[0] : null

		// },
        'attribute_data'() {
            if(this.current_lot)
                return Array.isArray(this.current_lot.attribute_data) ? this.current_lot.attribute_data : JSON.parse(this.current_lot.attribute_data)

            return null
        },
        'current_bid'() {
            return this.bid.current_bid ? this.$options.filters.moneyFormat(this.bid.current_bid) : 'No bid yet'
        },
        // 'ongoing_lots'() {
		// 	return this.lots.filter((lot) => {
		// 		return !lot.finalized_date
		// 	})
		// },
        // 'previous_lots'() {
		// 	return this.lots.filter((lot) => {
		// 		return lot.finalized_date
		// 	})
		// },
    },
    watch: {
        'bid.asking_bid':{
            handler() {
                this.asking_bid = this.bid.asking_bid || 0.00
            },
            immediate: true
        },
        'current_bid': {
            handler(){
                if(this.bid.current_bid == this.bid.asking_bid && this.bid.posting_id)
                    this.setAskingBid(this.bid.current_bid + (this.current_increment * this.current_increment_multiplier))
            },
            immediate:true
        },
        'bid.bid_increment':{
            handler() {
                this.current_increment = this.bid.bid_increment || 1000
            },
            immediate: true
        },
        'bid.bid_increment_multiplier':{
            handler() {
                this.current_increment_multiplier = this.bid.bid_increment_multiplier || 1
            },
            immediate: true
        },
        'bid.posting_id': {
            handler() {
                if(this.bid.posting_id) {
                    if(!this.current_lot || (this.current_lot && (this.current_lot.posting_id != this.bid.posting_id)))
					    this.getCurrentLot()

                    this.bid.getCurrentBidAndWinner()
                }
            },
            immediate: true
        },
        'bid.customer_id'() {
            if(this.bid.customer_id && this.bid.notify) {
                this.online_bid = true
                setTimeout(() => {
                    this.online_bid = false
                }, 4000)
            }
        },
        'lots' : {
            handler() {
                if(this.lots.length) {
                    this.updateLotTabs()
                    if(!this.current_lot)
				        this.getCurrentLot()
                    // if(this.bid.posting_id) {
                    //     this.current_active_lot_index = this.lots.findIndex((lot)=>{
                    //         return this.bid.posting_id == lot.posting_id
                    //     })

                    //     return
                    // }
                    // let lot = this.lots[this.current_active_lot_index]
                    // this.bid.setPostingId(lot.posting_id)
                }

            },
            immediate: true,
        },
        'current_lot' : {
            handler() {
                if(this.current_lot.posting_id != this.bid.posting_id){
                    this.bid.setActiveLot(this.current_lot.posting_id)
                    this.bid.setPostingId(this.current_lot.posting_id)
                    this.bid.getCurrentBidHistories()
                }
                // if(!this.ongoing_lots.length)
                //     this.currentTab = 'lots'
            }
        },
        'asking_bid': {
            handler() {
                this.manual_bid = this.asking_bid
            }
        }
    },
    created() {
        this.getLots()

    },

    methods: {
        getLots(){
            axios.get('/simulcast-auctions/' + this.$route.params.id + '/postings')
                .then(({data}) => {
                    this.lots = data
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        getCurrentLot() {
			if(this.bid.posting_id){
				if(!this.bid.posting) {
					this.current_lot = this.ongoing_lots.find((lot)=>{
                        return lot.posting_id == this.bid.posting_id
                    })
				} else {
					this.current_lot = this.bid.posting
				}

                if(this.current_lot)
				    return
			}

			this.current_lot = this.ongoing_lots.length ? this.ongoing_lots[0] : null
            // this.bid.setActiveLot(this.current_lot.posting_id)


		},

        setManualBid() {
            if(this.manual_bid > this.asking_bid)
                this.setAskingBid(this.manual_bid)
            this.bid.setBid(this.manual_bid)
        },
        setBid(amount) {
            if(amount > this.asking_bid)
                this.setAskingBid(amount)
            this.bid.setBid(amount)
        },
        resetManualdBid() {
            this.manual_bid = this.asking_bid
        },
        setManualAskingBid() {
            this.asking_bid = this.asking_temp
            this.bid.setAskingBid({
                asking_bid: this.asking_bid,
                bid_increment: this.current_increment,
            })
        },
        setAskingBid(amount, multiplier = null) {
            this.current_increment_multiplier = multiplier || this.current_increment_multiplier
            this.asking_bid = amount
            this.bid.setAskingBid({
                asking_bid: this.asking_bid,
                bid_increment: this.current_increment,
                bid_increment_multiplier: this.current_increment_multiplier,
            })
        },
        resetAskingBid() {
            this.setAskingBid(this.bid.current_bid + (this.current_increment * this.current_increment_multiplier))
        },
        setManualIncrement() {
            this.current_increment = this.increment_temp
        },
        resetIncrement() {
            this.current_increment = 1000
            this.increment_temp = 1000
        },

        muteMic() {
            this.$store.commit('muteMic')
        },
        unmuteMic() {
            this.$store.commit('unmuteMic')
        },
        getAttribute(column_name) {
            return this.attribute_data.find((attribute_data)=>{
                return attribute_data.column_name.toLowerCase() == column_name.toLowerCase()
            })
        },
        setNextLot() {
            this.$snotify.info('Please Wait', 'Processing...', {
                timeout: 3000,
                showProgressBar: false,
                closeOnClick: false,
                pauseOnHover: true
            })

            axios.post(`/simulcast-auctions/postings/${this.current_lot.posting_id}/finalized`,{})
                .then(()=>{
                    this.$snotify.success('Next Lot', 'Pass', {
                        timeout: 2000,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true
                    })

                })
                .catch((error)=>{
                    this.$snotify.error('Unable To go On Next Lot', 'Error', {
                        timeout: 2000,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true
                    })
                })

            this.updateLotInListing()

            setTimeout(()=>{
                if(this.ongoing_lots.length) {
                    this.current_lot = this.ongoing_lots[0]
                    return
                }
                this.current_lot = null
            }, 100)

        },
        setActiveLot(lot) {
            this.current_lot = lot
            // this.bid.setActiveLot(this.current_lot.posting_id)
        },
        handleClick(newTab) {
            this.currentTab = newTab;
        },
        setForApprovalStatus(status){
            this.for_approval = status
        },
        markAsSold(data = null) {
            let customer_type = null
            let customer_id = data ? data.customer_id : null
            let bidder_number_id = data ? data.bidder_number_id: null
            let for_approval = data ? data.for_approval: 0

            if(this.bid.customer_id !== null && this.bid.customer_id === 0)
                customer_type = 'Floor'

            if(this.bid.customer_id !== null && this.bid.customer_id > 0)
                customer_type = 'Online'

            if(!customer_type) return

            this.$modal.dialog({
				message: 'Are you sure you want to Sold this Lot?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
			})
			.then(confirmed => {
                this.$snotify.info('Please Wait', 'Processing...', {
                    timeout: 3000,
                    showProgressBar: false,
                    closeOnClick: false,
                    pauseOnHover: true
                })
                axios.post(`/simulcast-auctions/postings/${this.current_lot.posting_id}/mark-as-sold`, {
                    posting_id: this.current_lot.posting_id,
                    customer_type,
                    customer_id,
                    bidder_number_id,
                    for_approval
                }).then(({data}) => {
                    this.for_approval = false
                    this.$snotify.success('Lot Successfully Awarded', 'Awarded', {
                        timeout: 2000,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true
                    })
                }).catch((error) => {
                    this.for_approval = false
                    this.$snotify.error('Lot Not Successfully Awarded', 'Not Awarded', {
                        timeout: 2000,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true
                    })
                })
                this.updateLotInListing(for_approval)
                setTimeout(()=>{
                    if(this.ongoing_lots.length) {
                        this.current_lot = this.ongoing_lots[0]
                        return
                    }
                    this.current_lot = null
                }, 100)

			})
			.catch();

        },
        markAsForApproval() {
            let customer_type = null
            let customer_id = null
            let bidder_number_id = null
            let for_approval = 1
            if(this.bid.customer_id !== null && this.bid.customer_id === 0)
                customer_type = 'Floor'

            if(this.bid.customer_id !== null && this.bid.customer_id > 0)
                customer_type = 'Online'

            if(!customer_type) return

            this.$modal.dialog({
                message: 'Are you sure you want to set this as For Approval?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                this.$snotify.info('Please Wait', 'Processing...', {
                    timeout: 3000,
                    showProgressBar: false,
                    closeOnClick: false,
                    pauseOnHover: true
                })
                axios.post(`/simulcast-auctions/postings/${this.current_lot.posting_id}/mark-as-sold`, {
                        posting_id: this.current_lot.posting_id,
                        customer_type,
                        customer_id,
                        bidder_number_id,
                        for_approval
                    })
                    .then(({data}) => {
                        this.for_approval = false
                        this.$snotify.success('Lot Successfully Awarded', 'Awarded', {
                            timeout: 2000,
                            showProgressBar: false,
                            closeOnClick: false,
                            pauseOnHover: true
                        })
                    })
                    .catch((error)=>{
                        this.for_approval = false
                        this.$snotify.error('Lot Not Successfully Awarded', 'Not Awarded', {
                            timeout: 2000,
                            showProgressBar: false,
                            closeOnClick: false,
                            pauseOnHover: true
                        })
                    })
                this.updateLotInListing(1)
                setTimeout(()=>{
                    if(this.ongoing_lots.length) {
                        this.current_lot = this.ongoing_lots[0]
                        return
                    }
                    this.current_lot = null
                }, 100)
            })
            .catch()
        },
        updateLotInListing(for_approval = 0) {
            if(this.bid.auto_bid_acceptance)
                this.toggleBidAcceptance()

            if(!this.bid.hold_bid)
                this.toggleHoldBid()

            for(let lot of this.lots) {
                if(lot.posting_id == this.current_lot.posting_id) {
                    lot.finalized_date = moment().format('YYYY-MM-DD HH:mm:ss')
                    lot.bid_amount = this.bid.current_bid || null
                    lot.for_approval = for_approval
                    this.bid.setPostingStatus(lot)
                    break
                }
            }
            this.updateLotTabs()
        },
        updateLotTabs() {
            this.ongoing_lots = this.lots.filter((lot) => {
                        return !lot.finalized_date
                    })

            this.previous_lots = this.lots.filter((lot) => {
                    return lot.finalized_date
                })
        },
        resetLotInListing(selected_lot) {
            this.$modal.dialog({
                message: 'Are you sure you want to set this lot as the active lot?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
            })
            .then(confirmed => {
                for(let lot of this.lots) {
                    if(lot.posting_id == selected_lot.posting_id) {
                        lot.finalized_date = null
                        this.current_lot = selected_lot
                        this.bid.setActiveLot(this.current_lot.posting_id)
                        this.currentTab = 'ongoing_lots'
                        break
                    }
                }
                this.updateLotTabs()
            })

        },
        publishMessage() {
            this.bid.publishMessage(this.message)
            this.message = null
        },
        publishLastCall() {
            this.bid.publishMessage('Last Call')
        },
        publishWarning() {
            this.bid.publishMessage('Last 5 Calls')
        },
        toggleHoldBid() {
            this.bid.setHoldBid()
        },
        toggleBidAcceptance() {
            this.bid.setBidAcceptance()
        },
        publishAuction() {
            this.$modal.dialog({
                message: 'Are you sure you want to Published this Auction?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Published'
            })
            .then(confirmed => {
                axios.patch(`/auctions/${this.auction.auction_id}/publish`)
                    .then(() => {
                        this.$modal.success({
                            message: 'You successfully Published this Auction',
                            title: 'Published'
                        });
                        setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch();
            }).catch();
        },
        unpublishAuction() {
            this.$modal.dialog({
                message: 'Are you sure you want to Unpublished this Auction?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Unpublished'
            })
            .then(confirmed => {
                axios.patch(`/auctions/${this.auction.auction_id}/unpublish`)
                    .then(() => {
                        this.$modal.success({
                            message: 'You successfully Unpublished this Auction',
                            title: 'Unpublished'
                        });
                        setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch();
            }).catch();
        },
        refreshAuctionCache() {
            this.$modal.dialog({
                message: 'Are you sure you want to Refreshed this Auction Cache?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Refresh'
            })
            .then(confirmed => {
                axios.patch(`/auctions/${this.auction.auction_id}/refresh`)
                    .then(() => {
                        this.$modal.success({
                            message: 'You successfully Refreshed this Auction Cache',
                            title: 'Refreshed'
                        });
                        setTimeout(() =>  window.location.reload(), 3000);
                    })
                    .catch();
            }).catch();
        },
    }
}
</script>
