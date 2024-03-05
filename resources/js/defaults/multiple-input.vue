<template>
<div class="flex flex-col w-full mb-3 pr-1" v-if="format">
	<label class="text-2xs font-semibold mb-5">{{ label }}</label>
	<div class="flex flex-col"  v-for="(item, index) in displayValue" >
		<div class="flex flex-wrap mb-4">
			<span class="w-full">Contact # {{  index + 1 }}</span>
			<div v-for="(column, index) in getColumnNames()" class="w-1/2 pr-1">
				<input type="text" class="border border-solid border-gray-300 px-2 py-2 rounded w-full outline-none flex-0 h-10 mt-1" v-model="item[column.column_name]" :placeholder="column.placeholder" >
			</div>
		</div>
	</div>

	<span class="text-red-500 my-2 flex items-center text-2xs" v-if="errorMessage">
		{{ errorMessage }}
	</span>

	<span class="text-2xs text-blue-600 cursor-pointer underline mt-1 flex items-center" @click="addColumn">
		<svg class="fill-current w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM272 80v80H144V80h128zm122 352H54a6 6 0 0 1-6-6V86a6 6 0 0 1 6-6h42v104c0 13.255 10.745 24 24 24h176c13.255 0 24-10.745 24-24V83.882l78.243 78.243a6 6 0 0 1 1.757 4.243V426a6 6 0 0 1-6 6zM224 232c-48.523 0-88 39.477-88 88s39.477 88 88 88 88-39.477 88-88-39.477-88-88-88zm0 128c-22.056 0-40-17.944-40-40s17.944-40 40-40 40 17.944 40 40-17.944 40-40 40z"/></svg>
		Add
	</span>
</div>
</template>
<script>
export default {
	props: ['label', 'id', 'customClass', 'errorMessage', 'value'],
	data() {
		return {
			count: 1,
			format: null
		}
	},
	computed: {
	    displayValue:  {
	        get: function() {
                return this.value                    
	        },

	        set: function(modifiedValue) {
	            this.$emit('input', modifiedValue);
	        },
	    },
	   
	},	
	created() {
		if(this.value && this.value[0]){
			this.format = JSON.parse(JSON.stringify(this.value[0]));
			return;
		}

		console.error('The v-model must be an array.');
		
	},
	methods: {
		addColumn() {
			this.displayValue.push(this.format);
		},
		 getColumnNames() {
	    	return Object.keys(this.format).map((column_name)=>{
	    		return {
	    			column_name,
	    			placeholder: column_name.replace(/_/g, " ").replace(/\w+/g,(w)=>{
	    			return w[0].toUpperCase() + w.slice(1).toLowerCase();
	    			})
	    		}
	    	});
	    }
	},
}
</script>