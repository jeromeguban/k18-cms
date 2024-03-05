<template>
	<div class="w-full">
		<table-template link="/events" :fields="fields" modalIdentifier="#events-create">
			<template slot="label">
				<h4>List of Events</h4>
			</template>
			<template slot="name" slot-scope="props">
				<span class="font-medium">{{ props.item.name }}</span>

			</template>

			<template slot="registration_banner" slot-scope="props">
				<span v-if="!props.item.registration_banner" class="font-medium">No Registration Banner Found</span>
				<img alt="Registration Banner" v-if="props.item.registration_banner"
					:src="props.item.registration_banner" data-action="zoom"
					class=" w-1/2 h-1/2 my-5 image-fit rounded-md">
			</template>
			<template slot="banner" slot-scope="props">
				<span v-if="!props.item.banner" class="font-medium">No Banner Found</span>
				<img alt="Banner" v-if="props.item.banner" :src="props.item.banner" data-action="zoom"
					class=" w-1/2 h-1/2 my-5 image-fit rounded-md">
			</template>
			<template slot="event_id" slot-scope="props">
				<div class="flex justify-center items-center">
					<div class="intro-x dropdown w-8 h-8">

						<div class="dropdown-toggle w-8 h-8" role="button">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
								<path
									d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" />
							</svg>
						</div>

						<div class="dropdown-menu w-56">
							<div class="dropdown-menu__content box bg-white-26 dark:bg-dark-6">
								<div class="p-2">
									<router-link :to="'/events/' + props.item.event_id +'?tag=settings'"
										v-can="'view.event'">
										<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
											href="javascript:;">
											<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
												viewBox="0 0 512 512">
												<path
													d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
											</svg>Show
										</a>
									</router-link>

                                    <router-link
                                        v-if="props.item.category == 'Live Selling'"
                                        :to="'/events/' + props.item.event_id +'/live-selling'"
										v-can="'live-selling.event'">
										<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
											href="javascript:;">
											<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
												viewBox="0 0 512 512">
												<path
													d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
											</svg>Live Selling Controller
										</a>
									</router-link>

									<router-link  v-if="props.item.category == 'Live Selling'"
										:to="'/live-selling/' + props.item.event_id + '/view'"
										v-can="'live-selling.event'">
										<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
											<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M401 438.6l-49.19-30.75C409.88 367.39 448 300.19 448 224 448 100.29 347.71 0 224 0S0 100.29 0 224c0 76.19 38.12 143.39 96.23 183.85L47 438.6a32 32 0 0 0-15 27.14V480a32 32 0 0 0 32 32h320a32 32 0 0 0 32-32v-14.26a32 32 0 0 0-15-27.14zM32 224c0-106 86-192 192-192s192 86 192 192-86 192-192 192S32 330 32 224zm352 256H64v-14.26L127.62 426a221.84 221.84 0 0 0 192.76 0L384 465.74zm0-256a160 160 0 1 0-160 160 160 160 0 0 0 160-160zm-288 0a128 128 0 1 1 128 128A128.14 128.14 0 0 1 96 224zm144-80a16 16 0 0 0-16-16 96.1 96.1 0 0 0-96 96 16 16 0 0 0 32 0 64.07 64.07 0 0 1 64-64 16 16 0 0 0 16-16z"/>
											</svg>
											Show Display Page
										</a>
									</router-link>

                                    <router-link
                                        v-if="props.item.category == 'Live Selling'"
                                        :to="'/product-catalogs/'+ props.item.event_id"
                                        v-can="'live-selling.event'">
                                        <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
											href="javascript:;">
											<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
												viewBox="0 0 512 512">
												<path
													d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
											</svg>Product Catalog
										</a>
                                    </router-link>

									<input type="hidden" :id="props.item.event_id"
										:value="'https://hmr.ph/events/' + props.item.slug + '/registration'">
									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" @click.prevent="copyRegistrationUrl(props.item.event_id)">
										<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 512 512">
											<path
												d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
										</svg>Copy Registration Url
									</a>

									<input type="hidden" :id="props.item.slug"
										:value="'https://hmr.ph/events/' + props.item.slug + '/products'">
									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" @click.prevent="copyProductsUrl(props.item.slug)">
										<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 512 512">
											<path
												d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
										</svg>Copy Products Url
									</a>

									<a v-if="!props.item.published_date"
										class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" @click.prevent="publishEvent(props.item.event_id)"
										v-can="'publish.event'">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
											stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-send w-4 h-4 mr-2">
											<line x1="22" y1="2" x2="11" y2="13"></line>
											<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
										</svg> Publish
									</a>

									<a v-if="props.item.published_date"
										class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" @click.prevent="unpublishEvent(props.item.event_id)"
										v-can="'unpublish.event'">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
											stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-inbox w-4 h-4 mr-2">
											<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
											<path
												d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z">
											</path>
										</svg> Unpublish
									</a>



									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" data-toggle="modal"
										:data-target="'#event-uppy' + props.item.event_id">
										<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 512 512">
											<path
												d="M342.7 144H464v288H48V144h121.3l24-64h125.5l23.9 64zM324.3 32h-131c-20 0-37.9 12.4-44.9 31.1L136 96H48c-26.5 0-48 21.5-48 48v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48h-88l-14.3-38c-5.8-15.7-20.7-26-37.4-26zM256 408c-66.2 0-120-53.8-120-120s53.8-120 120-120 120 53.8 120 120-53.8 120-120 120zm0-192c-39.7 0-72 32.3-72 72s32.3 72 72 72 72-32.3 72-72-32.3-72-72-72z" />
										</svg> Upload Registration Banner
									</a>

									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" data-toggle="modal"
										:data-target="'#event-banner-uppy' + props.item.event_id">
										<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 512 512">
											<path
												d="M342.7 144H464v288H48V144h121.3l24-64h125.5l23.9 64zM324.3 32h-131c-20 0-37.9 12.4-44.9 31.1L136 96H48c-26.5 0-48 21.5-48 48v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48h-88l-14.3-38c-5.8-15.7-20.7-26-37.4-26zM256 408c-66.2 0-120-53.8-120-120s53.8-120 120-120 120 53.8 120 120-53.8 120-120 120zm0-192c-39.7 0-72 32.3-72 72s32.3 72 72 72 72-32.3 72-72-32.3-72-72-72z" />
										</svg> Upload Banner
									</a>

									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" data-toggle="modal" data-target="#events-edit"
										@click.prevent="setEvent(props.item, props.index)" v-can="'update.event'">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
											stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-edit w-4 h-4 mr-1 text-gray-600">
											<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
											<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
										</svg> Edit
									</a>

									<a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
										href="javascript:;" @click.prevent="deleteEvent(props.item.event_id)"
										v-can="'delete.event'">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
											stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-trash-2 w-4 h-4 mr-1">
											<polyline points="3 6 5 6 21 6"></polyline>
											<path
												d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
											</path>
											<line x1="10" y1="11" x2="10" y2="17"></line>
											<line x1="14" y1="11" x2="14" y2="17"></line>
										</svg> Delete
									</a>

									<div :id="'event-uppy' + props.item.event_id" class="modal" tabindex="-1"
										aria-hidden="true">
										<div class="modal-dialog modal-xl">
											<div class="modal-content">
												<!-- BEGIN: Modal Header -->
												<div class="modal-header bg-primary-1 text-theme-2">
													<h2 class="font-medium text-base mr-auto">Add Registration Banner
													</h2>
													<!-- <button class="btn btn-outline-secondary hidden sm:flex">
                                                    <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                                                </button> -->
												</div>
												<div class="modal-body flex justify-center">
													<uppy-dashboard
														:url="'/events/'+props.item.event_id+'/registration-banners'"
														class="-mt-0" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div :id="'event-banner-uppy' + props.item.event_id" class="modal" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<!-- BEGIN: Modal Header -->
								<div class="modal-header bg-primary-1 text-theme-2">
									<h2 class="font-medium text-base mr-auto">Add Banner</h2>
									<!-- <button class="btn btn-outline-secondary hidden sm:flex">
                                    <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                                </button> -->
								</div>
								<div class="modal-body flex justify-center">
									<uppy-dashboard :url="'/events/'+props.item.event_id+'/banners'" class="-mt-0" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</template>
		</table-template>

		<create></create>

		<edit v-if="event" @close="clearEvent" @update="clearEvent" :event="event" />

		<!-- <input-modal
		  	:identifier="'#edit-'+index"
		  	:triggerEvent="true"
		  	@close="clearEvent"
		  	v-if="event">
		  	<edit
		  		:event="event"/>
		  </input-modal> -->
	</div>
