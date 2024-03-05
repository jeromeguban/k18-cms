<template>
	<div class="flex flex-col w-full mb-3 pr-1 mt-2">
		<div class=" flex flex-row justify-between">
			<label class="text-2xs font-semibold">{{ label }}
				<span class="text-theme-6">{{required_code}}</span>
			</label>
			<span class="text-theme-6 flex items-center text-2xs" v-if="errorMessage">
				{{ errorMessage }}
			</span>
		</div>
		<input type="text"
			class="border border-solid border-gray-300 focus:border-gray-400 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
			:id="id" :class="customClass" v-model="displayValue" :disabled="disabled || false">
	</div>
</template>
<script>
export default {
	props: ['label', 'required_code', 'id', 'customClass', 'errorMessage', 'value', 'disabled'],

	computed: {
		displayValue: {
			get() {
				return this.value
			},

			set(modifiedValue) {
				this.$emit('input', modifiedValue);
			},
		}
	},


}
</script>