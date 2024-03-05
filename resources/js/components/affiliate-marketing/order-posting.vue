<template>
<div class="w-full">
	<table-template link="/affiliate-marketing/order-postings" :params="params" :fields="fields" modalIdentifier="#affiliate-marketing-create" :isLoading="isLoading" :addNewBtn="false">
		<template slot="label">
			<h4>List of Order Items</h4>
		</template>
		<template slot="additionals">

			<div class="w-80 sm:flex items-center sm:mr-4">
                <label class="w-18 flex-none xl:w-auto xl:flex-initial mr-2">Date Range</label>
                <VueDatePicker class="form-control h-9"
					v-model="date"
					:color="color"
					:min-date="minDate"
					:max-date="maxDate"
					placeholder="From - To"
					range
					range-header-text="From %d To %d"
					range-input-text="From %d To %d"
					fullscreen-mobile
					validate
				/>
            </div>

            <div class="w-80 sm:flex items-center sm:mr-4">
                <label class="w-18 flex-none xl:w-auto xl:flex-initial mr-2">Marketer</label>
                 <multiselect
                        class="mt-2"
                        v-model="marketer"
                        :options="marketers"
                        name="marketers"
                        :custom-label="getMarketerLabel"
                        customMaxWidth="100%"
                  ></multiselect>
            </div>

			<div class="mt-2 md:mt-4 sm:mt-2">
				<button id="tabulator-html-filter-go" type="button" class="btn btn-primary mr-2 w-full sm:w-16" @click.prevent="reset()" >Reset</button>
			</div>

			<div class="mt-2 md:mt-4 sm:mt-2">
				<button id="tabulator-html-filter-go" type="button" class="btn btn-primary mr-2 w-full sm:w-16" @click.prevent="exportData()" >Export</button>
			</div>

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
                referral_code 	: 'Referral Code', 
                name 	        : 'Name',
				description 	: 'Description',
			    quantity        : 'Quantity', 
                price           : 'Price', 
				total_price     : 'Total Price', 
				commission 		: 'Commission %',
                commission_price: 'Commission', 
                status          : 'Status',
                marketer        : 'Marketer',
                created_at      : 'Ordered Date',
			},
			affiliate_marketing: null,
			index: 0,
			date        : new Date(),
			currentDate : new Date(),
			color		: '#1C3FAA',
            isLoading   : false,
            marketer    : null,
            marketers   : [],
            marketer_id: null,
          
            
			params: {
				from		:null,
				to			:null,
				marketer_id :null,
			}
		}
    },

    created(){
		
		this.getMarketers()
		
	},
	computed: {
		minDate() {
			return new Date(
				this.currentDate.getFullYear() - 1,
				this.currentDate.getMonth(),
				this.currentDate.getDate()
			);
		},
		maxDate() {
			return new Date(
				this.currentDate.getFullYear() + 1,
				this.currentDate.getMonth(),
				this.currentDate.getDate(),
			);
		},
	},
	watch: {
		'date'() {
			this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit('reload');
        },

        'marketer'() {
            this.marketer_id = this.marketer ? this.marketer.id : null
            this.params.marketer_id = this.marketer.id ?? null;
            app.$emit('reload');
		},
	},
    methods: {

        getMarketers() {
			axios.get('/affiliate-marketing')
			.then(({data})=>{
				this.marketers = data;
				this.marketer = this.marketers.find((marketer)=>{
					return marketer.id == this.markter_id
				})
			});
        },
        
        getMarketerLabel({ first_name, last_name }) {
			return `${first_name} - ${last_name}`;
        },


		reset() {

		this.params.to      = null;
		this.params.from    = null;
        this.date           = null;
        this.params.marketer_id  = null;
        this.marketer_id    = null;
        this.marketer       = null;
        app.$emit('reload');

		},

		exportData() {

			let link = 'affiliate-marketing/export?'

			let parameters = Object.keys(this.params)
									.filter(parameter => {
										return this.params[parameter]
									})
									.map(parameter => {
										return `${parameter}=${this.params[parameter]}`
									})
									.join("&")

			window.open(link + parameters, "_blank")
		},

	}
}
</script>