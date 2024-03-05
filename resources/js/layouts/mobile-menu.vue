<template>
    <div>
        <!-- BEGIN: Mobile Menu -->
        <div class="mobile-menu md:hidden">
            <div class="mobile-menu-bar">
                <a href="" class="flex mr-auto">
                    <img alt="HMR.ph CMS" class="w-6" src="images/logo.svg">
                </a>
                <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2"
                        class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            </div>
            <ul class="border-t border-theme-29 py-5 hidden">
                <li>
                    <router-link :to="'/'" class="menu">
                        <div class="menu__icon"> <i data-feather="home"></i> </div>
                        <div class="menu__title text-sm"> Dashboard </div>
                    </router-link>
                </li>

                <li>
                    <router-link v-can="'access.auction'" :to="'/auctions?tag=auction'" class="menu">
                        <div class="menu__icon"> <i data-feather="tv"></i> </div>
                        <div class="menu__title text-sm"> Auctions </div>
                    </router-link>
                </li>

                <li>
                    <router-link v-can="'access.event'" :to="'/events?tag=event'" class="menu">
                        <div class="menu__icon"> <i data-feather="smartphone"></i> </div>
                        <div class="menu__title text-sm"> Events </div>
                    </router-link>
                </li>

                <li>
                    <router-link v-can="'publish.posting'" :to="'/postings?tag=posting'" class="menu">
                        <div class="menu__icon"> <i data-feather="monitor"></i> </div>
                        <div class="menu__title text-sm"> Postings </div>
                    </router-link>
                </li>

                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="list" data-lucide="list" class="lucide lucide-list stroke-1.5 mx-auto block"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        </div>
                        <div class="menu__title"> <span class="text-sm">Orders</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li  v-can="'access.order'">
                            <router-link @click.native="getRouter()"  :to="'/orders?tag=orders'" class="menu ml-6">
                                <div class="menu__title"> Orders </div>
                            </router-link>
                        </li>

                    
                        <li v-can="'access.order'">
                            <router-link @click.native="getRouter()" :to="'/order-waybills?tag=orders'" class="menu ml-6">
                                <div class="menu__title"> Order Way Bills </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <router-link v-can="'access.section'" :to="'/sections?tag=section'" class="menu">
                        <div class="menu__icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="align-justify" data-lucide="align-justify" class="lucide lucide-align-justify stroke-1.5 mx-auto block"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        </div>
                        <div class="menu__title text-sm"> Section </div>
                    </router-link>
                </li>

                <li>
                    <router-link v-can="'access.voucher'" :to="'/voucher?tag=voucher'" class="menu">
                        <div class="menu__icon"> <i data-feather="percent"></i> </div>
                        <div class="menu__title text-sm"> Vouchers </div>
                    </router-link>
                </li>

                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <i data-feather="shopping-cart"></i>
                        </div>
                        <div class="menu__title"> <span class="text-sm">Abandoned Carts</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.cart'">
                            <router-link @click.native="getRouter()" :to="'/abandoned-cart?tag=abandoned-carts'" class="menu ml-6">
                                <div class="menu__title"> Abandoned Carts </div>
                            </router-link>
                        </li>

                    
                        <li v-can="'list.abandoned-cart-history'">
                            <router-link @click.native="getRouter()" :to="'/abandoned-cart-history?tag=abandoned-carts'" class="menu ml-6">
                                <div class="menu__title"> Abandoned Cart Histories </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <a v-can="''" href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="folder"></i> </div>
                        <div class="menu__title"> <span class="text-sm">Inquiries</span>
                            <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.account-executive'">
                            <router-link @click.native="getRouter()" :to="'/account-executives?tag=inquiries'"
                                class="menu ml-6">
                                <div class="menu__title">Account Executives</div>
                            </router-link>
                        </li>
                        <li v-can="'list.posting-inquiry'">
                            <router-link @click.native="getRouter()" :to="'/posting-inquiries?tag=inquiries'"
                                class="menu ml-6">
                                <div class="menu__title"> Posting Inquiries </div>
                            </router-link>
                        </li>

                        <li>
                            <router-link v-can="'list.posting-inquiry'" :to="'/store-inquiries?tag=store-inquiries'"
                                class="menu ml-6">
                                <div class="menu__title"> Store Inquiries </div>
                            </router-link>
                        </li>
                    </ul>
                </li>


                <li v-can="'list.payable'">
                    <a v-can="''" href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="folder"></i> </div>
                        <div class="menu__title"> <span class="text-sm">Accounting</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.bank'">
                            <router-link @click.native="getRouter()" :to="'/banks?tag=accounting'" class="menu ml-6">
                                <div class="menu__title"> Banks </div>
                            </router-link>
                        </li>

                        <li v-can="'list.cost'">
                            <router-link @click.native="getRouter()" :to="'/costs?tag=accounting'" class="menu ml-6">
                                <div class="menu__title"> Costs </div>
                            </router-link>
                        </li>

                        <li v-can="'list.cost-type'">
                            <router-link @click.native="getRouter()" :to="'/cost-types?tag=accounting'" class="menu ml-6">
                                <div class="menu__title"> Cost Types </div>
                            </router-link>
                        </li>

                        <li v-can="'list.payable'">
                            <router-link @click.native="getRouter()" :to="'/payables?tag=accounting'" class="menu ml-6">
                                <div class="menu__title"> Payables </div>
                            </router-link>
                        </li>

                    </ul>
                </li>

                <li v-can="'access.affiliate-marketing'">
                    <a v-can="''" href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="percent"></i> </div>
                        <div class="menu__title"><span class="text-sm">Affiliate Marketing</span> <i data-feather="chevron-down"
                                class="menu__sub-icon "></i> </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.affiliate-marketing'">
                            <router-link @click.native="getRouter()" :to="'/affiliate-marketing?tag=affiliate'"
                                class="menu ml-6">
                                <div class="menu__title"> Orders </div>
                            </router-link>
                        </li>

                        <li v-can="'list.affiliate-marketing'">
                            <router-link @click.native="getRouter()"
                                :to="'/affiliate-marketing/marketers?tag=affiliate'" class="menu ml-6">
                                <div class="menu__title"> Marketer </div>
                            </router-link>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="users" data-lucide="users" class="lucide lucide-users stroke-1.5 mx-auto block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div class="menu__title"><span class="text-sm">Customers</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.customer'">
                            <router-link @click.native="getRouter()" :to="'/customers?tag=customers'" class="menu ml-6">
                                <div class="menu__title"> Customers </div>
                            </router-link>
                        </li>

                    
                        <li v-can="'list.customer-registration'">
                            <router-link @click.native="getRouter()" :to="'/customer-registrations?tag=customers'" class="menu ml-6">
                                <div class="menu__title"> Customer Registrations </div>
                            </router-link>
                        </li>
                    </ul>
                </li>


                <li>
                    <a v-can="'access.user'" href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="user"></i> </div>
                        <div class="menu__title"><span class="text-sm">Account Management</span> <i data-feather="chevron-down" class="menu__sub-icon "></i> </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.user'">
                            <router-link @click.native="getRouter()" :to="'/users?tag=acl'" class="menu ml-6">
                                <div class="menu__title"> Users </div>
                            </router-link>
                        </li>
                        <li v-can="'list.role'">
                            <router-link @click.native="getRouter()" :to="'/roles?tag=acl'" class="menu ml-6">
                                <div class="menu__title"> Roles </div>
                            </router-link>
                        </li>
                        <li v-can="'list.module'">
                            <router-link @click.native="getRouter()" :to="'/modules?tag=acl'" class="menu ml-6">
                                <div class="menu__title"> Module &amp; Permissions </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li v-can="'access.key-visual'">
                    <a v-can="'access.key-visual'" href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="monitor"></i> </div>
                        <div class="menu__title"><span class="text-sm">Marketing</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'upload.key-visual'">
                            <router-link @click.native="getRouter()" :to="'/marketing/key-visual?tag=marketing'"
                                class="menu ml-6">
                                <div class="menu__title"> Key Visual </div>
                            </router-link>
                        </li>
                        <li v-can="'upload.quicklink'">
                            <router-link @click.native="getRouter()" :to="'/marketing/quicklinks?tag=marketing'"
                                class="menu ml-6">
                                <div class="menu__title"> QuickLinks </div>
                            </router-link>
                        </li>
                        <li v-can="'upload.ads'">
                            <router-link @click.native="getRouter()" :to="'/marketing/ads?tag=marketing'" class="menu ml-6">
                                <div class="menu__title"> Ads </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="layout" data-lucide="layout" class="lucide lucide-layout stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                        </div>
                        <div class="menu__title"><span class="text-sm">Reports</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                      <li v-can="'reportings.user'">
                            <a href="javascript:;" class="menu">
                                <div class="menu__icon"> <i data-feather="file-text"></i> </div>
                                <div class="menu__title"><span class="text-sm">Reports</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                                </div>
                            </a>
                            <ul class="">
                                <li v-can="'upload.key-visual'">
                                    <router-link @click.native="getRouter()" :to="'/reports/bid-histories?tag=reports'"
                                        class="menu ml-6">
                                        <div class="menu__title"> Bid Histories </div>
                                    </router-link>
                                </li>
                                <li v-can="'upload.quicklink'">
                                    <router-link @click.native="getRouter()" :to="'/reports/bidder-per-auction?tag=reports'"
                                        class="menu ml-6">
                                        <div class="menu__title"> Bidder per Auction </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <li v-can="'list.posting-item'">
                            <a href="javascript:;" class="menu">
                                <div class="menu__icon"> 
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trello" data-lucide="trello" class="lucide lucide-trello stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>  
                                </div>
                                <div class="menu__title"><span class="text-sm">Retail Reports</span> <i data-feather="chevron-down"
                                        class="menu__sub-icon "></i> </div>
                            </a>
                            <ul class="">
                                <li>
                                    <router-link @click.native="getRouter()"
                                        :to="'/reports/retail-inventory-report?tag=retail-reports'" class="menu ml-6">
                                        <div class="menu__title"> Inventory Report </div>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link @click.native="getRouter()"
                                        :to="'/reports/reports/retail-voucher-report?tag=retail-reports'" class="menu ml-6">
                                        <div class="menu__title"> Voucher Report </div>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link @click.native="getRouter()"
                                        :to="'/reports/reports/retail-order-report?tag=retail-reports'" class="menu ml-6">
                                        <div class="menu__title"> Oder Detailed Report </div>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="key" data-lucide="key" class="lucide lucide-key stroke-1.5 mx-auto block"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                        </div>
                        <div class="menu__title"><span class="text-sm">Access Request</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.auction-access-request'">
                            <a href="javascript:;" class="menu">
                                <div class="menu__icon"> 
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 mx-auto block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 
                                </div>
                                <div class="menu__title"><span class="text-sm">Auction Access Request</span> <i data-feather="chevron-down"
                                        class="menu__sub-icon "></i> </div>
                            </a>
                            <ul class="">
                                <li v-can="'list.access-request-email-template'">
                                    <router-link @click.native="getRouter()" :to="'/request-email-templates?tag=settings'"
                                        class="menu ml-6">
                                        <div class="menu__title"> Auction Request Email Templates </div>
                                    </router-link>
                                </li>

                                <li v-can="'list.auction-access-request'">
                                    <router-link @click.native="getRouter()" :to="'/auction-access-requests?tag=settings'"
                                        class="menu ml-6">
                                        <div class="menu__title"> Access Requests </div>
                                    </router-link>
                                </li>

                            </ul>
                        </li>

                        <li v-can="'list.event-access-request'">
                          <a href="javascript:;" class="menu">
                              <div class="menu__icon"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square stroke-1.5 mx-auto block"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                              </div>
                              <div class="menu__title"><span class="text-sm">Event Access Request</span> <i data-feather="chevron-down"
                                      class="menu__sub-icon "></i> </div>
                          </a>
                          <ul class="">
                              <li v-can="'list.event-access-request'">
                                  <router-link @click.native="getRouter()" :to="'/event-access-requests?tag=settings'"
                                      class="menu ml-6">
                                      <div class="menu__title"> Event Access Requests </div>
                                  </router-link>
                              </li>

                              <li v-can="'list.access-request-email-template'">
                                  <router-link @click.native="getRouter()" :to="'/event-access-request-template?tag=settings'"
                                      class="menu ml-6">
                                      <div class="menu__title"> Event Access Request Templates </div>
                                  </router-link>
                              </li>
                              <li>
                                  <router-link @click.native="getRouter()" :to="'/reports/retail-event-reports'" class="menu ml-6">
                                      <div class="menu__title"> Retail Event Reports </div>
                                  </router-link>
                              </li>
                          </ul>
                      </li>
                    </ul>
                </li>


                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="sidebar" data-lucide="sidebar" class="lucide lucide-sidebar stroke-1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                        </div>
                        <div class="menu__title"><span class="text-sm">Landing Page</span> <i data-feather="chevron-down"
                                class="menu__sub-icon "></i> </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.retail-section'">
                            <router-link @click.native="getRouter()" :to="'/retail/section?tag=customizer'"
                                class="menu ml-6">
                                <div class="menu__title"> Retail Section </div>
                            </router-link>
                        </li>

                        <li v-can="'list.auction-section'">
                            <router-link @click.native="getRouter()" :to="'/auction/section?tag=customizer'"
                                class="menu ml-6">
                                <div class="menu__title"> Auction Section </div>
                            </router-link>
                        </li>

                        <li v-can="'list.navigation'">
                            <router-link @click.native="getRouter()" :to="'/navigations?tag=customizer'" class="menu ml-6">
                                <div class="menu__title"> Navigations </div>
                            </router-link>
                        </li>

                        <!-- <li v-can="'upload.key-visual'">
                         <router-link @click.native="getRouter()"  :to="'/reports/reports/retail-order-report?tag=retail-reports'" class="menu">
                            <div class="menu__icon"> <i data-feather="file-text"></i> </div>
                            <div class="menu__title"> Oder Detailed Report </div>
                         </router-link>
                    </li> -->
                    </ul>
                </li>

                <li>
                    <router-link v-can="'access.career'" :to="'/career-pages?tag=career'" class="menu">
                        <div class="side-menu__icon"> <i data-feather="image"></i> </div>
                        <div class="menu__title text-sm"> Career Page </div>
                    </router-link>
                </li>

                <li v-can="'settings.user'">
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-feather="settings"></i> </div>
                        <div class="menu__title"><span class="text-sm">Settings</span> <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <!-- <li v-can="'list.auction-access-request'">
                            <router-link @click.native="getRouter()" :to="'/auction-access-requests?tag=settings'"
                                class="menu">
                                <div class="menu__icon"> <i data-feather="thumbs-up"></i> </div>
                                <div class="menu__title"> Access Requests </div>
                            </router-link>
                        </li>
                        <li v-can="'list.access-request-email-template'">
                            <router-link @click.native="getRouter()" :to="'/request-email-templates?tag=settings'"
                                class="menu">
                                <div class="menu__icon"> <i data-feather="mail"></i> </div>
                                <div class="menu__title"> Auction Request Email Templates </div>
                            </router-link>
                        </li> -->
                        <li v-can="'access.api'">
                            <router-link @click.native="getRouter()" :to="'/apis?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> API's </div>
                            </router-link>
                        </li>
                        <li v-can="'access.bidder-deposit'">
                            <router-link @click.native="getRouter()" :to="'/bidder-deposits?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Bidder Deposits </div>
                            </router-link>
                        </li>
                        <li v-can="'access.brands'">
                            <router-link @click.native="getRouter()" :to="'/brands?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Brands </div>
                            </router-link>
                        </li>
                        <li v-can="'access.company'">
                            <router-link @click.native="getRouter()" :to="'/companies?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Companies </div>
                            </router-link>
                        </li>
                        <li v-can="'access.courier'">
                            <router-link @click.native="getRouter()" :to="'/couriers?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Couriers </div>
                            </router-link>
                        </li>
                        <li v-can="'access.courier-statuses'">
                            <router-link @click.native="getRouter()" :to="'/courier-statuses?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Courier Statuses </div>
                            </router-link>
                        </li>
                        <li v-can="'access.category'">
                            <router-link @click.native="getRouter()" :to="'/categories?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> CS </div>
                            </router-link>
                        </li>
                        <li v-can="'access.customer-registration'">
                            <router-link @click.native="getRouter()" :to="'/customer-registrations?tag=settings'"
                                class="menu ml-6">
                                <div class="menu__title"> Customer Registrations </div>
                            </router-link>
                        </li>
                        <li v-can="'list.payment-type'">
                            <router-link @click.native="getRouter()" :to="'/payment-types?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Payment Types </div>
                            </router-link>
                        </li>
                        <li v-can="'access.store'">
                            <router-link @click.native="getRouter()" :to="'/stores?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Stores </div>
                            </router-link>
                        </li>
                        <li v-can="'access.tags'">
                            <router-link @click.native="getRouter()" :to="'/tags?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Tags </div>
                            </router-link>
                        </li>
                        <li v-can="'list.term'">
                            <router-link @click.native="getRouter()" :to="'/terms?tag=settings'" class="menu ml-6">
                                <div class="menu__title"> Terms and Condition </div>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li>
                    <router-link v-can="'list.failed-job'" :to="'/failed-jobs?tag=failed-jobs'" class="menu">
                        <div class="menu__icon"> <i data-feather="frown"></i> </div>
                        <div class="menu__title text-sm"> Failed Jobs </div>
                    </router-link>
                </li>

                <li>
                    <a v-can="'access.sudo'" href="javascript:;" class="menu">
                        <div class="menu__icon"> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="lock" data-lucide="lock" class="lucide lucide-lock stroke-1.5 mx-auto block"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                          </div>
                        <div class="menu__title"><span class="text-sm">Admin Tools</span> 
                            <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li v-can="'list.posting-inquiry'">
                            <router-link @click.native="getRouter()" :to="'/posting-inquiries?tag=admin-tools'"
                                class="menu ml-6">
                                <div class="menu__title"> Cart Extension</div>
                            </router-link>
                        </li>
                        <li v-can="'list.posting-inquiry'">
                            <router-link @click.native="getRouter()" :to="'/order-uncancellation?tag=admin-tools'"
                                class="menu ml-6">
                                <div class="menu__title"> Order Uncancellation</div>
                            </router-link>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Mobile Menu -->
</template>

<script>
export default {

    data() {
        return {
            router: null,
        }
    },

    watch: {

    },

    created() {
        this.router = this.$route.path;
    },

    methods: {
        getRouter() {
            this.router = this.$route.path;
        }
    }
}
</script>
