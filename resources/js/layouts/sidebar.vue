<template>
    <div>
        <!-- BEGIN: Side Menu -->
        <nav class="side-nav" v-if="isOtherRoute">
            <a href="" class="intro-x flex items-center pl-5 pt-4">
                <img alt="hmr.ph Logo" class="w-6" src="images/logo.svg" />
                <span class="hidden xl:block text-white text-lg ml-3"
                    >HMR.ph &nbsp;<span class="font-medium">CMS</span>
                </span>
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                <li>
                    <router-link
                        v-can="'order.listings'"
                        :to="'/'"
                        :class="
                            $route.path == '/'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="home"></i>
                        </div>
                        <div class="side-menu__title text-sm">Dashboard</div>
                    </router-link>
                </li>

                <li>
                    <router-link
                        v-can="'access.auction'"
                        :to="'/auctions?tag=auction'"
                        :class="
                            $route.query.tag == 'auction'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="tv"></i>
                        </div>
                        <div class="side-menu__title text-sm">Auctions</div>
                    </router-link>
                </li>

                <li>
                    <router-link
                        v-can="'access.event'"
                        :to="'/events?tag=event'"
                        :class="
                            $route.query.tag == 'event'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="smartphone"></i>
                        </div>
                        <div class="side-menu__title text-sm">Events</div>
                    </router-link>
                </li>

                <li>
                    <router-link
                        v-can="'publish.posting'"
                        :to="'/postings?tag=posting'"
                        :class="
                            $route.query.tag == 'posting'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="monitor"></i>
                        </div>
                        <div class="side-menu__title text-sm">Postings</div>
                    </router-link>
                </li>

                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'orders'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="list" data-lucide="list" class="lucide lucide-list stroke-1.5 mx-auto block"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        </div>
                        <div class="side-menu__title">
                            <span class="text-sm">
                              Orders
                            </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="">
                        <li>
                            <router-link
                                v-can="'access.order'"
                                :to="'/orders?tag=orders'"
                                class="side-menu ml-0 xl:ml-6 relative"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0"  :class="$route.path.includes('/orders') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit2" data-lucide="edit2" class="lucide lucide-edit2 stroke-1.5 mx-auto block"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/orders') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/orders') ? 'text-white' : ''">
                                  Orders
                                </div>
                            </router-link>
                        </li>


                        <li>
                            <router-link
                                v-can="'access.order'"
                                :to="'/order-waybills?tag=orders'"
                                class="side-menu ml-0 xl:ml-6 relative"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/order-waybills') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trello" data-lucide="trello" class="lucide lucide-trello stroke-1.5 mx-auto block"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/order-waybills') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/order-waybills') ? 'text-white' : ''">Order Way Bills</div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <router-link
                        v-can="'access.section'"
                        :to="'/sections?tag=section'"
                        :class="
                            $route.query.tag == 'section'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="align-justify" data-lucide="align-justify" class="lucide lucide-align-justify stroke-1.5 mx-auto block"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        </div>
                        <div class="side-menu__title text-sm">Sections</div>
                    </router-link>
                </li>

                <li>
                    <router-link
                        v-can="'access.voucher'"
                        :to="'/vouchers?tag=voucher'"
                        :class="
                            $route.query.tag == 'voucher'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="percent"></i>
                        </div>
                        <div class="side-menu__title text-sm">Vouchers</div>
                    </router-link>
                </li>

                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'abandoned-carts'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="shopping-cart"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Abandoned Carts </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="">
                        <li>
                            <router-link
                                v-can="'list.cart'"
                                :to="'/abandoned-cart?tag=abandoned-carts'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/abandoned-cart' ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle stroke-1.5 mx-auto block"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/abandoned-cart' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/abandoned-cart' ? 'text-white' : ''">Abandoned Carts</div>
                            </router-link>
                        </li>

                        <li>
                            <router-link
                                v-can="'list.abandoned-cart-history'"
                                :to="'/abandoned-cart-history?tag=abandoned-carts'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/abandoned-cart-history' ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-square" data-lucide="x-square" class="lucide lucide-x-square stroke-1.5 mx-auto block"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/abandoned-cart-history' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/abandoned-cart-history' ? 'text-white' : ''">
                                    Abandoned Cart Histories
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'inquiries'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="file"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Inquiries </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.account-executive'">
                            <router-link
                                :to="'/account-executives?tag=inquiries'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/account-executives' ? 'text-white' : ''">
                                    <i data-feather="users" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/account-executives' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/account-executives' ? 'text-white' : ''">
                                    Account Executives
                                </div>
                            </router-link>
                        </li>

                        <li>
                            <router-link
                                v-can="'list.posting-inquiry'"
                                class="side-menu ml-0 xl:ml-6"
                                :to="'/posting-inquiries?tag=inquiries'"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/posting-inquiries' ? 'text-white' : ''">
                                    <i data-feather="message-square" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/posting-inquiries' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/posting-inquiries' ? 'text-white' : ''">
                                    Posting Inquiries
                                </div>
                            </router-link>
                        </li>

                        <li>
                            <router-link
                                v-can="'list.posting-inquiry'"
                                :to="'/store-inquiries?tag=inquiries'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/store-inquiries') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="home" data-lucide="home" class="lucide lucide-home stroke-1.5 mx-auto block"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/store-inquiries') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>


                                <div class="side-menu__title" :class="$route.path.includes('/store-inquiries') ? 'text-white' : ''">Store Inquiries</div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li v-can="'list.payable'">
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'accounting'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="folder"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Accounting </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.bank'">
                            <router-link
                                :to="'/banks?tag=accounting'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/banks') ? 'text-white' : ''">
                                  <i data-feather="pocket" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/banks') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/banks') ? 'text-white' : ''">Banks</div>
                            </router-link>
                        </li>
                        <li v-can="'list.cost'" >
                            <router-link
                                :to="'/costs?tag=accounting'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                  <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/costs') ? 'text-white' : ''">
                                    <i data-feather="paperclip" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/costs') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/costs') ? 'text-white' : ''">Costs</div>
                            </router-link>
                        </li>
                        <li v-can="'list.cost-type'" >
                            <router-link
                                :to="'/cost-types?tag=accounting'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                  <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/cost-types') ? 'text-white' : ''">
                                    <i data-feather="file-text" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/cost-types') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/cost-types') ? 'text-white' : ''">Cost Types</div>
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                :to="'/payables?tag=accounting'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/payables') ? 'text-white' : ''">
                                  <i data-feather="briefcase" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/payables') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/payables') ? 'text-white' : ''">Payables</div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li v-can="'access.affiliate-marketing'">
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'affiliate'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="percent"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Affiliate Marketing </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                      <!-- <li v-can="'list.bank'">
                            <router-link
                                :to="'/banks?tag=accounting'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                  <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0">
                                    <i data-feather="pocket" width="16" height="16"></i>
                                </div>
                                <div class="side-menu__title">Banks</div>
                            </router-link>
                        </li> -->
                        <li v-can="'list.affiliate-marketing'">
                            <router-link
                                :to="'/affiliate-marketing?tag=affiliate'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/affiliate-marketing' ? 'text-white' : ''">
                                    <i data-feather="file-text" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/affiliate-marketing' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/affiliate-marketing' ? 'text-white' : ''">Orders</div>
                            </router-link>
                        </li>
                        <li v-can="'list.affiliate-marketing'">
                            <router-link
                                :to="'/affiliate-marketing/marketers?tag=affiliate'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/marketers') || $route.query.tag == 'marketer' ? 'text-white' : ''">
                                  <i data-feather="users" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/marketers' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/marketers') || $route.query.link == 'marketer' ? 'text-white' : ''">Marketer</div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <!-- <li>
                         <router-link v-can="'list.inquiry'" :to="'/inquiries?tag=posting-inquiry'" :class="$route.query.tag == 'inquiries' ? 'side-menu side-menu--active' : 'side-menu'">
                             <div class="side-menu__icon"> <i data-feather="message-square"></i> </div>
                             <div class="side-menu__title"> Sell with us Inquiries </div>
                         </router-link>
                     </li> -->

                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'customers'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="users" data-lucide="users" class="lucide lucide-users stroke-1.5 mx-auto block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Customers </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="">
                        <li>
                            <router-link
                                v-can="'list.customer'"
                                :to="'/customers?tag=customers'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/customers') ? 'text-white' : ''">
                                  <i data-feather="users" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/customers') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/customers') ? 'text-white' : ''">Customers</div>
                            </router-link>
                        </li>

                        <li>
                            <router-link
                                v-can="'list.customer-registration'"
                                :to="'/customer-registrations?tag=customers'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/customer-registrations') ? 'text-white' : ''">
                                    <i data-feather="users" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/customer-registrations') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/customer-registrations') ? 'text-white' : ''">
                                    Customer Registrations
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </li>


                <!-- <li>

                        <router-link v-can="'access.inquiry'" :to="'/inquiries?tag=inquiries'" :class="$route.query.tag == 'inquiries' ? 'side-menu side-menu--active' : 'side-menu'">
                            <div class="side-menu__icon"> <i data-feather="message-square"></i> </div>
                            <div class="side-menu__title"> Inquiries </div>
                        </router-link>

                     </li> -->

                <li>
                    <a
                        v-can="'access.user'"
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'acl'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="user"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">  Account Management </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.user'">
                            <router-link
                                :to="'/users?tag=acl'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/users') ? 'text-white' : ''">
                                  <i data-feather="users" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/users') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/users') ? 'text-white' : ''">User</div>
                            </router-link>
                        </li>

                        <li v-can="'list.role'">
                            <router-link
                                :to="'/roles?tag=acl'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/roles') ? 'text-white' : ''">
                                  <i data-feather="activity" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/roles') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/roles') ? 'text-white' : ''">Role</div>
                            </router-link>
                        </li>
                        <li v-can="'list.module'">
                            <router-link
                                :to="'/modules?tag=acl'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/modules') ? 'text-white' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="book-open" data-lucide="book-open" class="lucide lucide-book-open stroke-1.5 mx-auto block"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/modules') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/modules') ? 'text-white' : ''">
                                    Modules &amp; Permissions
                                </div>
                            </router-link>
                        </li>
                        <!-- <li>
                                <router-link   :to="'/stores?tag=acl'"  class="side-menu">
                                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                                    <div class="side-menu__title"> Stores</div>
                                 </router-link>
                            </li> -->
                    </ul>
                </li>

                <li v-can="'access.key-visual'">
                    <a
                        v-can="'access.key-visual'"
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'marketing'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="monitor"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Marketing </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'upload.key-visual'">
                            <router-link
                                :to="'/marketing/key-visual?tag=marketing'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/key-visual') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="key" data-lucide="key" class="lucide lucide-key stroke-1.5 mx-auto block"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/key-visual') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/key-visual') ? 'text-white' : ''">Key Visual</div>
                            </router-link>
                        </li>

                        <li v-can="'upload.quicklink'">
                            <router-link
                                :to="'/marketing/quicklinks?tag=marketing'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/quicklinks') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="link2" data-lucide="link2" class="lucide lucide-link2 stroke-1.5 mx-auto block"><path d="M9 17H7A5 5 0 0 1 7 7h2"></path><path d="M15 7h2a5 5 0 1 1 0 10h-2"></path><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/quicklinks') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/quicklinks') ? 'text-white' : ''">QuickLinks</div>
                            </router-link>
                        </li>
                        <li v-can="'upload.ads'">
                            <router-link
                                :to="'/marketing/ads?tag=marketing'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/ads') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="voicemail" data-lucide="voicemail" class="lucide lucide-voicemail stroke-1.5 mx-auto block"><circle cx="6" cy="12" r="4"></circle><circle cx="18" cy="12" r="4"></circle><line x1="6" y1="16" x2="18" y2="16"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/ads') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/ads') ? 'text-white' : ''">Ads</div>
                            </router-link>
                        </li>
                    </ul>
                </li>


                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'reports' || $route.query.tag == 'retail-reports'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="layout" data-lucide="layout" class="lucide lucide-layout stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Reports </span>

                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="">
                      <li v-can="'reportings.user'">
                            <a
                                href="javascript:;"
                                :class="
                                    $route.query.tag == 'reports'
                                        ? 'side-menu side-menu--active'
                                        : 'side-menu'
                                "
                                class="ml-0 xl:ml-4"
                            >
                                <div class="side-menu__icon">
                                    <i data-feather="file-text"></i>
                                </div>
                                <div class="side-menu__title">
                                  <span class="text-sm">Reports </span>
                                    <div class="side-menu__sub-icon">
                                        <i data-feather="chevron-down"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="">
                                <li>
                                    <router-link
                                        :to="'/reports/bid-histories?tag=reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/bid-histories') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="activity" data-lucide="activity" class="lucide lucide-activity stroke-1.5 mx-auto block"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/bid-histories') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/bid-histories') ? 'text-white' : ''">
                                            Bid Histories
                                        </div>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link
                                        :to="'/reports/bidder-per-auction?tag=reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/bidder-per-auction') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="user-check" data-lucide="user-check" class="lucide lucide-user-check stroke-1.5 mx-auto block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/bidder-per-auction') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/bidder-per-auction') ? 'text-white' : ''">
                                            Bidder per Auction
                                        </div>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        :to="'/reports/product-catalogs?tag=reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/product-catalogs') ? 'text-white' : ''">
                                          <i data-feather="file-text" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/product-catalogs') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/product-catalogs') ? 'text-white' : ''">
                                            Product Catalog
                                        </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <li v-can="'list.posting-item'">
                            <a
                                href="javascript:;"
                                :class="
                                    $route.query.tag == 'retail-reports'
                                        ? 'side-menu side-menu--active'
                                        : 'side-menu'
                                "
                                class="ml-0 xl:ml-4"
                            >
                                <div class="side-menu__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trello" data-lucide="trello" class="lucide lucide-trello stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                                </div>
                                <div class="side-menu__title">
                                    <span class="text-sm"> Retail Reports </span>
                                    <div class="side-menu__sub-icon">
                                        <i data-feather="chevron-down"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="">
                                <li>
                                    <router-link
                                        :to="'/reports/retail-inventory-report?tag=retail-reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/retail-inventory-report') ? 'text-white' : ''">
                                          <i data-feather="file-text" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/retail-inventory-report') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/retail-inventory-report') ? 'text-white' : ''">
                                            Inventory Report
                                        </div>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link
                                        :to="'/reports/retail-voucher-report?tag=retail-reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/retail-voucher-report') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trello" data-lucide="trello" class="lucide lucide-trello stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/retail-voucher-report') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/retail-voucher-report') ? 'text-white' : ''">
                                            Voucher Report
                                        </div>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        :to="'/reports/retail-order-report?tag=retail-reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/retail-order-report') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="sidebar" data-lucide="sidebar" class="lucide lucide-sidebar stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/retail-order-report') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/retail-order-report') ? 'text-white' : ''">
                                            Order Detailed Report
                                        </div>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        :to="'/reports/total-inventory-report?tag=retail-reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/total-inventory-report') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="smartphone" data-lucide="smartphone" class="lucide lucide-smartphone stroke-1.5 mx-auto block"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><path d="M12 18h.01"></path></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/total-inventory-report') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/total-inventory-report') ? 'text-white' : ''">
                                            Posting Report
                                        </div>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        :to="'/reports/delivery-reports?tag=retail-reports'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/delivery-reports') ? 'text-white' : ''">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard" data-lucide="clipboard" class="lucide lucide-clipboard stroke-1.5 mx-auto block"><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path></svg>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/delivery-reports') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/delivery-reports') ? 'text-white' : ''">
                                            Delivery Reports
                                        </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'auction-access-request' || $route.query.tag == 'event-access-request'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="key" data-lucide="key" class="lucide lucide-key stroke-1.5 mx-auto block"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm"> Access Request </span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="">
                        <li v-can="'list.auction-access-request'">
                            <a
                                href="javascript:;"
                                :class="
                                    $route.query.tag == 'auction-access-request'
                                        ? 'side-menu side-menu--active'
                                        : 'side-menu'
                                "
                                class="ml-0 xl:ml-4"
                            >
                                <div class="side-menu__icon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 mx-auto block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </div>
                                <div class="side-menu__title">
                                    <span class="text-sm"> Auction Access Request </span>
                                    <div class="side-menu__sub-icon">
                                        <i data-feather="chevron-down"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="">
                                <li v-can="'list.access-request-email-template'">
                                    <router-link
                                        :to="'/request-email-templates?tag=auction-access-request'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/request-email-template') ? 'text-white' : ''">
                                          <i data-feather="mail" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/request-email-template') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/request-email-template') ? 'text-white' : ''">
                                            Auction Request Email Templates
                                        </div>
                                    </router-link>
                                </li>
                                <li v-can="'list.auction-access-request'">
                                    <router-link
                                        :to="'/auction-access-requests?tag=auction-access-request'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/auction-access-requests') ? 'text-white' : ''">
                                          <i data-feather="thumbs-up" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/auction-access-requests') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/auction-access-requests') ? 'text-white' : ''">
                                            Auction Access Requests
                                        </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <li v-can="'list.event-access-request'">
                            <a
                                href="javascript:;"
                                :class="
                                    $route.query.tag == 'event-access-request'
                                        ? 'side-menu side-menu--active'
                                        : 'side-menu'
                                "
                                class="ml-0 xl:ml-4"
                            >
                                <div class="side-menu__icon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square stroke-1.5 mx-auto block"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                </div>
                                <div class="side-menu__title">
                                    <span class="text-sm">Event Access Request </span>
                                    <div class="side-menu__sub-icon">
                                        <i data-feather="chevron-down"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="">
                                <li v-can="'list.event-access-request'">
                                    <router-link
                                        :to="'/event-access-requests?tag=event-access-request'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/event-access-requests') ? 'text-white' : ''">
                                          <i data-feather="thumbs-up" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/event-access-requests') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/event-access-requests') ? 'text-white' : ''">
                                            Event Access Requests
                                        </div>
                                    </router-link>
                                </li>
                                <li v-can="'list.event-access-request-template'">
                                    <router-link
                                        :to="'/event-access-request-template?tag=event-access-request'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/event-access-request-template') ? 'text-white' : ''">
                                          <i data-feather="mail" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/event-access-request-template') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/event-access-request-template') ? 'text-white' : ''">
                                            Event Access Request Templates
                                        </div>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        :to="'/reports/retail-event-reports?tag=event-access-request'"
                                        class="side-menu ml-0 xl:ml-10"
                                    >
                                        <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/retail-event-reports') ? 'text-white' : ''">
                                          <i data-feather="file-text" width="16" height="16"></i>
                                        </div>

                                        <div class="hidden xl:block">
                                          <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/retail-event-reports') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                        </div>

                                        <div class="side-menu__title" :class="$route.path.includes('/retail-event-reports') ? 'text-white' : ''">
                                            Retail Event Reports
                                        </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li>
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'customizer'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="sidebar" data-lucide="sidebar" class="lucide lucide-sidebar stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                        </div>
                        <div class="side-menu__title">
                            <span class="text-sm">Landing Page</span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.retail-section'">
                            <router-link
                                :to="'/retail/section?tag=customizer'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/retail/section' ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="align-center" data-lucide="align-center" class="lucide lucide-align-center stroke-1.5 mx-auto block"><line x1="21" y1="6" x2="3" y2="6"></line><line x1="17" y1="12" x2="7" y2="12"></line><line x1="19" y1="18" x2="5" y2="18"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/retail/section' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/retail/section' ? 'text-white' : ''">
                                    Retail Section
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'list.auction-section'">
                            <router-link
                                :to="'/auction/section?tag=customizer'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/auction/section' ? 'text-white' : ''">
                                  <i data-feather="columns" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/auction/section' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/auction/section' ? 'text-white' : ''">
                                    Auction Section
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'list.navigation'">
                            <router-link
                                :to="'/navigations?tag=customizer'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path == '/navigations' ? 'text-white' : ''">
                                  <i data-feather="layers" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path == '/navigations' ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path == '/navigations' ? 'text-white' : ''">Navigations</div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <router-link
                        v-can="'list.career'"
                        :to="'/career-pages?tag=career-page'"
                        :class="
                            $route.query.tag == 'career-page'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="image"></i>
                        </div>
                        <div class="side-menu__title text-sm">Career Page</div>
                    </router-link>
                </li>

                <li v-can="'settings.user'">
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'settings'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="settings"></i>
                        </div>
                        <div class="side-menu__title">
                          <span class="text-sm">Settings</span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <!-- <li v-can="'list.retail-section'">
                            <router-link
                                :to="'/retail/section?tag=customizer'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                               <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0">
                                    <i data-feather="columns" width="16" height="16"></i>
                                </div>
                                <div class="side-menu__title">
                                    Retail Section
                                </div>
                            </router-link>
                        </li> -->

                        <li v-can="'access.api'">
                            <router-link
                                :to="'/apis?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/apis') ? 'text-white' : ''">
                                  <i data-feather="cloud"  width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/apis') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/apis') ? 'text-white' : ''">API's</div>
                            </router-link>
                        </li>

                        <li v-can="'list.bidder-deposit'">
                            <router-link
                                :to="'/bidder-deposits?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/bidder-deposits') ? 'text-white' : ''">
                                  <i data-feather="dollar-sign" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/bidder-deposits') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/bidder-deposits') ? 'text-white' : ''">
                                    Bidder Deposits
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'access.brand'">
                            <router-link
                                :to="'/brands?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/brands') ? 'text-white' : ''">
                                  <i data-feather="shopping-bag" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/brands') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/brands') ? 'text-white' : ''">Brands</div>
                            </router-link>
                        </li>
                        <li v-can="'access.company'">
                            <router-link
                                :to="'/companies?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/companies') ? 'text-white' : ''">
                                  <i data-feather="globe" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/companies') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/companies') ? 'text-white' : ''">Companies</div>
                            </router-link>
                        </li>
                        <li v-can="'access.courier'">
                            <router-link :to="'/couriers?tag=settings'" class="side-menu ml-0 xl:ml-6">
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/couriers') ? 'text-white' : ''"> <i data-feather="truck" width="16" height="16"></i> </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/couriers') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/couriers') ? 'text-white' : ''"> Couriers </div>
                            </router-link>
                        </li>
                        <li v-can="'access.delivery-status'">
                            <router-link :to="'/delivery-statuses?tag=settings'" class="side-menu ml-0 xl:ml-6">
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/delivery-statuses') ? 'text-white' : ''"> <i data-feather="layers" width="16" height="16"></i> </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/delivery-statuses') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/delivery-statuses') ? 'text-white' : ''"> Delivery Statuses </div>
                            </router-link>
                        </li>
                        <li v-can="'access.courier-status'">
                            <router-link :to="'/courier-statuses?tag=settings'" class="side-menu ml-0 xl:ml-6">
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/courier-statuses') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="truck" data-lucide="truck" class="lucide lucide-truck stroke-1.5 mx-auto block"><path d="M10 17h4V5H2v12h3"></path><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5"></path><path d="M14 17h1"></path><circle cx="7.5" cy="17.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/courier-statuses') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/courier-statuses') ? 'text-white' : ''"> Courier Statuses </div>
                            </router-link>
                        </li>
                        <li v-can="'access.category'">
                            <router-link
                                :to="'/categories?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/categories') ? 'text-white' : ''">
                                    <i data-feather="layers" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/categories') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/categories') ? 'text-white' : ''">Categories</div>
                            </router-link>
                        </li>
                        <li v-can="'list.customer-registration'">
                            <router-link
                                :to="'/customer-registrations?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/customer-registrations') ? 'text-white' : ''">
                                    <i data-feather="user-check" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/customer-registrations') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/customer-registrations') ? 'text-white' : ''">
                                    Customer Registrations
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'list.payment-type'">
                            <router-link
                                :to="'/payment-types?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/payment-types') ? 'text-white' : ''">
                                    <i data-feather="dollar-sign" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/payment-types') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/payment-types') ? 'text-white' : ''">
                                    Payment Types
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'access.store'">
                            <router-link
                                :to="'/stores?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/stores') ? 'text-white' : ''">
                                    <i data-feather="home" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/stores') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/stores') ? 'text-white' : ''">Stores</div>
                            </router-link>
                        </li>
                        <li v-can="'access.tag'">
                            <router-link
                                :to="'/tags?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/tags') ? 'text-white' : ''">
                                    <i data-feather="tag" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/tags') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/tags') ? 'text-white' : ''">Tags</div>
                            </router-link>
                        </li>
                        <li v-can="'access.tag'">
                            <router-link
                                :to="'/inquire-emails?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/inquire-emails') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="phone-forwarded" data-lucide="phone-forwarded" class="lucide lucide-phone-forwarded stroke-1.5 mx-auto block"><polyline points="18 2 22 6 18 10"></polyline><line x1="14" y1="6" x2="22" y2="6"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/inquire-emails') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/inquire-emails') ? 'text-white' : ''">
                                    Contact Us Email Settings
                                </div>
                            </router-link>
                        </li>
                        <li v-can="'list.term'">
                            <router-link
                                :to="'/terms?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/terms') ? 'text-white' : ''">
                                    <i data-feather="book-open" width="16" height="16"></i>
                                </div>
                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/terms') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>
                                <div class="side-menu__title" :class="$route.path.includes('/terms') ? 'text-white' : ''">
                                    Terms and Conditions
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <router-link
                        v-can="'list.failed-job'"
                        :to="'/failed-jobs?tag=failed-jobs'"
                        :class="
                            $route.query.tag == 'failed-jobs'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="frown"></i>
                        </div>
                        <div class="side-menu__title text-sm">Failed Jobs</div>
                    </router-link>
                </li>

                <li v-can="'access.sudo'">
                    <a
                        href="javascript:;"
                        :class="
                            $route.query.tag == 'admin-tools'
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="lock" data-lucide="lock" class="lucide lucide-lock stroke-1.5 mx-auto block"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <div class="side-menu__title">
                            <span class=text-sm>Admin Tools</span>
                            <div class="side-menu__sub-icon">
                                <i data-feather="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                      <!-- <li v-can="'list.payment-type'">
                            <router-link
                                :to="'/payment-types?tag=settings'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0">
                                    <i data-feather="dollar-sign" width="16" height="16"></i>
                                </div>
                                <div class="side-menu__title">
                                    Payment Types
                                </div>
                            </router-link>
                        </li> -->

                        <li v-can="''">
                            <router-link
                                :to="'/admin-tools/cart-extension?tag=admin-tools'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/cart-extension') ? 'text-white' : ''">
                                  <i data-feather="shopping-cart" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/cart-extension') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/cart-extension') ? 'text-white' : ''">
                                    Cart Extension
                                </div>
                            </router-link>
                        </li>
                        <li v-can="''">
                            <router-link
                                :to="'/admin-tools/order-uncancellation?tag=admin-tools'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/order-uncancellation') ? 'text-white' : ''">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="slash" data-lucide="slash" class="lucide lucide-slash stroke-1.5 mx-auto block"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/order-uncancellation') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/order-uncancellation') ? 'text-white' : ''">
                                    Order Uncancellation
                                </div>
                            </router-link>
                        </li>
                        <li v-can="''">
                            <router-link
                                :to="'/admin-tools/bidder-auctions?tag=admin-tools'"
                                class="side-menu ml-0 xl:ml-6"
                            >
                                <div class="side-menu__icon hidden sm:block xl:hidden ml-1 xl:ml-0" :class="$route.path.includes('/bidder-auctions') ? 'text-white' : ''">
                                  <i data-feather="package" width="16" height="16"></i>
                                </div>

                                <div class="hidden xl:block">
                                  <i style="top: 20px; left: 12px;" :class="`${$route.path.includes('/bidder-auctions') ? 'opacity-1' : 'opacity-0'}`" class="fa-solid fa-arrow-right-long absolute"></i>
                                </div>

                                <div class="side-menu__title" :class="$route.path.includes('/bidder-auctions') ? 'text-white' : ''">
                                    Auction Bidders
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <!-- <li>
                    <router-link
                        v-can="''"
                        :to="'/events/29/live-selling'"
                        :class="
                            $route.query.tag == ''
                                ? 'side-menu side-menu--active'
                                : 'side-menu'
                        "
                    >
                        <div class="side-menu__icon">
                            <i data-feather="frown"></i>
                        </div>
                        <div class="side-menu__title">Live Selling Controller</div>
                    </router-link>
                </li> -->

            </ul>
        </nav>
        <!-- END: Side Menu -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOtherRoute: true,
            isLiveSellingControllerRoute:  sessionStorage.getItem('isLiveSellingControllerRoute') == 'true' ? true : false
        };
    },

    watch: {
        $route() {
            if (this.$route.path.includes("events") && this.$route.path.includes("live-selling")) {
                this.isOtherRoute = false
            } else {
                // sessionStorage.removeItem('isLiveSellingControllerRoute');
                this.isOtherRoute = true
            }
        },

    },

    mounted() {
        if (this.isLiveSellingControllerRoute) {
            this.isOtherRoute = false
        } else {
            this.isOtherRoute = true
        }
    }
};
</script>
