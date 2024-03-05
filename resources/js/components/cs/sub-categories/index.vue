<template>
	<div class="w-full">
		<table-template :link="'/categories/' + this.$route.params.id + '/sub-categories/'  " :fields="fields" modalIdentifier="#sub-categories-create">
		<template slot="label">
			<h4>List of Sub Categories</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
		  
	    </template>
	    <template slot="id" slot-scope="props">

	    	<router-link :to="'/sub-categories/' + props.item.sub_category_id +'?tag=settings'" v-can="'view.sub-categories'">
				<a class="flex items-center mr-3 " href="javascript:;">
					<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
				</a>
			</router-link>

			<a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#sub-categories-edit"  @click.prevent="setSubCategory(props.item, props.index)" v-can="'update.sub-categories'" >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
			</a>

			<a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteSubCategory(props.item.sub_category_id)" v-can="'delete.sub-categories'">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
			</a>

	    </template>
	  </table-template>
	  
	  	<create :category="categories"></create>
	  
	  	<edit v-if="sub_category"
	  	  :sub_category="sub_category"/>
	</div>
</template>
<script>
import TableTemplate from "../../../Table";
import Edit from "./edit";
import Create from "./create";
export default {
	components: { TableTemplate, Create, Edit },	
	data() {
		return {
			fields: {
				icon 	        	: 'Icon Name',
				sub_category_code 	: 'Code',
				sub_category_name 	: 'Name', 
				id          		: 'Actions'
			},
			sub_category: null,
			categories:null,
			index: 0,
		}
	},

	created() {
		this.getCategories();
	},

	methods: {
		deleteSubCategory(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Sub Category ?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {  
				axios.delete(`/sub-categories/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted a Sub Category',
					 	title: 'Deleted'
					 });
					 // Reload page
				app.$emit('reload');
				})
			})
			.catch();
		},
				
		setSubCategory(sub_category) {
			this.sub_category = sub_category;
		},
		setSubCategory(sub_category, index) {
			console.log(index);
			this.sub_category = sub_category;
			this.index = index;
		},
		clearSubCategory() {
			this.sub_category = null;
			this.index = 0;
		},

		getCategories() {
			axios.get('/categories/' + this.$route.params.id)
			.then(({data}) => {
				this.categories = data;
			}).catch({});
		},

	}
}
</script>