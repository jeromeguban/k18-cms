<template>
	<div>
		<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
			<h2 class="text-lg font-medium mr-auto">Customize Header Section</h2>
			<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
				<div class="flex items-center justify-end">
					<a @click.prevent="$router.go(-1)" class="btn btn-primary w-32 mr-2 mb-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							icon-name="home" data-lucide="home" class="lucide lucide-home w-3 h-3 mr-2">
							<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
							<polyline points="9 22 9 12 15 12 15 22"></polyline>
						</svg>
						Menu
					</a>
					<a href="#" @click.prevent="" data-toggle="modal" data-target="#header-create"
						class="btn btn-primary w-32 mr-2 mb-2">
						<svg class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path
								d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
						</svg>Add New
					</a>
				</div>
			</div>
		</div>
		<div class="intro-y box mt-5">
			<div class="px-5 sm:px-10 py-5 sm:py-10">
				<div class="w-full">
					<draggable v-model="navigations" @change="updateSequenced"
						:options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
						class="flex flex-row flex-wrap gap-4 justify-center">
						<div v-for="(navigation, index) in navigations" :key="navigation.id" class="intro-x">
							<div class="">
								<div class="flex flex-col w-full border-theme-13 border-solid shadow-md rounded-b pb-2">

									<div
										class="flex flex-row justify-between w-full px-4 mb-4 py-2 text-white bg-theme-1">

										<span class="flex">
											<li class="absolute cursor-pointer list-none item">
												<svg class="fill-current w-4 h-4" viewBox="0 0 512 512">
													<path
														d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z" />
												</svg>
											</li>
										</span>
										<div>
											<span class="flex text-center uppercase ml-6">
												{{ index + 1 }} . {{ navigation.label }}
											</span>
										</div>
										<span class="flex">
											<a class="flex" href="#" data-toggle="modal" data-target="#header-edit"
												@click.prevent="setNavigation(navigation)">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-edit w-4 h-4">
													<path
														d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
													</path>
													<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
													</path>
												</svg>
											</a>
											<a href="#" @click.prevent="deleteNavigation(navigation.id)">
												<svg class="fill-current w-4 h-4 ml-2" viewBox="0 0 448 512">
													<path
														d="M296 432h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zm-160 0h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zM440 64H336l-33.6-44.8A48 48 0 0 0 264 0h-80a48 48 0 0 0-38.4 19.2L112 64H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h24v368a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V96h24a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8zM171.2 38.4A16.1 16.1 0 0 1 184 32h80a16.1 16.1 0 0 1 12.8 6.4L296 64H152zM384 464a16 16 0 0 1-16 16H80a16 16 0 0 1-16-16V96h320zm-168-32h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8z" />
												</svg>
											</a>
										</span>
									</div>
									<draggable v-if="navigation.property.length" v-model="navigation.property"
										group="property" @change="updateProperty(navigation)"
										:options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
										class="overflow-y-scroll overflow-x-hidden w-full px-2 lg:px-4"
										style="max-height: 150px; min-height: 150px; max-width:320px; min-width: 320px;">
										<div class="flex flex-col" v-for="(property, index) in navigation.property"
											:key="index">
											<div class="flex py-2">
												<li class="w-12 item list-none cursor-pointer">
													<svg class="fill-current w-4 h-5 cursor-pointer"
														viewBox="0 0 512 512">
														<path
															d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z" />
													</svg>
												</li>
												<div class="flex justify-between w-full">
													<!-- <div class="flex items-center justify-center text-2xs mr-3 bg-theme-1 rounded-full text-white"
														style="width: 17px; height: 17px;">{{ index + 1 }}
													</div> -->
													<div class="flex items-center justify-center text-2xs mr-4">
														<i :class="'fa fa-' + property.icon + ' vfa-icon-preview'"></i>
													</div>
													<div class="w-full items-center justify-center ">{{ property.label
													}}</div>

													<div class="flex cursor-pointer">
														<a class="flex text-theme-1" href="#" data-toggle="modal"
															data-target="#header-properties-edit"
															@click.prevent="setProperty(property, navigation)">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
																height="24" viewBox="0 0 24 24" fill="none"
																stroke="currentColor" stroke-width="1.5"
																stroke-linecap="round" stroke-linejoin="round"
																class="feather feather-edit w-4 h-4 mr-1 text-gray-600">
																<path
																	d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
																</path>
																<path
																	d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
																</path>
															</svg>
														</a>
													</div>
												</div>
											</div>
										</div>
									</draggable>
									<div v-if="!navigation.property.length" class="flex items-center"
										style="max-height: 150px; min-height: 150px; max-width:300px; min-width: 300px;">
										<div class="mx-auto text-center">
											<div class="overflow-hidden">
												<img alt="No-Result" class="rounded-md w-20 h-20"
													src="images/no-result.png">
											</div>
											<div class="">
												<div class="font-medium">No Properties.</div>
											</div>
										</div>
									</div>

									<div class="flex w-full px-5 items-center justify-center">
										<a href="#" data-toggle="modal" data-target="#add-column"
											@click.prevent="setNavigation(navigation)"
											class="zoom-in btn border border-dotted border-slate-400 dark:border-darkmode-300 text-slate-500 w-full mt-4 md:mt-0 "><svg
												class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
												viewBox="0 0 512 512">
												<path
													d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
											</svg>
											Properties
										</a>
									</div>
								</div>
							</div>
						</div>
					</draggable>
				</div>
			</div>
		</div>
		<create @created="refreshData"></create>
		<edit v-if="navigation" @updated="refreshData" :navigation="navigation"></edit>
		<add v-if="navigation" @updated="refreshData" :navigation="navigation"></add>
		<edit-property v-if="property" @updated="pushToNavigation" :property="property"></edit-property>
	</div>
