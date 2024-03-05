<template>
	<div>
		<div ref="close" id="event-access-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Customer Event Tagging</h2>
					</div>
					<form class="">
						<div class="modal-body p-10">
							<div class="input-form">
                                <div class="w-full">
                                    <span class="font-semibold w-64"> Select Event</span>
                                    <multiselect v-model="event" :options="events" name="customer"
                                        :custom-label='eventLabel' customMaxWidth="100%"></multiselect>
                                </div>

                                <div class="flex flex-col  w-full">
                                    <label class="text-2xs font-semibold">Customer Filter</label>
                                    <select
                                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                        name="filter" v-model="filter">
                                        <option value="Last Name">Last Name</option>
                                        <option value="First Name">First Name</option>
                                        <option value="Email">Email</option>
                                        <option value="Mobile No.">Mobile No.</option>
                                    </select>
                                </div>

                                <div v-if="filter" class="w-full mt-6">
                                    <label class=" font-semibold">Search Customer By {{ filter }}</label>
                                    <div class="flex flex-row items-center">
                                        <input v-on:keyup="searchCustomers" type="text"
                                            class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                            v-model="q">

                                        <button @click.prevent="searchCustomers()" class="btn btn-primary ml-4 w-20">Find</button>
                                    </div>

                                </div>

                                <div v-if="filter" class="w-full mt-6">
                                    <span class="font-semibold w-64"> Select Customer</span>
                                    <multiselect v-model="customer" :options="customers" name="customer"
                                        :custom-label='customerCustomLabel' customMaxWidth="100%"></multiselect>
                                </div>

                                <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                    v-if="form.errors.has('customer_id')"
                                    v-text="form.errors.get('customer_id')"></span>

								
							</div>
						</div>
						<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
							<button @click.prevent="submit()" class="btn btn-primary w-20">Save</button>
						</div>
						<!-- END: Modal Footer -->
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			form : new Form({
				customer_id : '',
				event_id :'',
			}),
			q: null,
            filter: null,
            customer: null,
            customers: [],
			event : null,
			events: [],
			isLoading : false,
		}
	},

	watch : {
		'customer'() {
			this.form.customer_id = this.customer.customer_id;
		},

		'event'() {
			this.form.event_id = this.event.event_id;
		},
	},

	created() {
		this.getEvents();
	},
	methods: {
		submit() {
			this.isLoading = true;
			this.form.post('/event-access-requests')
			.then(() => {
				 this.isLoading = false;
                 this.form.customer_id = null;
                 this.form.event_id = null;
                 this.filter = null;
                 this.q = null;
                 this.event = null;
                 this.customer = null;
                 this.getEvents();
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Customer successfully added to Event!',
						config: {
						timeout: 2000,
						showProgressBar: true,
						closeOnClick: false,
						pauseOnHover: true
						}
					}), 1000);
				}));
				 // Reload page
                this.closeModal();
				app.$emit('reload');
			})
			.catch(()=>{
			this.isLoading = false;
			});
		},

        searchCustomers() {
            axios.get('/customers', {
                params: {
                    q: this.q,
                    filter: this.filter
                }
            }).then(({
                data
            }) => {
                this.customers = data;
            }).catch((error) => {
                console.log(error);
            });
        },

        customerCustomLabel({
            customer_firstname,
            customer_lastname,
            email
        }) {
            return `${customer_lastname}, ${customer_firstname} - ${email}`;
        },

		getEvents() {
			axios.get('/events')
			.then(({data})=>{
				this.events = data;
			});
		},

        eventLabel({event_name}) {
			return `${event_name}`;
		},

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
