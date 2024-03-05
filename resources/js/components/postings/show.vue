<template>
<div>


    <div class="intro-y box p-5 mt-5">
        <a  v-on:click="$router.go(-2)" class="flex items-center cursor-pointer mb-4">
            <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
            <span class=" ml-1">Back</span>
        </a>
        <div class="text-lg text-black font-semibold leading-loose">Show Details - Postings</div>
        <p class="text-gray-700 leading-loose border-b border-gray-200 pb-2 mb-4">This page contains information about your posting details</p>
        <div class="w-full bg-gray-100 h-px"></div>
        <span class="flex flex-col box rounded border border-gray-200 px-6 py-4 gap-y-2" style="width: fit-content">
            <div class="font-bold leading-loose border-b border-gray-300 border-dotted pb-1 mb-1">Details</div>
            <div class="flex gap-x-6">
                <!-- row must be balance -->
                <!-- maximum of 7-8 rows when adding 2nd column -->
                <!-- 1st column example -->
                <!-- add this class in first column div when adding second column: 'border-r border-gray-200' -->
                <div class="flex flex-col pb-3 gap-y-3 border-r border-gray-200" v-if="items">
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Name</div>
                        <div class="w-96 pr-3">{{items.name}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Description</div>
                        <div class="w-96 pr-3">{{items.desc}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Extended Description</div>
                        <div class="w-96 pr-3" v-html="items.extended_desc"></div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Quantity</div>
                        <div class="w-96 pr-3">{{items.quantity}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Category</div>
                        <div class="w-96 pr-3">{{items.category}}</div>
                    </div>
                    <div class="flex leading-loose">
                        <div class="w-52 font-semibold pr-3">Location</div>
                        <div class="w-96 pr-3">{{ items.location ? items.location : '-'}}</div>
                    </div>
                </div>
            </div>
        </span>
    </div>

    <posting-item v-if="items && items.category === 'Retail'"></posting-item>
    <posting-image
	    :posting="items" v-if="items.category === 'Auction' || items.category === 'Whole Sale' || items.category === 'Wholesale' || items.category === 'Negotiated Bid' || items.category === 'Expression of Interest' || items.category == 'International Trade'"/>


</div>
</template>
<script>
import postingItem from '../posting-items/index';
import postingImage from '../posting-image/index';
	export default {
		components: {
			postingItem, postingImage,
		},
		data() {
			return {
				items   : null,
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
                axios.get('/postings/' + this.$route.params.id)
                    .then(({data}) => {
                        this.items = data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
		},
	}
</script>
