<template>
<div>


    <div class="intro-y box p-5 mt-5" v-for="store_inquiry in store_inquiries">
        <a  v-on:click="$router.go(-2)" class="flex items-center cursor-pointer mb-4">
            <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
            <span class=" ml-1">Back</span>
        </a>
        <div v-can="'update.store-inquiry'" class="flex justify-between items-center py-4 mr-3 ">
            <a class="flex items-center mr-3 mt-1" href="javascript:;" data-toggle="modal"
                data-target="#change-status" @click.prevent="setStoreInquiry(props.item, props.index)"
                v-can="'update.store-inquiry'">
                <button class="btn btn-primary d-paddingTop10" @click.prevent="">
                    <i class="fa fa-image d-marginRight5"></i>&nbsp; Update Status</button>
            </a>
        </div>
        <div class="text-lg text-black font-semibold leading-loose">Posting Inquiries Details</div>
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
                        <div class="w-52 font-semibold pr-3">Full Name</div>
                        <div class="w-96 pr-3">{{store_inquiry.full_name}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Email</div>
                        <div class="w-96 pr-3">{{store_inquiry.email}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Mobile no.</div>
                        <div class="w-96 pr-3">{{store_inquiry.mobile_no}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Inquiry Details</div>
                        <div class="w-96 pr-3">{{store_inquiry.status}}</div>
                    </div>
                    
                </div>
                <div class="flex flex-col pb-3 gap-y-3">
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Inquired Date</div>
                        <div class="w-96 pr-3">{{store_inquiry.created_at}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Inquired About</div>
                        <!-- <div class="w-96 pr-3">{{items.description}}</div> -->
                        <div class="w-96 pr-3" v-html="store_inquiry.description"></div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Subject</div>
                        <div class="w-96 pr-3">{{store_inquiry.subject}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Message</div>
                        <div class="w-96 pr-3">{{store_inquiry.message}}</div>
                    </div>
                </div>
            </div>
        </span>
         <status :store_inquiry="store_inquiry" />
    </div>



</div>
</template>
<script>
import Status from "./status";
	export default {
        components: { Status},
		data() {
			return {
				store_inquiries   : {},
                index   : 0,
			}
		},
		created() {
		    this.show();
		},

		watch: {
		},


		methods: {
            show() {
                axios.get('/store-inquiries/' + this.$route.params.id)
                    .then(({data}) => {
                        this.store_inquiries = data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
		},
	}
</script>
