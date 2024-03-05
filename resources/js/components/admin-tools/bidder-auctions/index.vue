<template>
	<div class="w-full">
		<table-template link="/banks" :fields="fields" modalIdentifier="#banks-create">
		<template slot="label">
			<h4>List of Banks</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
		  
	    </template>
	    <template slot="id" slot-scope="props">

			<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteBank(props.item.id)" v-can="'delete.bank'">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
			</a>

	    </template>
	  </table-template>
	  	<create></create>
	</div>
</template>
<script>
import TableTemplate from "../../../Table";
import Create from "./create";

export default {
	components: { TableTemplate, Create },
	data() {
		return {
			fields: {
				name  	     : 'Name',
				description  : 'Description',
				id           : 'Actions'
			},
			bank: null,
			index: 0,
		}
	},
	methods: {
		deleteBank(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Bank?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {  
				axios.delete(`/banks/${id}`)
				.then(()=>{
			        this.$modal.success({
				 	message: 'Bank Successfully Deleted',
				 	title: 'Success'
					 });
					// Reload page
				 app.$emit('reload');
				})
				.catch();
			}).catch();
		},
		setBank(bank, index) {
			console.log(index);
			this.bank = bank;
			this.index = index;
		},
		clearBank() {
			this.bank = null;
			this.index = 0;
		}
	}
}
</script>