<template>
	<div class="w-full">
	  <table-template :link="'/store/'+$route.params.id+'/addresses'" :fields="fields" modalIdentifier="#store-address-create">
			<template slot="label">
				<h4>Store Addresses</h4>
			</template>
			<template slot="name" slot-scope="props">
			<span class="font-medium">{{ props.item.name }}</span>
			
			</template>
			<template slot="id" slot-scope="props">

				<a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#store-address-edit"  @click.prevent="setCost(props.item, props.index)" v-can="'update.store-address-type'" >
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
				</a>

				<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteStoreAddress(props.item.address_id)" v-can="'delete.store-address'">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
				</a>

			</template>
	   </table-template>
	  	<create v-if="store" :store="store"/>
	  	<edit v-if="store && store_address" :store_address="store_address"/>
	</div>
</template>
<script>
import TableTemplate from "../../Table";
import Edit from "./edit";
import Create from "./create";
export default {
     props: {
        store: {
            type: Object,
            default: null
        },
    },
	components: { TableTemplate, Create, Edit},	
	data() {
		return {
			fields: {
				contact_person	        : 'Contact Person',
				email                   : 'Email',
				province			    : 'Province',
				city  	     	        : 'City',
                barangay  	     	    : 'Barangay',
                additional_information  : 'Additional Info.',
				id           		    : 'Actions'
			},
			store_address: null,
			index: 0,
		}
	},

    mounted() {
		
        // if (typeof google === "undefined") {
		//     const script = document.createElement("script");
		//     script.type = "text/javascript";
		//     script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyCNB4S8pvuKMZNxlMTM-QCZrHUmcMD75Qw&libraries=places`;
		//     document.head.appendChild(script);
		//   } else {
		//   }
		
    },

    watch: {
        
    },

	methods: {
		deleteStoreAddress(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Store Address?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {  
				axios.delete(`/store-addresses/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted a Store Address',
					 	title: 'Deleted'
					 });
					 // Reload page
				app.$emit('reload');
				})
			})
			.catch();
		},		
	
		setCost(store_address, index) {
			console.log(index);
			this.store_address = store_address;
			this.index = index;
		},
		
		clearCost() {
			this.store_address = null;
			this.index = 0;
		}
	}
}
</script>