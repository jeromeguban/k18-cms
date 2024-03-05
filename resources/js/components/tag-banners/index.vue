<template>
	<div class="intro-y box overflow-hidden mt-5">
		<div class="px-5 sm:px-16 py-10 sm:py-20">

			<div class="bg-white rounded-sm p-5 table-box-shadow">
				<h4>Tag Banner</h4>

				<div class="w-full">
					<div class="w-full flex overflow-x-scroll flex-wrap">

						<div class="flex relative justify-center items-center px-2 mb-4" style="width: 227px;"
							v-for="(image, index) in images">

							<img :src="image.banner" alt="" class="block max-w-full " style="max-height:120px">
							<button @click.prevent="deleteBanner()"
								class="absolute z-70 bottom-0 bg-red-600 hover:bg-red-700 px-3 py-1 text-white mb-2 rounded">Remove</button>
						</div>

					</div>

					<div class="show-content__row">
						<file-upload method="POST" :url="'/tags/'+$route.params.id+'/banners'" />
					</div>
				</div>

				<!-- <h4 class="mt-5">Tag Mobile Banner</h4>
                <div class="w-full">
					<div class="w-full flex overflow-x-scroll flex-wrap">

						<div class="flex relative justify-center items-center px-2 mb-4" style="width: 227px;" v-for="(image, index) in images">

							<img :src="'/images/tags/mobile'+image.mobile_banner" alt="" class="block max-w-full " style="max-height:120px">
							<button  @click.prevent="deleteBanner()" class="absolute z-70 bottom-0 bg-red-600 hover:bg-red-700 px-3 py-1 text-white mb-2 rounded">Remove</button>
						</div>

					</div>

					<div class="show-content__row">
                        <file-upload
                            method="POST"
                            :url="'/tags/'+$route.params.id+'/mobile-banners'"/>
					</div>
				</div> -->
			</div>

		</div>
	</div>
</template>
<script>
export default {
	props: ['tags'],
	data() {
		return {
			form: new FormUpload({
				upload: null,
			}),
			images: [],
		}
	},
	created() {
		this.getBanners();
	},
	methods: {
		getBanners() {
			axios.get(`/tags/${this.$route.params.id}/banners`)
				.then(({ data }) => {
					this.images = data;
				})
				.catch();
		},
		deleteBanner(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this banner?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
				.then(confirmed => {
					axios.delete(`/tag/${this.tags.id}/banners/${id}`)
						.then(() => {
							this.$modal.success({
								message: 'You successfully deleted a banner',
								title: 'Deleted'
							});
							// Reload page
							setTimeout(function () {
								window.location.reload();
							}, 1000);
						})
						.catch();
				}).catch();
		},

		// Mobile Banner
		getBanners() {
			axios.get(`/tags/${this.$route.params.id}/mobile-banners`)
				.then(({ data }) => {
					this.images = data;
				})
				.catch();
		},
		deleteBanner(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this mobile banner?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
				.then(confirmed => {
					axios.delete(`/tag/${this.tags.id}/mobile-banners/${id}`)
						.then(() => {
							this.$modal.success({
								message: 'You successfully deleted a mobile banner',
								title: 'Deleted'
							});
							// Reload page
							setTimeout(function () {
								window.location.reload();
							}, 1000);
						})
						.catch();
				}).catch();
		},
	}
}
</script>
