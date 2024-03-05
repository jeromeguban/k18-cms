<template>
	<div class="w-full">
		<table-template link="/inquiries" :fields="fields" :params="params" modalIdentifier="#" :addNewBtn="false">
			<template slot="label">
			<h4>List of  Sell with Us Inquiries</h4>
		</template>

		<template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
	    </template>

	    <template slot="id" slot-scope="props">
	    	<router-link :to="'/inquiries/' + props.item.id" v-can="'view.inquiry'">
				<a class="flex items-center mr-3 " href="javascript:;">
					<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
				</a>
			</router-link>

			<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteInquiry(props.item.id)" v-can="'delete.inquiry'">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
			</a>
	    </template>
		</table-template>
	</div>
</template>
<script>
import TableTemplate from "../../Table";

export default {
	components: { TableTemplate },
	data() {
		return {
			fields: {
				full_name	 : 'Name',
				email		 : 'Email',
				mobile_no    : 'Mobile No.',
				message   	 : 'Message',
				created_at   : 'Date of Inquiry',
				id           : 'Actions'
			},

		}
	},


	methods: {
		deleteInquiry(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Inquiry?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {
				axios.delete(`/posting-inquiries/${id}`)
				.then(()=>{
			        this.$modal.success({
				 	message: 'Inquiry Successfully Deleted',
				 	title: 'Success'
					 });
					// Reload page
				 app.$emit('reload');
				})
				.catch();
			}).catch();
		},

	}
}
</script>
