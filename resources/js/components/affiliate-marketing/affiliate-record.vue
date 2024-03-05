<template>
	<div class="w-full">
		<table-template :link="'/affiliate-marketing/' + this.$route.params.id + '/record/'  " :fields="fields" :params="params" :addNewBtn="false">
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

			<div class="mt-2 xl:mt-0">
				<button id="tabulator-html-filter-go" type="button" class="btn btn-primary mr-2 w-full sm:w-16" @click.prevent="reset()" >Reset</button>
			</div>

			<div class="mt-2 xl:mt-0">
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
                referral_code          : 'Referral Code',
                name                   : 'Name',
                description            : 'Description',
                // extended_description   : 'Extended Description',
                quantity               : 'Quantity',
                price                  : 'Price',
				total_price            : 'Total Price',
				commission_price       : 'Commission',
				commission             : 'Commission Percentage',
				status            	   : 'Status',
                created_at             : 'Ordered Date',
			},
			index: 0,
			date        : new Date(),
			currentDate : new Date(),
			color       : '#1C3FAA',

			params: {
				from		:null,
				to			:null,
				id          :this.$route.params.id
			}
		}
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
	},
	methods: {
	reset() {


		this.params.to      = null;
		this.params.from    = null;
		this.date           = null;
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
