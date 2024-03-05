<template>
	<div ref="close" id="order-waybill-create" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Rebook Waybill</h2>
                </div>
                <form class="">
                	<div class="modal-body p-10">
                		<div class="input-form">

                			<div class="flex" v-if="order">
                				<div class="w-full mr-1 box pb-2 pl-2 pt-2 pr-2 overflow-x-hidden overflow-visible" style="max-height: 200px;" >
                					<span><center><b>Order Details</b></center> </span>
									<div class="flex justify-between border-b border-dashed border-gray-300 py-4">
										<span> Order Number : {{ order ? order.order_id : ' ' }} </span>
									</div>
									<div class="flex justify-between border-b border-dashed border-gray-300 py-4">
										<span> Customer : {{ order ? order.customer_name : ' ' }} </span>
									</div>
									<div class="flex justify-between border-b border-dashed border-gray-300 py-4">
										<span> Address : {{ order ? order.address : ' ' }} </span>
									</div>

									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('order_id')" v-html="form.errors.get('order_id')" />
                				</div>

                				<div class="w-full ml-1 box pb-2 pl-2 pt-2 pr-2 overflow-x-hidden overflow-visible" style="max-height: 200px;">
                					<span><center><b>Items</b></center></span>
                					<div>
                						<table class="table table--sm">
                							<tr>
                								<th>Name </th>
                								<th>Length </th>
                								<th>Width </th>
                								<th>Height </th>
                								<th>Amount</th>
                							</tr>
                							<tr v-for="(order_posting, index) in order_postings">
                								<td> {{ getOrderPostingDetails(order_posting.details).name }} </td>
                								<td> {{ getOrderPostingDetails(order_posting.details).length }} </td>
                								<td> {{ getOrderPostingDetails(order_posting.details).width }} </td>
                								<td> {{ getOrderPostingDetails(order_posting.details).height }} </td>
                								<td> {{ order_posting.price }} </td>
                							</tr>
                						</table>
                					</div>
                				</div>
                			</div>

                            <div v-if="order">
                                <div class="flex">
                                    <div class="w-full mr-1">
                                        <label class="form-label w-full flex flex-col sm:flex-row mt-3">
                                            Store Address
                                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                                v-if="form.errors.get('store_address')"
                                                v-html="form.errors.get('store_address')" />
                                        </label>

                                        <multiselect searchable="true" :options="store_addresses" name="store_address"
                                            v-model="store_address" :custom-label="getStoreAddressCustomLabel"
                                            customMaxWidth="100%" />
                                    </div>
                                    <div class="w-full mr-1">
                                        <label class="form-label w-full flex flex-col sm:flex-row mt-3">
                                            Contact Person
                                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.contact_name')" v-html="form.errors.get('from_address.contact_name')" />
                                        </label>
                                        <input v-model="contact_person" type="text" name="contact_name" class="form-control" placeholder="Contact Person">

                                    </div>
                                    <div class="w-full mr-1">
                                        <label class="form-label w-full flex flex-col sm:flex-row mt-3">
                                            Contact Number
                                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.contact_number')" v-html="form.errors.get('from_address.contact_number')" />
                                        </label>
                                        <input v-model="contact_number" type="text" name="contact_number" class="form-control" placeholder="Contact Number">

                                    </div>
                                </div>
                            </div>

                			<div v-if="order">
                				<div class="flex">
                					<div class="w-full mr-1" >
										<label class="form-label w-full flex flex-col sm:flex-row mt-3">
											Pickup Address
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.address1')" v-html="form.errors.get('from_address.address1')" />

											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.city')" v-html="form.errors.get('from_address.city')" />
											
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.province')" v-html="form.errors.get('from_address.province')" />

											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.latitude')" v-html="form.errors.get('from_address.latitude')" />

											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('from_address.longitude')" v-html="form.errors.get('from_address.longitude')" />
										</label>
										<div style="position: relative;">
											<input v-model="pickup_address" type="text" name="pickup_address" class="form-control" placeholder="Pickup Address" disabled>
											<a href="javascript:;" data-toggle="modal" data-target="#mapModal" @click="showMap('pickup')">
												<i  class="fas fa-map-marker-alt" style="position: absolute; right: 0; top: 50%;  right:2% transform: translateY(-50%); font-size: 1.5em;"></i>
											</a>


										</div>
									</div>
									<div class="w-full ml-1">
										<label class="form-label w-full flex flex-col sm:flex-row mt-3">
											Drop off Address
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('to_address')" v-html="form.errors.get('to_address')" />
										</label>
										<div style="position: relative;">
											<input v-model="drop_off_address" type="text" name="drop_off_address" class="form-control" placeholder="Drop off Address" disabled>
											<a href="javascript:;" data-toggle="modal" data-target="#mapModal" @click="showMap('dropoff')">
												<i class="fas fa-map-marker-alt" style="position: absolute; right: 0; top: 50%;  right:2% transform: translateY(-50%); font-size: 1.5em;"></i>
											</a>
										</div>
									</div>
                				</div>
                				<div class="flex items-center" v-if="order.courier_details">
                					<div class="w-full mr-1" >
										<label class="form-label w-full flex flex-col sm:flex-row mt-3">
											Couriers & Shipping Rates
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    			v-if="form.errors.get('courier')" v-html="form.errors.get('courier')" />
										</label>
										<input v-model="courier_detail" type="text" name="courier_detail" class="form-control" disabled>
										<!-- <multiselect v-model="shipping" :options="shippings" name="shippings" :custom-label="getShippingLabel"
											label="getShippingLabel" customMaxWidth="100%"></multiselect> -->
									</div>
									<div class="w-full ml-1 box pb-2 pl-2 pt-2 pr-2">
	                					<span><center><b>Fees</b></center></span>
	                					<div>
	                						<table class="table table--sm">
	                							<tr>
	                								<th>Declared Value </th>
	                								<th>Shipmates Fee </th>
	                								<th>Shipping Fee </th>
	                								<th>Vat </th>
	                								<th>Valuation Fee </th>
	                								<th>Sub Total </th>
	                								<th>Total </th>
	                							</tr>
	                							<tr>
	                								<td> {{ fees.declared_value }} </td>
	                								<td> {{ fees.shipmates_fee }} </td>
	                								<td> {{ fees.shipping_fee }} </td>
	                								<td> {{ fees.vat }} </td>
	                								<td> {{ fees.valuation_fee }} </td>
	                								<td> {{ fees.subtotal }} </td>
	                								<td> {{ fees.total }} </td>
	                							</tr>
	                						</table>
	                					</div>
	                				</div>
								</div>

                			</div>
                		</div>
                	</div>
                	<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
							<button @click.prevent="submit()" class="btn btn-primary w-20" :disabled="!form.pickup_address || !form.drop_off_address || !order_postings">Save</button>
						</div>

                </form>

			</div>

		</div>
		<div  id="mapModal" class="modal" tabindex="-1" aria-hidden="true">
			<Map v-if="type"
				@updated="pushToAddress"
				:type="type"
				:drop_off_address="address"
				:customer_address="contacts.customer_address"
				:store_address="contacts.store_address"
			/>
		</div>
	</div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import Map from './map';