</template>
<style scoped>
.del-container {
	position: relative;
	float: right;
}
</style>
<script>
import draggable from 'vuedraggable';
import Create from "./../header/create";
import Edit from "./../header/edit";
import Add from "./../header/add";
import EditProperty from "./../header/edit-property";
export default {
	components: {
		Create,
		Edit,
		draggable,
		Add,
		EditProperty,
	},
	data() {
		return {
			property: null,
			navigation: null,
			navigations: [],
			empty_array: []
		}
	},

	created() {
		this.getHeader();
	},

	computed: {
		'navigationSequenceIds'() {
			return this.navigations.map((navigation) => {
				return navigation.id
			})
		},
		'propertyIds'() {
			return this.navigations.map((navigation) => {
				return navigation.property
			})
		},
	},

	methods: {
		getHeader() {
			axios.get(`/navigations?type=header`)
				.then((response) => {
					this.navigations = response.data;
				})
		},

		updateSequenced() {
			axios.post(`navigations/sequence`, {
				sequence: this.navigationSequenceIds,
			}).then(() => {

				this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Key Visual successfully Change Sequence!',
						config: {
							timeout: 2000,
							showProgressBar: true,
							closeOnClick: false,
							pauseOnHover: true
						}
					}), 1000);
				}));

			});
		},

		deleteNavigation(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Header?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
				.then(confirmed => {
					axios.delete(`navigations/${id}`)
						.then(() => {
							this.$modal.success({
								message: 'You successfully deleted a Header',
								title: 'Deleted'
							});
							// Reload page
							app.$emit('reload');
							this.getHeader();
						})
				})
				.catch();
		},

		setNavigation(navigation) {
			this.navigation = navigation;
		},
		setProperty(property, navigation) {
			this.property = property;
			this.navigation = navigation;
		},
		updateProperty(navigation) {
			this.setNavigation(navigation);
			// console.log(this.navigation.id);
			axios.post(`navigations/properties-sequence`, {
				navigation: this.navigation
			}).then(() => {

				this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Header successfully updated!',
						config: {
							timeout: 2000,
							showProgressBar: true,
							closeOnClick: false,
							pauseOnHover: true
						}
					}), 1000);
				}));

			});
		},
		pushToNavigation(data) {
			this.property.icon = data.icon;
			this.property.label = data.label;
			this.property.link = data.link;
			console.log(data);
			axios.post(`navigations/properties-sequence`, {
				navigation: this.navigation
			}).then(() => {
				this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Property successfully updated!',
						config: {
							timeout: 2000,
							showProgressBar: true,
							closeOnClick: false,
							pauseOnHover: true
						}
					}), 1000);
				}));

			});

			// this.getHeader();
		},

		refreshData() {
			this.getHeader();
		}
	},
}
</script>