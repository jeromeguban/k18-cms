<template>
<div>
	<div class="intro-y box overflow-hidden  mt-16">
		<div class="px-5 sm:px-16 py-10 sm:py-20">
			<a  v-on:click="$router.go(-1)" class="flex cursor-pointer">
				<svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
				<span class=" ml-1">Back</span>		
			</a>
			 
			<div class="mt-4"  v-can="'list-rates.courier'">
				<div class="pl-5 pr-5 pb-5">
                    <div class="bg-white rounded-sm p-5 table-box-shadow">
                        <div class="flex flex-col md:flex-row justify-left items-center">
                            <h4 class="text-lg font-medium"> Courier Classification Rates</h4>
                        </div>
                        <div class="w-1/2 text-lg flex mt-4 ">
                            <span v-if="courier">Courier : {{ courier.name }}</span>
                        </div>
                        <div class="w-1/2 flex mt-6">
                            <div class="lg:flex-col flex w-1/3 mt-4 ">
                                <span class="font-semibold">Origin</span>
                                <multiselect 
                                    v-model="origin" 
                                    :options="classifications"
                                    :custom-label="getClassificationName"
                                    name="origin" 
                                    customMaxWidth="100%"/>
                            </div>
                            <div class="lg:flex-col flex w-1/3 mt-4 ml-6 ">
                                <span class="font-semibold">Destination</span>
                                <multiselect 
                                    v-model="destination" 
                                    :options="classifications"
                                    :custom-label="getClassificationName"
                                    name="destination" 
                                    customMaxWidth="100%"/>
                            </div>
                        </div>

                        <div class="sm:mt-6 mt-12">
                            <div class="flex w-full bg-theme-10">
                                
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Row</span>
                                </div>
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Minimum Weight</span>
                                </div>
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Maximum Weight</span>
                                </div>
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Decrement Weight</span>
                                </div>
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Price</span>
                                </div>
                                <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Increment Price</span>
                                </div>
                                 <div class="flex w-1/4 py-3 pl-3 text-white justify-left items-center">
                                    <span class="font-semibold">Action</span>
                                </div>
                            </div>
                            <draggable 
                            v-model="form.rates"
                            @change="updateOrCreateClassificationRates" 
                            group="rates"
                            :options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}" class="overflow-y-scroll overflow-x-hidden px-2 lg:px-4" style="max-height: 900px;">
                                <div class="flex w-full border-b border-gray-300 border-solid" v-for="(rate, index) in form.rates">
                                    <div class="flex flex-row w-1/4 py-3 pl-3 justify-left items-center">
                                        <li class="flex w-12 item list-none cursor-pointer">
                                            <svg class="fill-current w-4 h-5 cursor-pointer" viewBox="0 0 512 512"><path d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z"/></svg>
                                        </li>
                                         <span class="flex">{{ index + 1 }}</span>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="w-full">
                                            <input 
                                                v-model="rate.minimum_weight"
                                                @keyup="updateOrCreateClassificationRates"
                                                type="number"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-1/2 flex-0 h-10 mt-2">
                                            <span 
                                                class="text-theme-6 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('rates.'+index+'.minimum_weight')" 
                                                v-text="form.errors.get('rates.'+index+'.minimum_weight')"/>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="w-full">
                                            <input 
                                                v-model="rate.maximum_weight"
                                                @keyup="updateOrCreateClassificationRates"
                                                type="number"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-1/2 flex-0 h-10 mt-2">
                                            <span 
                                                class="text-theme-6 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('rates.'+index+'.maximum_weight')" 
                                                v-text="form.errors.get('rates.'+index+'.maximum_weight')"/>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="w-full">
                                            <input 
                                                v-model="rate.decrement_weight"
                                                @keyup="updateOrCreateClassificationRates"
                                                type="number"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-1/2 flex-0 h-10 mt-2">
                                            <span 
                                                class="text-theme-6 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('rates.'+index+'.decrement_weight')" 
                                                v-text="form.errors.get('rates.'+index+'.decrement_weight')"/>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="w-full">
                                            <input 
                                                v-model="rate.price"
                                                @keyup="updateOrCreateClassificationRates"
                                                type="number"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-1/2 flex-0 h-10 mt-2">
                                            <span 
                                                class="text-theme-6 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('rates.'+index+'.price')" 
                                                v-text="form.errors.get('rates.'+index+'.price')"/>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="w-full">
                                            <input 
                                                v-model="rate.increment"
                                                @keyup="updateOrCreateClassificationRates"
                                                type="number"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-1/2 flex-0 h-10 mt-2">
                                            <span 
                                                class="text-theme-6 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('rates.'+index+'.increment')" 
                                                v-text="form.errors.get('rates.'+index+'.increment')"/>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 py-3 pl-3 justify-left items-center">
                                        <div class="flex items-center justify-center text-theme-6 underline hover:no-underline cursor-pointer" @click.prevent="deleteRow(index)">
                                            <svg class="fill-current w-4 h-4 mr-1 mt-1 cursor-pointer" viewBox="0 0 320 512"><path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/></svg>
                                            <span class="cursor-pointer">Remove</span>
                                        </div>
                                    </div>
                                </div>
                            </draggable>

                            <div class="w-full flex justify-center mt-6">
                                <button class="bg-theme-10  w-32 rounded flex justify-center py-2 mt-6 shadow-md" @click.prevent="addRow()">
                                    <svg class="fill-current w-5 h-5 text-white" viewBox="0 0 384 512"><path d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
	import draggable from 'vuedraggable';
	export default {
		components: { draggable },
		data() {
			return {
				origin: null,
				courier: null,
				destination: null,
				classifications: [],
				form: new Form({
					origin_classification_id: null,
					destination_classification_id: null,
					rates: [],
				}, false)
			}
		},

		watch: {
			'origin'() {

				this.form.origin_classification_id = this.origin.id

				if(this.origin && this.destination)
					this.getCourierClassificationRate()

			},
			'destination'() {

				this.form.destination_classification_id = this.destination.id

				if(this.origin && this.destination)
					this.getCourierClassificationRate()

			},
		},
		created() {
			this.getClassifications()
			this.getCourier()
		},
		methods: {
			getClassifications() {
				axios.get('/classifications')
				.then(({data})=>{
					this.classifications = data
				})
				.catch()
			},
			getCourier() {
				axios.get(`/couriers/${this.$route.params.id}`)
				.then(({data})=>{
					this.courier = data
				})
				.catch()
			},
			getCourierClassificationRate() {
				axios.get(`/couriers/${this.$route.params.id}/classification-rates`,{
					params: {
						origin_classification_id: this.origin.id,
						destination_classification_id: this.destination.id,
					}
				})
				.then(({data})=>{
					if(data.length) {
						this.form = new Form(data[0], false)
						this.form.rates = data[0].rates ? JSON.parse(data[0].rates) : []
						return
					} 
					this.form.rates = []
				})
			},
			updateOrCreateClassificationRates() {
				this.form.post(`/couriers/${this.$route.params.id}/classification-rates`)
				.then((data) => {
					this.form.errors.clear()
				});
			},

			getClassificationName({classification_name, classification_type}) {
				return `${classification_name} - ${classification_type}`
			},

			addRow() {
				this.form.rates.push({
					minimum_weight: null,
					maximum_weight: null, 
					decrement_weight: 0, 
					price: 0.00, 
					increment: 0
				})
			},

			deleteRow(index) {
				this.form.rates.splice(index, 1)
				this.updateOrCreateClassificationRates()
			}
		}
	}
</script>