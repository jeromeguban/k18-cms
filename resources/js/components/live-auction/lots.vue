<template>
    <div class="flex p-4 bg-gray-100 border border-gray-200 rounded cursor-pointer mt-2 w-full" @click.prevent="setActiveLot()">
        <div class="flex items-center justify-center w-28 h-16 bg-gray-300 mr-4">
            <img :src="lot.banner" class="block w-auto max-w-full max-h-16" :alt="lot.name">
        </div>
        <div class="flex flex-col gap-y-1">
            <div class="flex flex-row justify-between">
                <div class="flex flex-col justify-start w-1/2">
                    <span class="italic">Lot #{{ lot.lot_number }}</span>
                    <h3 class="font-semibold">{{ lot.name }}</h3>
                </div>

                <div class="flex flex-col justify-end  w-1/2" v-if="tab=='previous_lots'">
                    <span class="font-bold" :class="status=='Sold' ? 'text-theme-9' : (status=='For Approval' ? 'text-theme-12' : 'text-theme-6')">{{ status }}</span>
                    <h3 class="font-semibold">{{ lot.bid_amount | moneyFormat }}</h3>
                </div>
            </div>
           
            <div class="flex gap-x-2 mt-2">
                <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs">
                    <i class="fa-solid fa-gauge-simple-high mr-1"></i>{{ getAttribute('Odometer') && getAttribute('Odometer').value ? getAttribute('Odometer').value : 'N/A' }}
                </span>
                <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs uppercase">
                    <i class="fa-solid fa-gas-pump mr-1"></i>
                    {{ getAttribute('Fuel Type') && getAttribute('Fuel Type').value ? getAttribute('Fuel Type').value : 'N/A' }}
                </span>
                <span class="border border-gray-300 text-gray-600 rounded-full px-3 py-1 text-xs uppercase">
                    {{ getAttribute('Transmission') && getAttribute('Transmission').value ? getAttribute('Transmission').value : 'N/A' }}
                </span>
            </div>
        </div>
    </div>    
</template>
<script>
export default {
    name: 'lots',
    props: {
		lot: {
			type: Object,
			required: true
		},
		index: {
			type: Number,
			required: true
		},
        tab: {
			type: String,
			required: true
		}
	},

    data() {
		return {
		}
	},

    mounted() {

	},

    computed: {
        'status'() {
            if(this.lot.bid_amount && this.lot.finalized_date && this.lot.for_approval != 1){
                return 'Sold'
            }
            if(this.lot.bid_amount && this.lot.finalized_date && this.lot.for_approval == 1){
                return 'For Approval'
            }
            if(!this.lot.bid_amount || this.lot.bid_amount == 0 && this.lot.finalized_date){
                return 'Pass'
            }
        },
        'attribute_data'() {
            return Array.isArray(this.lot.attribute_data) ? this.lot.attribute_data : JSON.parse(this.lot.attribute_data)
        }
    },
    
    methods: {
        getAttribute(column_name) {
            return this.attribute_data.find((attribute_data)=>{
                return attribute_data.column_name.toLowerCase() == column_name.toLowerCase()
            })
        },
        setActiveLot() {
            this.$emit('setActiveLot', this.lot)
        }
    }
}
</script>