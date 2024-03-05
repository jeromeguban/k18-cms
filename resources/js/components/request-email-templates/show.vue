<template>

<div>
	<div class="intro-y box p-5 mt-5">
        <a  v-on:click="$router.go(-2)" class="flex items-center cursor-pointer mb-4">
            <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
            <span class=" ml-1">Back</span>
        </a>
        <div class="text-lg text-black font-semibold leading-loose">Show Details - Order</div>
        <p class="text-gray-700 leading-loose border-b border-gray-200 pb-2 mb-4">This page contains information about your order details</p>
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
                        <div class="w-52 font-semibold pr-3">Auction Number</div>
                        <div class="w-96 pr-3">{{ email.auction_number }}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Email Type</div>
                        <div class="w-96 pr-3">{{ email.type }}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Title</div>
                        <div class="w-96 pr-3">{{ email.title }}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Body</div>
                        <div class="w-96 pr-3">{{ email.body }}</div>
                    </div>
                </div>
                <!-- 2nd column example -->
				<!-- <div class="flex flex-col pb-3 gap-y-3">
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Created By</div>
                        <div class="w-96 pr-3">{{ email.fullname }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Created Date</div>
                        <div class="w-96 pr-3">{{ email.created_at }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Modified By</div>
                        <div class="w-96 pr-3">{{ email.fullname }}</div>
                    </div>
					<div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Modified Date</div>
                        <div class="w-96 pr-3">{{ email.updated_at }}</div>
                    </div>
				</div> -->
            </div>
        </span>
    </div>
</div>

</template>
<script>
	export default {
		components: {},		
		data() {
			return {
				email: {}
			}
		},
		created() {			
			this.show();			
		},
		methods: {
			show() {
				   axios.get('/access-request-email-templates/' + this.$route.params.id)
			        .then((response) => {
			            this.email = response.data;
			        })
			        .catch((error) => {
			            console.log(error);
			        });
			}
		}
	}
</script>