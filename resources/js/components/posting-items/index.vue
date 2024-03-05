<template>
	<div class="w-full">
		<table-template :link="'/posting/'+$route.params.id+'/items/'" :fields="fields" :addNewBtn="false">
		<template slot="label">
			<h4>List of Itemsasd</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
	    </template>


	    <template slot="id" slot-scope="props">
	    	<a class="flex items-center mr-3 mb-2" href="javascript:;" data-toggle="modal" data-target="#posting-items-publish"  @click.prevent="setPostingItem(props.item, props.index)" v-can="'publish.posting-item'" >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send w-4 h-4 mr-2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> {{ !props.item.google_merchant_product_id ? 'Upload to ' : 'Update on'}}  Google Merchant

			</a>

			<a  v-if="!props.item.published_date"  class="flex items-center mr-6 mt-2" href="javascript:;" @click.prevent="publishPosting(props.item.id)"  v-can="'publish.posting-item'" >
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send w-4 h-4 mr-2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Publish
			</a>

			<a  v-if="props.item.published_date" class="flex items-center mr-6 mt-2" href="javascript:;"  @click.prevent="unpublishPosting(props.item.id)"  v-can="'unpublish.posting-item'" >
				 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox w-4 h-4 mr-2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Unpublish
			</a>

	    </template>
	  </table-template>
		<publish v-if="posting_item"
				@publish="clearPostingItem"
				:posting_item="posting_item"/>
	</div>
</template>
<script>
import TableTemplate from "../../Table";
import publish from './publish';
export default {
	components: { TableTemplate, publish },
	data() {
		return {
			fields: {
                upc                    : 'Upc',
                sku                    : 'Sku',
                name                   : 'Name',
                desc                   : 'Description',
                extended_desc          : 'Extended Description',
                quantity               : 'Quantity',
                condition              : 'Condition',
                unit_price             : 'Unit Price',
                discounted_price       : 'Discounted Price',
                suggested_retail_price : 'SRP',
				published_date         : 'Published Date',
                id                     : 'Actions'
			},
			posting_item: null,
			index: 0,
		}
	},
	methods: {

        publishPosting(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to Publish this posting item?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Publish'
			})
			.then(confirmed => {
				axios.patch(`/posting/${id}/items/publish`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully publish a posting item',
					 	title: 'Publish'
					 });
				 // Reload page
				app.$emit('reload');
				})
				.catch((error)=>{
				if(error.response.status)
                    this.$modal.error({
                        message:error.response.data.message,
                    });
			    });
			})
		},

		unpublishPosting(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to unpublish this posting item?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Unpublish'
			})
			.then(confirmed => {
				axios.patch(`/posting/${id}/items/unpublish`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You Unpublish this posting item',
					 	title: 'Unpublish'
					 });
					// Reload page
					app.$emit('reload');
				})
				.catch((error)=>{
                    if(error.response.status)
                    this.$modal.error({
                        message:error.response.data.message,
                    });
			    });
			})
		},

		clearPostingItem() {
			this.posting_item = null;
			this.index = 0;
		},

		setPostingItem(posting_item, index) {
			this.posting_item = posting_item;
			this.index = index;
		},


	}
}
</script>
