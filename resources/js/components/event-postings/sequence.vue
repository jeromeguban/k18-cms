<template>
	<div>
		<div id="event-posting-sequence" class="modal" tabindex="-1" aria-hidden="true" style="z-index: 400;">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Sequence</h2>
						<button class="btn text-theme-2 hidden sm:flex" @click.prevent="getPostings()">
							<div class="flex flex-row col-span-6 sm:col-span-3 lg:col-span-2 xl:col-span-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="refresh-ccw" data-lucide="refresh-ccw" class="lucide lucide-refresh-ccw stroke-1.5 mx-auto block"><path d="M3 2v6h6"></path><path d="M21 12A9 9 0 0 0 6 5.3L3 8"></path><path d="M21 22v-6h-6"></path><path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"></path></svg>
								<div class="flex flex-row mt-1 ml-1 text-center text-xs">Refresh</div>
							</div> 
						</button>
					</div>
					<form>
						<div class="modal-body p-10">
							<div v-if="postings.length <= 0 && isLoading"
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
							<div class="h-full flex items-center" v-if="postings.length <= 0 && !isLoading">
								<div class="mx-auto text-center">
									<div class="overflow-hidden mx-auto">
										<img alt="No-Result" class="rounded-md w-36 h-36"
											src="images/no-result.png">
									</div>
									<div class="mt-2">
										<div class="font-medium">No Result to Show.</div>
									</div>
								</div>
							</div>
							<draggable v-model="postings" @change="updateSequenced"
								:options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}">
								<div class="border mb-4 border-gray-300 border-solid shadow-md rounded w-full "
									v-for="(posting, index) in postings" :key="posting.posting_id">
									<div class="flex relative">
										<div class="flex p-3 w-full">
											<div class="flex flex-row w-full">
												<div class="w-full flex flex-row justify-left">
													<li class="flex flex-row cursor-pointer list-none item">
														<svg class="fill-current w-5 h-5 mt-1" viewBox="0 0 512 512">
															<path
																d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z" />
														</svg>
														<span
															class="py-2 px-3 rounded-full text-xs bg-theme-1 text-white cursor-pointer font-medium ml-2">
															{{index + 1}}
														</span>
													</li>
													<span class="flex flex-row items-center ml-4 font-medium text-base"> 
														{{ posting.name | limitDescription(70)}}
													</span>
												</div>
												<div class="flex flex-row justify-end">
													<img data-action="zoom" :src="posting.banner" class="h-7 w-7 mt-1"/>
												</div>
											</div>
										</div>
									</div>
								</div>
							</draggable>

						</div>

						<vue-snotify></vue-snotify>
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
<style>
.modal{
	z-index: 500;
}
.zoom-img-wrap{
	z-index: 99999999;
	background:#000000a6;
}
.zoom-overlay{
	z-index: 9999999;
	background:#000000a6;
}
.zoom-img{
	height: 100%;
	width: 100%;
}
</style>
<script>
import draggable from 'vuedraggable';
export default {
	props: {
        reset_sequence: {
            type: Boolean,
            default: false
        },
    },
	components: {
		draggable
	},
	data() {
		return {
			posting: null,
			postings: [],
			isLoading : false
		}
	},
	computed : {
		'postingSequencedIds'() {
			return this.postings.map((posting) => { return posting.posting_id})
		}
	},
	watch: {
		'reset_sequence' () {
			this.getPostings();
		}
    },
	created() {
		this.getPostings();
	},
	methods: {
		getPostings() {
			this.postings = [];
			this.isLoading = true;
			axios.get(`events/${this.$route.params.id}/postings`)
			.then(({data}) => {
				this.isLoading = false;
				this.postings = data;
			}).catch({});
		},
		updateSequenced() {
			axios.post(`event-postings/sequence`, {
				sequence: this.postingSequencedIds,
			}).then(response => {
			//   this.getPostings()
			});
		},
	}
}
</script>

