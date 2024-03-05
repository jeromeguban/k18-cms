<template>
	<div class="w-full">
		<table-template :link="link" :fields="fields" :addNewBtn="false" >
		<template slot="label">
			<h4>Payable Items</h4>
		</template>
        <template slot="additionals">
            <div class="sm:flex items-center sm:mr-4">
                <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Store</label>
                <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="store">
                    <option v-for="(store) in stores" :value="store.id">{{store.code}} - {{store.store_name}}</option>
                </select>
            </div>
             <div class="mt-2 xl:mt-0">
				<button id="tabulator-html-filter-go" type="button" class="btn btn-primary w-full sm:w-16" @click.prevent="search()" >Search</button>
			</div>
            <div class="mt-2 ml-2 xl:mt-0">
				<button id="tabulator-html-filter-go" type="button" class="btn btn-primary w-full sm:w-16" @click.prevent="reset()" >Reset</button>
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
                posting_name 	        : 'Name',
				discount_total_amount 	: 'Discount Total Amount',
				sub_total_amount 	    : 'Sub Total Amount',
                total_commission        : 'Total Commission',
                payable_amount 	        : 'Payable Amount',
				total_sold_amount 	    : 'Total Sold Amount',
			},
			payable: null,
            index   : 0,
            link    : '',
            store   : [],
            stores  : [],
            store_id: null,
            
			params : {
				store_id : null,
			}
		}
    },

    watch: {
        'store'() {
            this.params.store_id = this.store ? this.store : null;
		},

    },

    created() {
     
		this.getStore()
	},

    methods: {
        
        getStore() {
            
            this.link = '/payables/' + this.$route.params.id + '/items'
               
			axios.get('/stores')
			.then(({data})=> {
				this.stores = data;
			});
        },

        search() {
            this.link = '/payables/' + this.$route.params.id + '/items?store_id=' + this.params.store_id;
			app.$emit('reload');
        },

        reset() {
            this.link = '/payables/' + this.$route.params.id + '/items';
            app.$emit('reload');
        },
    },

   
}
</script>