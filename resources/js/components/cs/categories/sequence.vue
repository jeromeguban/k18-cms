<template>
	<div>
		<div id="category-sequence" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Category Sequence</h2>
					</div>
					<form>
						<div class="modal-body p-10">
							<div v-if="categories.length <= 0"
								class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center">
								<svg width="20" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8">
									<defs>
										<linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
											<stop stop-color="rgb(45, 55, 72)" stop-opacity="0" offset="0%"></stop>
											<stop stop-color="rgb(45, 55, 72)" stop-opacity=".631" offset="63.146%">
											</stop>
											<stop stop-color="rgb(45, 55, 72)" offset="100%"></stop>
										</linearGradient>
									</defs>
									<g fill="none" fill-rule="evenodd">
										<g transform="translate(1 1)">
											<path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)"
												stroke-width="3">
												<animateTransform attributeName="transform" type="rotate" from="0 18 18"
													to="360 18 18" dur="0.9s" repeatCount="indefinite">
												</animateTransform>
											</path>
											<circle fill="rgb(45, 55, 72)" cx="36" cy="18" r="1">
												<animateTransform attributeName="transform" type="rotate" from="0 18 18"
													to="360 18 18" dur="0.9s" repeatCount="indefinite">
												</animateTransform>
											</circle>
										</g>
									</g>
								</svg>

								<div class="text-center text-xs mt-2">Loading ...</div>
							</div>
							<draggable v-model="categories" @change="updateSequenced"
								:options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}">
								<div class="border mb-4 border-gray-300 border-solid shadow-md rounded w-full "
									v-for="(category, index) in categories" :key="category.category_id">
									<div class="flex relative">
										<div class="flex p-6 w-full">
											<div class="flex flex-row w-full">
												<div class="w-full flex flex-row justify-left">
													<li class="flex flex-row absolute cursor-pointer list-none item">
														<svg class="fill-current w-4 h-5" viewBox="0 0 512 512">
															<path
																d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z" />
														</svg>
														<span
															class="py-1 px-2 rounded-full text-xs bg-theme-1 text-white cursor-pointer font-medium ml-2">{{index
															+ 1}}</span>
													</li>

													<span class="text-xs justify-left mt-10"> {{ category.category_name
													}}</span>

												</div>
												<div class="lg:flex flex-row md:flex flex-row mt-6 sm:hidden">
													<div class="flex items-center w-24 mr-8">
														<label class="w-20 text-left text-md mr-2">Enabled:</label>
														<div class="w-24 mt-1">
															<label for="settings" class="cursor-pointer">
																<input type="checkbox"
																	class="show-code form-check-switch mr-0 ml-3"
																	:id="'enable-'+index"
																	:checked="category.active == 1"
																	@change="changeCategoryStatus(category.category_id, index)">
																<label :for="'enable-'+index"
																	class="font-semibold text-gray-600"
																	v-if="category.active == 1"></label>
																<label :for="'enable-'+index"
																	class="font-semibold text-gray-600" v-else></label>
															</label>
														</div>
													</div>
													<div class="flex items-center w-24">
														<label class="w-20 text-left text-md mr-2">Featured:</label>
														<div class="w-24 mt-1">
															<label for="settings" class="cursor-pointer">
																<input type="checkbox"
																	class="show-code form-check-switch mr-0 ml-3"
																	:id="'enable-'+index"
																	:checked="category.featured == 1"
																	@change="changeFeaturedStatus(category.category_id, index)">
																<label :for="'enable-'+index"
																	class="font-semibold text-gray-600"
																	v-if="category.featured == 1"></label>
																<label :for="'enable-'+index"
																	class="font-semibold text-gray-600" v-else></label>
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</draggable>

						</div>

						<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Close</button>
						</div>
						<!-- END: Modal Footer -->
					</form>
				</div>
			</div>
		</div>

	</div>




</template>
<script>
import draggable from 'vuedraggable';
export default {
	components: {
		draggable
	},
	data() {
		return {
			category: null,
			categories: [],
		}
	},
	computed : {
		'categorySequencedIds'() {
			return this.categories.map((category) => { return category.category_id})
		}
	},
	created() {
		this.getCategories();
	},
	methods: {
		getCategories() {
			axios.get('categories?sequence=1')
			.then(({data}) => {
				this.categories = data;
			}).catch({});
		},
		updateSequenced() {
			axios.post(`categories/sequence`, {
				sequence: this.categorySequencedIds,
			}).then(response => {
			//   this.getCategories()
			});
		},

		changeCategoryStatus(id, index) {
			this.categories[index].active = !this.categories[index].active;
			axios.patch(`categories/${id}/status`,{
			    active: this.categories[index].active,
			}).then(({data})=>{
				// this.getCategories()
			})
			.catch((error)=> console.log());
		},


		changeFeaturedStatus(id, index) {
			this.categories[index].featured = !this.categories[index].featured;
			axios.patch(`categories/${id}/featured`,{
			    featured: this.categories[index].featured,
			}).then(({data})=>{
				// this.getCategories()
			})
			.catch((error)=> console.log());
		},

	}
}
</script>

