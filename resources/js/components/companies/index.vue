<template>
	<div class="w-full">
		<table-template link="/companies" :fields="fields" modalIdentifier="#companies-create">
		<template slot="label">
			<h4>List of Companies</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
		  
	    </template>
	    <template slot="id" slot-scope="props">

			<a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#companies-edit"  @click.prevent="setCostType(props.item, props.index)" v-can="'update.cost-type'" >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
			</a>

			<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteCostType(props.item.id)" v-can="'delete.cost-type'">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
			</a>

	    </template>
	  </table-template>
	  	<create></create>
	  	<edit v-if="company"
	  	  :company="company"/>
	</div>
</template>
<script>
import TableTemplate from "../../Table";
import Edit from "./edit";
import Create from "./create";
export default {
	components: { TableTemplate, Create, Edit },	
	data() {
		return {
			fields: {
				company_name		: 'Company Name',
				company_code		: 'Company Code',
				default_commission  : 'Commission (%)',
				id           		: 'Actions'
			},
			company: null,
			index: 0,
		}
	},
	methods: {
		deleteCostType(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Cost Type?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {  
				axios.delete(`/companies/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted a Cost Type',
					 	title: 'Deleted'
					 });
					 // Reload page
				app.$emit('reload');
				})
			})
			.catch();
		},		
	
		setCostType(company, index) {
			console.log(index);
			this.company = company;
			this.index = index;
		},
		clearCostType() {
			this.company = null;
			this.index = 0;
		}
	}
}
</script>