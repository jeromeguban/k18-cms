<template>
<div class="w-full">
	<div class="flex-grow overflow-y-auto">

			<div class="bg-white rounded-sm p-5 table-box-shadow">
			 <div class="text-lg text-black font-semibold leading-loose">Store Banner</div>
				<div class="w-full">
					<div class="w-full flex overflow-x-scroll flex-wrap">

						<div class="flex relative justify-center items-center px-2 mb-4" style="width: 227px;" v-for="(image, index) in images">
							<img :src="image.banner" alt="" class="block max-w-full " style="max-height:120px">
								<button  @click.prevent="deleteBanner()" class="absolute z-70 bottom-0 bg-red-600 hover:bg-red-700 px-3 py-1 text-white mb-2 rounded">Remove</button>

						</div>
					</div>

					<div class="show-content__row">
						<file-upload
							method="POST"
							:url="'/store/'+$route.params.id+'/banners'"/>
					</div>
				</div>
			</div>

	</div>
</div>
</template>
<script>
	export default {
		props: ['store'],
		data() {
			return {
				form: new FormUpload({
					upload: null,
				}),
				images : [],
			}
		},
		created() {
			this.getBanners();
		},
		methods: {
			submit() {
				this.form.post(`/store-banners`)
				.then(()=>{
				});
			},
			getBanners() {
				axios.get('/store/' +this.$route.params.id+ '/banners')
				.then(({data})=>{
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
					axios.delete(`/store/${this.store.id}/banners/${id}`)
					.then(()=>{
				        this.$modal.success({
						 	message: 'You successfully deleted a banner',
						 	title: 'Deleted'
						 });
						// Reload page
					 setTimeout(function(){
	                 	window.location.reload();
	                }, 1000);
					})
					.catch();
				}).catch();
			},
		}
	}
</script>
