<template>
	<div class="w-full">
		<table-template link="/customer-registrations" :params="params" :fields="fields" :addNewBtn="false">
		<template slot="label">
			<h4>List of Customer Registrations</h4>
		</template>

	    <template slot="name" slot-scope="props">
	      <span class="font-medium">{{ props.item.customer_firstname }}</span>
	    </template>



		<template slot="search-filter">

            <div class="sm:flex items-center sm:mr-4">
                <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter</label>
                <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="params.decrypt">
                    <option v-for="(filter) in filters" :value="filter">{{filter}}</option>
                </select>
            </div>

	    </template>

	    <template slot="customer_id" slot-scope="props">
             <a v-if="!props.item.verified_date" class="flex items-center mr-3" href="javascript:;" @click.prevent="validate(props.item.id)" v-can="'update.customer'" >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Validate Customer
			</a>
	    </template>

	  </table-template>

	</div>
</template>
 <script>
    import TableTemplate from "../../Table";

    export default {
        components: { TableTemplate},
        data() {
            return {
				fields: {
				customer_firstname 	: 'First Name',
				customer_lastname 	: 'Last Name',
				customer_middlename : 'Middle Name',
                username            : 'Username',
				email          	    : 'Email',
                mobile_no           : 'Phone No.',
				customer_id         : 'Actions',

			},

                customer_registration 		: null,
                customer_registrations 		: [],
                q 				: null,
                filters 		: [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Mobile No'
                ],
                filter 	: null,

                params : {
				    decrypt     : null,
                }
            }
        },

        methods : {
            searchCustomers() {
                axios.get('/customer-registrations',{
                    params: {
                        q: this.q,
                        filter: this.filter,
                        decrypt     : 1,
                    }
                }).then(({data}) => {
                    this.customer_registrations = data;
                }).catch((error) => {
                    console.log(error);
                });
            },

             validate(id) {
                    console.log(id)
                this.$modal.dialog({
                    message: 'Are you sure you want to Validate this Customer?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Validate'
                })
                .then(confirmed => {
                    axios.patch(`/customer-registration/${id}/validate`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'Customer Successfully Validated',
                         title: 'Success'
                         });
                        // Reload page
                        app.$emit('reload');
                    })
                    .catch();
                }).catch();
            },

        }
    }
    </script>
