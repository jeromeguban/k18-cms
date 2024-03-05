<template>

<div>
	<div class="intro-y box p-5 mt-5">
        <a  v-on:click="$router.go(-2)" class="flex items-center cursor-pointer mb-4">
            <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
            <span class=" ml-1">Back</span>
        </a>
        <div class="text-lg text-black font-semibold leading-loose">Show Details - Tag</div>
        <p class="text-gray-700 leading-loose border-b border-gray-200 pb-2 mb-4">This page contains information about your tag details</p>
        <div class="w-full bg-gray-100 h-px"></div>
        <span class="flex flex-col box rounded border border-gray-200 px-6 py-4 gap-y-2" style="width: fit-content">
            <div class="font-bold leading-loose border-b border-gray-300 border-dotted pb-1 mb-1">Details</div>
            <div class="flex gap-x-6">
                <!-- row must be balance -->
                <!-- maximum of 7-8 rows when adding 2nd column -->
                <!-- 1st column example -->
                <!-- add this class in first column div when adding second column: 'border-r border-gray-200' -->
                <div class="flex flex-col pb-3 gap-y-3 border-r border-gray-200">
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Name</div>
                        <div class="w-96 pr-3">{{ tags.name }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Featured</div>
                        <div class="w-96 pr-3">{{ tags.featured }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Status</div>
                        <div class="w-96 pr-3" v-if="tags.active == 1">Active</div>
                        <div class="w-96 pr-3" v-if="tags.active == 0">Inactive</div>
                    </div>
                </div>
                <!-- 2nd column example -->
				<!-- <div class="flex flex-col pb-3 gap-y-3">
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Address</div>
                        <div class="w-96 pr-3">{{ store.address_line }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Extended Address</div>
                        <div class="w-96 pr-3">{{ store.extended_address }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Contact Number</div>
                        <div class="w-96 pr-3">{{ store.contact_number }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Email</div>
                        <div class="w-96 pr-3">{{ store.email }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Store Type</div>
                        <div class="w-96 pr-3">{{ store.store_type }}</div>
                    </div>
				</div> -->
            </div>
        </span>
    </div>

    <tag-banner
        :tag="tags"/>


</div>

</template>
<script>
    import TagBanner from '../tag-banners';
	export default {
		components: {
            TagBanner
        },
		data() {
			return {
				tags: {}
			}
		},
		created() {
			this.show();
		},
		methods: {
			show() {
				   axios.get('/tags/' + this.$route.params.id)
			        .then((response) => {
			            this.tags = response.data;
			        })
			        .catch((error) => {
			            console.log(error);
			        });
			}
		}
	}
</script>
