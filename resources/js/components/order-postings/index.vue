<template>
	<div class="w-full">
		<table-template :link="'/order/' + this.$route.params.id + '/postings/'" :fields="fields" :addNewBtn="false">
		<template slot="label">
			<h4>List of Order Posting</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
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
                description 		: 'Description',
                extended_description: 'Extended Description',
			},
			index: 0,
		}
	},
	methods: {


	}
}
</script>
