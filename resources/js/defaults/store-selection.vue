<template>
<div class="flex flex-col items-center justify-center">
	
	<span class=" items-center text-white font-semibold tracking-wider lg:text-3xl mt-6">SELECT STORE</span>
	                                
	<div class="mt-8 items-center" v-if="stores.length >= 15">
		<div class="input-group form-control-lg">
			<div id="input-group-email" class="input-group-text"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" data-lucide="search" class="lucide lucide-search search__icon dark:text-slate-500"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></div>
			<input v-model="search" type="text" class="form-control" placeholder="Store Name" aria-label="Email" aria-describedby="input-group-email">
		</div>
	</div>

	<div class="flex mt-8 flex-wrap justify-center">

		<div class="flex-col intro-x" v-for="(store, index) in storeList" @click.prevent="setStore(store.id)">
		
			<div :class="'zoom-in mx-4 mb-4 p-4 cursor-pointer bg-theme-' + number[index] + ' bg-opacity-50 w-40 h-40 lg:w-64 lg:h-64 cursor-pointer'">
				<div class="w-full h-full flex items-center justify-center transition" :class="['bg-theme-' + number[index] + ' hover:bg-theme-' + number[index] + ' bg-opacity-70']">
					<div class="flex flex-col  items-center justify-center">
						<span class="uppercase text-center text-2xl lg:text-5xl">{{ store.code }}</span>
						<span class="uppercase text-center mt-4 text-xs lg:text-xs">{{ store.store_name }}</span>
						<span class="uppercase text-center text-xs lg:text-xs">{{store.store_company_code}}</span>
					</diV>
				</div>
			</div>

		</div>
	</div>
	
</div>
</template>
<script>

export default {
	name: 'store-selection',
	props: {
		stores:{
			required: true,
			type: Array
		}
	},
	data() {
		return {
			number: [5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30,
					 5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30,
					 5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,25,28,30
					],	
			user : {},
			search: '',
			store_list: this.stores,
		}
		
	},

	computed: {
		storeList() {
			return this.stores.filter(store => {
				return store.store_name.toLowerCase().includes(this.search.toLowerCase())
			})
		}
	},
	methods: {
		setStore(id) {
			axios.post(`/stores/${id}/validate`)
			.then(()=>{
				window.location.reload();
			});
		},

		logOut() {
			axios.post('/logout')
			.then(()=>{
				window.location.reload();
			});
		},

		
	}
}
</script>





