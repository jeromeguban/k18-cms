<template>
	<div class="w-full">
		<table-template link="/apis" :fields="fields" modalIdentifier="#apis-create">
		<template slot="label">
			<h4>List of Api's</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
		  
	    </template>

		<template slot="active" slot-scope="props">

			<div class="flex items-center w-24 mr-6">
				<label v-if="props.item.active == 1" class="w-20 text-left text-md text-theme-9">Active</label>
				<label v-if="props.item.active == 0" class="w-20 text-left text-md text-theme-6">Inactive</label>
				<div class="w-24 mt-1"> 
					<label for="settings" class="cursor-pointer">
						<input type="checkbox"  class="show-code form-check-switch mr-0 ml-3" :id="'enable-'+index" :checked="props.item.active == 1" @change="changeActiveStatus(props.item.id, props.item.active)">
						<label :for="'enable-'+index" class="font-semibold text-gray-600" v-if="props.item.active == 1"></label>
						<label :for="'enable-'+index" class="font-semibold text-gray-600" v-else></label>
					</label>
				</div>
			</div>
	    </template>


	    <template slot="id" slot-scope="props">

			<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteApi(props.item.id)" v-can="'delete.api'">
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
				name 				: 'Name',
				slug 				: 'Slug',
				active          	: 'Status',
				id          		: 'Actions',
			
			},

			active:'',
			apis: null,
			index: 0,
		}
	},

	methods: {
		
		deleteApi(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this api?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {  
				axios.delete(`/apis/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted a api',
					 	title: 'Deleted'
					 });
					 // Reload page
				app.$emit('reload');
				})
			})
			.catch();
		},		
	
		setApi(apis, index) {
			console.log(index);
			this.apis = apis;
			this.index = index;
		},

		clearApi() {
			this.apis = null;
			this.index = 0;
		},

		changeActiveStatus(id,active) {
	
			axios.patch(`apis/${id}/active`,{
			    active: active ,
			}).then(({data})=>{

				app.$emit('reload');
			})
			.catch((error)=> console.log());
		},
	}
}
</script>