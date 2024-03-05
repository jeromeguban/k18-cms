<template>
<div class="flex flex-col w-full mb-3 pr-1">
	<label class="font-medium mb-2">{{ label }}</label>
	<input 
		type="file" 
		:ref="id"
		class="border border-solid border-gray-300 focus:border-gray-400 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1" 
		:id="id" 
		:class="customClass" 
		@change="onInputChange" 
		:multiple="multiple"
		:disabled="disabled" 
		:accept="accept">
	<span class="text-theme-6 my-2 flex items-center text-2xs" v-if="errorMessage">
		{{ errorMessage }}
	</span>
</div>
</template>
<script>
export default {
	props: {
		id: {
			type: String,
			default: null
		},
		name: {
			type: [String, Function],
			required: true,
		},
		label: {
			type: String,
			required: false,
		},
		customClass: {
			type: String,
			default: null,
		},

		errorMessage: {
			type: String,
			default: null
		},
		value: {
			default: () => {
				return null;
			},
		},
		disabled: {
			type: Boolean,
			default: false,
		},
		accept: {
			type: String,
			default: '*'
		},
		multiple: {
			type: Boolean,
			default: false,
		},
	},

	computed: {
	    displayValue:  {
	        get() {
                return this.value                    
	        },

	        set(modifiedValue) {
	            this.$emit('input', modifiedValue);
	        },
	    }
	},	
	methods: {
		onInputChange(e) {
			
			let files = this.multiple ? e.target.files : this.$refs[this.id || this.name].files[0];
			this.$emit('input', files);
		},
	}


}
</script>