<template>
	<li class="list-none flex flex-col w-full">
		<div class="flex flex-1 justify-between items-center hover:bg-gray-100 px-4 transition-all cursor-pointer" @click="toggleShow">
			<div class="flex py-4">
				<img :src="'svgs/' + icon + '.svg'" class="w-4 h-4 mr-2" alt="">
				<span class="font-semibold">{{ title }}</span>
			</div>
			<div class="flex">
				<svg class="fill-current w-4 h-4 dropdown-menu" :class="{'rotate-90' : showMenu}" viewBox="0 0 256 512"><path d="M119.5 326.9L3.5 209.1c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0L128 287.3l100.4-102.2c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L136.5 327c-4.7 4.6-12.3 4.6-17-.1z"/></svg>
			</div>
		</div>
	    <ul class="pl-6 overflow-hidden dropdown-menu font-normal" :class="this.class">
	      <slot></slot>
	    </ul>
	 </li>
</template>
<script>
export default {
	props: ['icon', 'title', 'height'],
	data() {
		return {
			showMenu: false,
		}
	},
	computed: {
		class() {
			return this.showMenu ? this.height : 'max-h-0'
		}
	},
	methods: {
		toggleShow() {
			this.showMenu = !this.showMenu;
		}
	}
}
</script>