export default {
	components : { Datepicker, Map },
	props : ['order_waybill_ready'],
	data() {
		return {
			form : new Form({
				order_id : '',
				order_postings : [],
				drop_off_address : '',
				pickup_address : '',
				from_address : {
					contact_name : '',
					contact_number : '',
					address1 : '',
					address2 : '',
					city : '',
					province : '',
					country : '',
					zip : '',
					landmark : '',
					remarks : '',
					latitude : '',
					longitude : '',
					// google_places_id : '',
				},
				to_address : {
					contact_name : '',
					contact_number : '',
					address1 : '',
					address2 : '',
					city : '',
					province : '',
					country : '',
					zip : '',
					landmark : '',
					remarks : '',
					latitude : '',
					longitude : '',
					// google_places_id : '',
				},
				courier_service : '',
				amount_cod : 0,
				amount_total : 0,
			}),
			pickup_address : null,
			drop_off_address : null,
			isLoading : false,
			order_id : '',
			order : null,
			orders : [],
			order_posting : null,
			order_postings : [],
			openMap : false,
			type : '',
			shipping : null,
			shippings : [],
			contacts : [],
			address : '',
			courier_detail : '',
			fees : [],
            store_address : null,
            store_addresses : [],
            contact_person: null,
            contact_number: null,
		}
	},

	watch : {
        'contact_person'() {
            this.form.from_address.contact_name = this.contact_person;
        },

        'contact_number'() {
            this.form.from_address.contact_number = this.contact_number;
        },

        'store_address'() {
            this.contact_person = this.store_address.contact_person;
            this.contact_number = this.store_address.contact_number;

            this.form.from_address.contact_name = this.store_address.contact_person;
            this.form.from_address.contact_number = this.store_address.contact_number;
            this.form.from_address.address1 = this.store_address.additional_information;
            this.form.from_address.address2 = this.store_address.barangay;
            this.form.from_address.city = this.processCityName(this.store_address.city);
            this.form.from_address.province = this.store_address.province;
            this.form.from_address.country = this.store_address.country;
            this.form.from_address.zip = this.store_address.zipcode;
            // this.form.from_address.google_places_id = this.store_address.google_places_id;
            this.form.from_address.latitude = this.store_address.latitude;
            this.form.from_address.longitude = this.store_address.longitude;
        },

		'order'() {

			if(this.order) {
				this.form.order_id = this.order.order_id;
				this.form.delivery_instruction = this.order.delivery_instructions;
				this.order_id = this.order.order_id;
				this.form.to_address.contact_name = this.order.customer_name;
				this.form.to_address.contact_number = this.order.contact_number;
				this.form.to_address.address1 = this.order.address;
				this.getOrderPostings();
				this.getOrderStoreContactDetails();
				this.getCourierDetails();
			}
		},

		order_waybill_ready: {
			handler(newOrderWaybillReady) {
				this.order = newOrderWaybillReady;
			},
			immediate: true,
		},

		'order_postings'() {
			if(this.order_postings.length > 0) {
				this.updateFormWithOrderPostings();
			}
		},

		drop_off_address(newAddress, oldAddress) {
		    if (newAddress) {
				this.form.drop_off_address = this.drop_off_address;
		    }
	  	},
	  	pickup_address(newAddress, oldAddress) {
		    if (newAddress ) {
				this.form.pickup_address = this.pickup_address;
		    }
	  	},


	},

	created() {
        this.getStoreAddresses();
	},

	methods : {
		submit() {
			this.isLoading = true;
			this.form.post(`/orders/shipmates-waybills`)
			.then((response)=>{
			    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Successfully Created Waybill',
						config: {
							timeout: 2000,
							showProgressBar: true,
							closeOnClick: false,
							pauseOnHover: true
						}
					}), 1000);
				}));
				this.print_link = response.data;
					 // Reload page
				this.closeModal();
				window.location.reload();
				app.$emit('reload');

			})
			.catch((response)=>{
				this.isLoading = false;

			});
		},

		getOrderStoreContactDetails() {
			axios.get(`orders/${this.order.order_id}/store-contacts`)
			.then((response)=>{
				this.contacts = response.data;
				this.pickup_address = response.data.store_address[0].additional_information + ', ' + response.data.store_address[0].barangay + ', ' + response.data.store_address[0].city  + ', ' + response.data.store_address[0].province;
				this.form.from_address.contact_name = response.data.store_address[0].contact_person;
				this.form.from_address.contact_number = response.data.store_address[0].contact_number;
				this.form.from_address.address1 = response.data.store_address[0].additional_information;
				this.form.from_address.address2 = response.data.store_address[0].barangay;
				this.form.from_address.city = this.processCityName(response.data.store_address[0].city);
				this.form.from_address.province = response.data.store_address[0].province;
				this.form.from_address.country = response.data.store_address[0].country;
				this.form.from_address.zip = response.data.store_address[0].zipcode;
				// this.form.from_address.google_places_id = response.data.store_address[0].google_places_id;
				this.form.from_address.latitude = response.data.store_address[0].latitude;
				this.form.from_address.longitude = response.data.store_address[0].longitude;
				// this.contact_person = response.data.store_address[0].contact_person;
				// this.contact_number = response.data.store_address[0].contact_number;

				this.drop_off_address = response.data.customer_address.additional_information + ', ' + response.data.customer_address.barangay + ', ' + response.data.customer_address.city  + ', ' + response.data.customer_address.province;
				this.form.to_address.country = response.data.customer_address.country;
				this.form.to_address.province = response.data.customer_address.province;
				this.form.to_address.zip = response.data.customer_address.zipcode;
				this.form.to_address.address2 = response.data.customer_address.barangay;
				// this.form.to_address.google_places_id = response.data.customer_address.google_places_id;
				this.form.to_address.latitude = response.data.customer_address.latitude;
				this.form.to_address.longitude = response.data.customer_address.longitude;
				this.form.to_address.city = this.processCityName(response.data.customer_address.city);
			})
		},

		processCityName(cityName) {
			const cleanedCityName = cityName.replace(/\(Capital\)/gi, '')
											.replace(/CITY OF/gi, '')
											.replace(/City/gi, '')
											.trim();

			return cleanedCityName;
		},

		getOrderCustomLabel({customer_name, id, email}) {
			return `${id} - ${customer_name} - ${email}`;
		},

		getOrderPostings() {
			axios.get(`/order/${this.order_id}/posting-details`)
			.then((response)=>{
				this.order_postings = response.data;
			})
		},

		getCourierDetails() {
			let courier_details = JSON.parse(this.order.courier_details);

			this.courier_detail = courier_details.courier;
			this.form.courier_service = courier_details.courier_service;
			this.fees = courier_details.fees;
		},

		showMap(type) {
			this.type = type;
			this.openMap = true;
			if(this.type == 'dropoff') {
				this.address = this.order.address
			}

		},

		pushToAddress(address,google_places_id,latitude, longitude) {
			if(this.type == 'pickup'){
				this.pickup_address = address;
				this.form.pickup_address = address;
				// this.form.from_address.address2 = address;
				this.form.from_address.google_places_id = google_places_id;
				this.form.from_address.latitude = latitude;
				this.form.from_address.longitude = longitude;
			}
			if(this.type == 'dropoff') {
				this.drop_off_address = address;
				this.form.drop_off_address = address;
				// this.form.to_address.address2 = address;
				this.form.to_address.google_places_id = google_places_id;
				this.form.to_address.latitude = latitude;
				this.form.to_address.longitude = longitude;
			}

		},



		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

        getStoreAddressCustomLabel({barangay, city, province}) {
            return `${barangay} - ${province} - ${city}`
        },

        getContactPersonCustomLabel({contact_person}) {
            return `${contact_person}`
        },

        getContactNumberCustomLabel({contact_number}) {
            return `${contact_number}`
        },

        getStoreAddresses() {
            axios.get(`/store-addresses`)
            .then((response)=>{
                this.store_addresses = response.data;
            })
        },

		getOrderPostingDetails(details) {
			let detail = JSON.parse(details)

			return detail
		},

		updateFormWithOrderPostings() {
			this.form.order_postings = this.order_postings;

			let cod = this.getCashOnDeliveryStatus(this.order.courier_details);

			if(this.order_postings) {
				this.form.amount_total = this.sumOrderPostings(this.order_postings, 'price', 'quantity');
				console.log(cod);
				if(cod === "Yes") {
					this.form.amount_cod = this.form.amount_total;
				}
				if(cod === "No") {
					this.form.amount_cod = 0;
				}
			}
		},

		getCashOnDeliveryStatus(courierDetails) {
			let codDetails;

			try {
				codDetails = JSON.parse(courierDetails);
			} catch (error) {
				console.error("Error parsing courier details:", error);
				return "No";
			}

			if (codDetails && 'cash_on_delivery' in codDetails) {
				return (codDetails.cash_on_delivery === "true" || codDetails.cash_on_delivery === true) ? "Yes" : "No";
			} else {
				return "No";
			}
		},

		sumOrderPostings(postings, priceProperty, quantityProperty) {
			return postings.reduce((acc, posting) => {
				const price = parseFloat(posting[priceProperty]);
				const quantity = parseInt(posting[quantityProperty], 10);
				return acc + (price * quantity);
			}, 0);
		}


	},
}
</script>
