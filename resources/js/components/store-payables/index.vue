<template>
	<div class="w-full">
		<table-template link="stores/for-payables" :fields="fields" :params="params" :addNewBtn="false">
		<template slot="label">
			<h4>List of Store Payables</h4>
		</template>
	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.name }}</span>
		  
	    </template>
	    <template slot="id" slot-scope="props">

			<router-link  :to="'/store-payables/'+props.item.id+'?from='+params.from+'&to='+params.to+'&company_id='+props.item.company_id+'&store_id='+props.item.id" v-can="'create.payable'">
                <a class="flex items-center mr-3 " href="javascript:;">
                    <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
                </a>
            </router-link>

			<a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#exclude-store-items"  @click.prevent="setStore(props.item, props.index)" v-can="'create.payable'" >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Exclude Items
			</a>

	    </template>
	  </table-template>  
	  <item  v-if="store"
	 	:store="store" :from="from" :to="to"/>
	</div>
</template>
<script>
import TableTemplate from "../../Table";
import Item from "./item";
export default {
    components: { TableTemplate, Item },	
    props:['company_id','from','to'],
	data() {
		return {
            fields: {
                code                 : 'Store Code',
				store_name           : 'Store Name',
				sub_total_amount	 : 'Sub Total Amount',
				discount_total_amount: 'Discount on Total Amount',
				total_sold_amount	 : 'Total Sold Amount',
				total_commission	 : 'Total Commission',
				total_costs  		 : 'Total Costs',
				total_payable_amount : 'Total Payable Amount',
				id           		 : 'Actions'
			},
			payable    : null,
            index	   : 0,
           
			store      : null,

            params: {
                from        : null,
                to          : null,
                company_id  : null,
            },
		}
    },

    created() {
        this.params.from = moment(this.from).format('YYYY-MM-DD');
        this.params.to   = moment(this.to).format('YYYY-MM-DD');
        this.params.company_id = this.company_id;
	},
    
	methods: {
		
		setStore(store, index) {
			console.log(index);
			this.store = store;
			this.index = index;
		},

		clearStore() {
			this.store = null;
			this.index = 0;
		}
	}
}
</script>