</template>
<script>
import UppyDashboard from '../../defaults/uppy-dashboard.vue';
import TableTemplate from "../../Table";
import Edit from "./edit";
import Create from "./create";
export default {
	components: { TableTemplate, Create, Edit, UppyDashboard },
	data() {
		return {
			fields: {
				registration_banner : 'Registration Banner',
				banner 				: 'Banner',
				event_name 			: 'Name',
				type 				: 'Type',
				description 		: 'Description',
				purchase_limit 		: 'Purchase Limit',
				event_id    		: 'Actions',
			},
			event: null,
			index: 0,
		}
	},
	methods: {
		deleteEvent(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Event?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {
				axios.delete(`/events/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted an Event',
					 	title: 'Deleted'
					 });
					 // Reload page
				app.$emit('reload');
				})
			})
			.catch();
		},

		setEvent(event, index) {
			console.log(index);
			this.event = event;
			this.index = index;
		},
		clearEvent() {
			this.event = null;
			this.index = 0;
		},

		publishEvent(id){
			this.$modal.dialog({
				message: 'Are you sure you want to Publish this event?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Publish'
			})
			.then(confirmed => {
				axios.patch(`/events/${id}/publish`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully publish a event',
					 	title: 'Publish'
					 });
				 // Reload page
				app.$emit('reload');
				})
				.catch((error)=>{
				if(error.response.status)
				this.$modal.error({
					message:error.response.data.message,
				});

			});
			})
		},

		unpublishEvent(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to unpublish this event?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Unpublish'
			})
			.then(confirmed => {
				axios.patch(`/events/${id}/unpublish`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You Unpublish this event',
					 	title: 'Unpublish'
					 });
					// Reload page
					app.$emit('reload');
				})
				.catch((error)=>{
				if(error.response.status)
				this.$modal.error({
					message:error.response.data.message,
				});

			});
			})
		},

		copyRegistrationUrl(event_id) {

			let url = document.querySelector(`[id='${event_id}']`)
			url.setAttribute('type', 'text')
			url.select()

			try {
				var successful = document.execCommand('copy');
				var msg = successful ? 'successful' : 'unsuccessful';
				alert(' Url was copied ' + msg);
			} catch (err) {
				alert('Oops, unable to copy Url');
			}

			/* unselect the range */
			url.setAttribute('type', 'hidden')
			window.getSelection().removeAllRanges()
		},

		copyProductsUrl(slug) {
			let url = document.querySelector(`[id='${slug}']`)
			url.setAttribute('type', 'text')
			url.select()

			try {
				var successful = document.execCommand('copy');
				var msg = successful ? 'successful' : 'unsuccessful';
				alert(' Url was copied ' + msg);
			} catch (err) {
				alert('Oops, unable to copy Url');
			}

			/* unselect the range */
			url.setAttribute('type', 'hidden')
			window.getSelection().removeAllRanges()
		 },
	}
}
</script>
