<template>
	<div>
		<div ref="close" id="store-address-create" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Store Address For {{store.store_name}}</h2>
					</div>
					<form class="">
                        <div class="input-form">
                            <div class="modal-body p-10">
                                <div v-if="address.errors.has('store_id')" class="alert alert-secondary show flex items-center mb-2" role="alert">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="alert-octagon" data-lucide="alert-octagon" class="lucide lucide-alert-octagon text-theme-6 w-6 h-6 mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                     <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('store_id')" v-text="address.errors.get('store_id')"></span>
                                     <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('store_id')"> &nbsp; Maximum of One Address per store only.</span>
                                </div>
                                <div class="input-form">
                                    <div class="flex w-full flex-wrap mt-2">
                                        <div class="flex flex-col mt-2 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label class="text-gray-600">Contact Person <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('contact_person')" v-text="address.errors.get('contact_person')"></span>
                                            </div>
                                            <input v-model="address.contact_person" type="text" class="border border-solid border-gray-200 rounded mt-2 outline-none px-4 py-2 text-gray-600" :class="{ 'border-red-600' : address.errors.has('contact_person') }" autocomplete="nope">
                                        </div>

                                        <div class="flex flex-col mt-2 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label class="text-gray-600">Contact Number <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('contact_number')" v-text="address.errors.get('contact_number')"></span>
                                            </div>
                                            <input v-model="address.contact_number" type="text" class="border border-solid border-gray-200 rounded mt-2 outline-none px-4 py-2 text-gray-600" :class="{ 'border-red-600' : address.errors.has('contact_number') }" autocomplete="nope">
                                        </div>

                                        <div class="flex flex-col mt-2 w-full px-1">
                                            <div class="flex flex-row justify-between">
                                                <label class="text-gray-600">Email <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('email')" v-text="address.errors.get('email')"></span>
                                            </div>
                                            <input v-model="address.email" type="email" class="border border-solid border-gray-200 rounded mt-2 outline-none px-4 py-2 text-gray-600" :class="{ 'border-red-600' : address.errors.has('email') }" autocomplete="off">
                                        </div>

                                        <div class="flex flex-col mt-4 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label for="" class="text-gray-600">Select Province <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6"  v-if="address.errors.has('province')" v-text="address.errors.get('province')"></span>
                                            </div>
                                            <multiselect
                                                :options="provinces.data"
                                                v-model="province"
                                                name="province"
                                                label="provDesc"
                                                customMaxWidth="100%"
                                                :error="address.errors.has('province')"
                                                :loading="provinces.loading"/>
                                        </div>

                                        <div class="flex flex-col mt-4 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label for="" class="text-gray-600">Select City <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6"  v-if="address.errors.has('city')" v-text="address.errors.get('city')"></span>
                                            </div>
                                            <multiselect
                                            :options="cities.data"
                                            v-model="address.city"
                                            name="city"
                                            customMaxWidth="100%"
                                            :loading="cities.loading"
                                            :error="address.errors.has('city')"
                                            ></multiselect>
                                        </div>

                                        <div class="flex flex-col mt-2 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label for="" class="text-gray-600">Select Barangay <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6"  v-if="address.errors.has('barangay')" v-text="address.errors.get('barangay')"></span>
                                            </div>
                                            <multiselect
                                            :options="barangays.data"
                                            v-model="address.barangay"
                                            name="barangay"
                                            customMaxWidth="100%"
                                            :error="address.errors.has('barangay')"
                                            :loading="barangays.loading"
                                            ></multiselect>
                                        </div>

                                        <div class="flex flex-col mt-2 w-1/2 px-1">
                                            <div class="flex flex-row justify-between">
                                                <label for="" class="text-gray-600">Select Zipcode <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6"  v-if="address.errors.has('zipcode')" v-text="address.errors.get('zipcode')"></span>
                                            </div>
                                            <multiselect
                                            :options="zipcodes.data"
                                            v-model="address.zipcode"
                                            name="address.zipcode"
                                            customMaxWidth="100%"
                                            :error="address.errors.has('zipcode')"
                                            :loading="zipcodes.loading"
                                            ></multiselect>
                                        </div>

                                        <div class="flex flex-col mt-2 w-full px-1 mb-0">
                                            <div class="flex flex-row justify-between">
                                                <label for="" class="text-gray-600">Additional Information <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                                <span class="text-xs text-theme-6 mt-1" v-if="address.errors.has('additional_information')" v-text="address.errors.get('additional_information')"></span>
                                            </div>
                                        
                                            <textarea type="text" v-model="address.additional_information" rows="2" class="border border-solid border-gray-200 rounded mt-1 outline-none px-4 py-2 text-gray-600" :class="{ 'border-red-600' : address.errors.has('additional_information') }"></textarea>
                                            
                                        </div>

                                        </div>
                                                                        
                                        <div style="position:relative;z-index:1;" class="mt-4">
                                            <label for="" class="text-gray-600 mt-4">Search Street Name, Building Name <span class="text-theme-6 mt-1 text-xs">*</span></label>
                                            <input v-model="address.google_address" type="text" name="address" class="form-control mt-2" placeholder="Enter Address" ref="autocomplete">
                                            <i class="fas fa-dot-circle fa-fw mt-3" @click="myLocation()"></i>
                                        </div>

                                        <div id="map" ref="map"> 
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
    props: {
        store: {
            type: Object,
            required: true
        }
    },
	data() {
			return {
				address : new Form({
					contact_person:  null,
					contact_number:  null,
                    country: null,
                    province: null,
                    city: null,
                    barangay: null,
                    zipcode: null,
                    additional_information: null,
                    classification_id:  null,
					google_places_id:"",
					latitude: "",
					longitude: "",
					google_address: "",
                    store_id: null,
                    email : null
				}),
                countries: [],
				provinces: {
					data: [],
					loading: true,
				},
				cities: {
					data: [],
					loading: true
				},
				barangays: {
					data: [],
					loading: true
				},
				zipcodes: {
					data: [],
					loading: true
				},
				province: null,
				error: "",
				spinner: false,
                isLoading: false
			}
		},

	 	created() {
			this.getCountries();
            this.getProvinces();
            this.getZipcodes();
            this.myLocation();
		},

        computed: {
                is_philippines: function () {
                    return (this.address.country == 'Philippines') ? true : false;
            }
        },

		mounted() {
             const autocomplete = new google.maps.places.Autocomplete(
                this.$refs["autocomplete"],
                {
                bounds: new google.maps.LatLngBounds(
                    new google.maps.LatLng(45.4215296, -75.6971931)
                ),
                }
            );

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                console.log(place.geometry.location.lat())

                this.getAddressFrom(
                    place.geometry.location.lat(),
                    place.geometry.location.lng()
                );
        
                this.showLocationOnTheMap(
                    place.geometry.location.lat(),
                    place.geometry.location.lng()
                );
            });
        },

		watch: {
            'store'() {
                this.address.store_id = this.store.id
            },

			'province'() {
				if(this.province) {
					this.address.province = this.province
					this.address.city = null
					this.address.barangay = null
					this.address.classification_id = this.province.classification_id
					this.cities.data = []
					this.cities.loading = true
					this.getCities();
				}
			},

			'address.city'() {
				if(this.address.city) {
					this.getBarangays();
				}
			}
		},


	methods: {
            submit() {
              this.isLoading = true;
              this.address.post('/store-addresses')
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Store Successfully Created!',
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

        	getCountries(){
                axios.get('/countries')
                .then(({data})=>{
                    this.countries = data;
                });
            },


			getProvinces(){
	            axios.get('/provinces')
				.then((response) => {
					this.provinces.data = response.data;
					this.provinces.loading = false
				}).catch((error) => {
					console.log(error);
				});
	        },

			getCities() {
				axios.get(`/provinces/${this.address.province}/municipalities`)
				.then((response) => {
					this.cities.data = response.data;
					this.cities.loading = false
				}).catch((error) => {
					console.log(error);
				});
			},

			getBarangays() {
				axios.get(`/municipalities/${this.address.city}/barangays`)
				.then((response) => {
					this.barangays.data = response.data;
					this.barangays.loading = false
				}).catch((error) => {
					console.log(error);
				});
			},

			getZipcodes() {
				axios.get(`/zipcodes`).then((response) => {
					this.zipcodes.data = response.data;
					this.zipcodes.loading = false
				}).catch((error) => {
					console.log(error);
				});
			},

            myLocation() {
                this.spinner = true;
                if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                    this.getAddressFrom(
                        position.coords.latitude,
                        position.coords.longitude
                    );
        
                    this.showLocationOnTheMap(
                        position.coords.latitude,
                        position.coords.longitude
                    );
                    },
                    (error) => {
                    this.error =
                        "Locator is unable to find your address. Please type your address manually.";
                    this.spinner = false;
                    // console.log(error.message);
                    }
                );
                } else {
                this.error = error.message;
                this.spinner = false;
                console.log("Your browser does not support geolocation API ");
                }
            },

            getAddressFrom(lat, long) {
                this.spinner = true
                axios.get( `/api/google-address?latitude=${lat}&longitude=${long}`).then((response) => {
                    if (response.data.error_message) {
                    this.error = response.data.error_message;
                    console.log(response.data.error_message);
                    this.spinner = false
                    } else {
                    this.address.google_address = response.data.results[0].formatted_address;
                    this.address.google_places_id = response.data.results[0].place_id;
                    }
                    this.spinner = false;
                })
                .catch((error) => {
                    this.error = error.message;
                    this.spinner = false;
                    console.log(error.message);
                });
            },
    
            showLocationOnTheMap(latitude, longitude) {
                // Show & center the Map based oon
                const mapElement = document.getElementById('map')
                const map = new google.maps.Map(this.$refs["map"], {
                zoom: 15,
                center: new google.maps.LatLng(latitude, longitude),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                });

                this.address.latitude = latitude;
                this.address.longitude = longitude;
            

                new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: map,
                    dragabble: true,
                });

                // Create a draggable marker
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: map,
                    draggable: true, // Make the marker draggable
                });

                // Add an event listener for when the marker is dragged
                marker.addListener("dragend", (event) => {
                const newLat = event.latLng.lat();
                const newLng = event.latLng.lng();

                // Update the address and show the new location on the map
                this.getAddressFrom(newLat, newLng);
                this.showLocationOnTheMap(newLat, newLng);
                });

            },


            closeModal() {
                setTimeout(() => this.$refs.close.click(), 3000);
            },

	}
}
</script>
<style>
  .ui.button,
  
  .dot.circle.icon {
    background-color: #ff5a5f;
    color: white;
  }
  
  .pac-icon {
    display: none;
  }
  
  .pac-item {
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
  }
  
  .pac-item:hover {
    background-color: #ececec;
  }
  
  .pac-item-query {
    font-size: 16px;
  }
  
  .input-form {
    position: relative;

  }

  .input-form input {
    padding-right: 35px;
  }

  .input-form i {
    position: absolute;
    top: 70%;
    right: 10px;
    transform: translateY(-50%);
    display: inline-block;
  }

  .input-form i:hover {
    cursor: pointer;
    color: #ff5a5f;
  }

  .pac-container {
	z-index: 100000;
  }

  #map {
	height: 380px;
    margin-top: 31px;
  }
	
</style>

