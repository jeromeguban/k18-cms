<template>
<div>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Posting Images</h2>
    </div>
    <div class="intro-y box p-5 mt-5">
			<div class="bg-white rounded-sm p-5 table-box-shadow">

				<div class="w-full">
                    <div class="show-contents">
                        <div class="w-full">
                            <form enctype="multipart/form-data">
                                <div class="px-5">
                                    <draggable v-model="photos" @change="updateSequenced()" axis="x" class="flex flex-row grid grid-cols-4"
								        :options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}">
                                        <div class="relative bg-white py-2 px-2 rounded-sm shadow-xl px-5 ml-5 mt-4" v-for="(photo, index) in photos" :key="index">
                                            <div class="max-w-md w-full -shadow-xl rounded-sm p-6 item">
                                                <div class="flex flex-col">
                                                    <div class="">
                                                        <div class="relative">
                                                            <div class="absolute -top-14 -right-14">
                                                                <a href="" @click.prevent="deleteBanner(photo)"
                                                                    class="btn-danger btn-danger-shadow rounded-full h-10 w-10 flex items-center justify-center">
                                                                    <img
                                                                        src="https://img.icons8.com/windows/20/000000/delete.png" />
                                                                </a>
                                                            </div>
                                                            <div class="flex-auto mt-5 flex justify-center">
                                                                <img :src="photo"
                                                                alt="banner" data-action="zoom" class="w-24 h-24 object-fill shadow-lg justify-center items-center">
                                                            </div>
                                                        </div>
                                                        <div class="flex-auto mt-5 flex justify-center">
                                                            <a href="" @click.prevent="setBanner(photo)" class="btn btn-primary btn-primary-shadow rounded-full mt-1">
                                                                Make this Banner
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="max-w-md mx-auto bg-white overflow-visible md:max-w-2xl">
                        <uppy-dashboard :url="'/postings/'+$route.params.id+'/images'" v-if="$route.params.id"/>
                    </div>
				</div>
			</div>

	</div>
</div>
</template>
<script>
import draggable from 'vuedraggable';
	export default {
        props: ['posting'],
        components: {
            draggable
        },
		data() {
			return {
				form: new FormUpload({
					upload: null,
				}),
                images :  Array.isArray(this.posting.images) ? this.images : JSON.parse(this.posting.images)
			}
		},

        computed: {
            'photos': {
                get() {
                    if (this.images) {
                        try {
                            return Array.isArray(this.images) ? this.images : JSON.parse(this.images);
                        } catch (error) {
                            return Object.values(this.images);
                        }
                    }
                    if (!this.images) {
                        return [this.posting.banner];
                    }
                },
                set(newPhotos) {
                    // Update the logic here to handle changes to the photos array
                    this.images = newPhotos;
                }
            },
	    },

		methods: {

            deleteBanner(image) {
                this.$modal.dialog({
                    message: 'Are you sure you want to delete this image?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Delete'
                })
                .then(confirmed => {
                    axios.delete(`postings/${this.$route.params.id}/images?image_name=${image}`)
                    .then(()=>{
                        this.$modal.success({
                            message: 'You Successfully deleted a banner',
                            title: 'Deleted'
                        });
                        window.location.reload()
                    })
                    .catch();
                }).catch();
            },

            setBanner(image) {
                this.$modal.dialog({
                    message: 'Are you sure you want to Set this banner?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Set Banner'
                })
                .then(confirmed => {
                    axios.patch(`/postings/${this.$route.params.id}/images?image_name=${image}`)
                    .then(()=>{
                        this.$modal.success({
                            message: 'You successfully Set this banner',
                            title: 'Set Banner'
                        });

                    })
                    window.location.reload()
                }).catch();
            },

            updateSequenced() {
			axios.patch(`/posting-images/${this.$route.params.id}/sequence`, {
				images: this.photos,
			}).then(response => {
			});
		},
		}
	}
</script>
