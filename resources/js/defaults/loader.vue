<template>
<div class="loader" v-if="isLoading">
	<div class="loader--dot"></div>
	<div class="loader--dot"></div>
	<div class="loader--dot"></div>
	<div class="loader--dot"></div>
	<div class="loader--dot"></div>
	<div class="loader--dot"></div>
	<div class="loader--text" v-if="!textHidden"></div>
</div>
</template>
<script>
export default {
	props: {
		isLoading: {
			type: Boolean,
			default: false,
		},
		textHidden: {
			type: Boolean,
			default: false,
		}
	},
}
</script>