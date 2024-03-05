<template>

		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Setup Pin Location</h2>
                </div>
                <form class="">
                	<div class="modal-body p-10">
                		<div class="input-form">
                			<div style="position:relative;z-index:1;">
								<input v-model="address" type="text" name="address" class="form-control" placeholder="Enter Address" ref="autocomplete">
								<i class="fas fa-dot-circle fa-fw" @click="locatorButtonPressed"></i>
							</div>
							<div id="map">

							</div>
					  	</div>

				  	</div>
				  	<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button ref="closeMap" type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
							<button type="button" data-dismiss="modal" @click.prevent="onSubmit()" class="btn btn-primary w-20" :disabled="!address || !latitude || !longitude">Save</button>
						</div>
		  		</form>
	  		</div>
	  </div>

</template>
<script>
export default {
	props: {
		type: {
			type: String,
			required: true,
		},
		drop_off_address: {
			type: String,
			required: true,
		},
		customer_address: {
			type: [Array, Object],
			default: null,
		},
		store_address: {
			type: [Array, Object],
			default: null,
		},
	},

	data() {
		return {
			error : '',
			spinner: false,
			address: "",
			isLoading : false,
			google_places_id:"",
	        latitude: "",
	        longitude: "",
		}
	},

	mounted() {
		if (typeof google === "undefined") {
		    const script = document.createElement("script");
		    script.type = "text/javascript";
		    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyB1Zjeb3B_UappcLJTiItRB2Sw_WimxcQU&libraries=places`;
		    script.onload = () => {
				if(this.type === 'pickup') {
					this.getAddressForm(this.store_address[0].latitude, this.store_address[0].longitude);
					this.showUserLocationOnTheMap(this.store_address[0].latitude, this.store_address[0].longitude)
				}
				if(this.type === 'dropoff') {
					this.getAddressForm(this.customer_address.latitude,this.customer_address.longitude);
					this.showUserLocationOnTheMap(this.customer_address.latitude,this.customer_address.longitude)
				}
		    };
		    document.head.appendChild(script);
		  } else {
			console.log(this.type)
			if(this.type === 'pickup') {
				this.getAddressForm(this.store_address[0].latitude, this.store_address[0].longitude);
				this.showUserLocationOnTheMap(this.store_address[0].latitude, this.store_address[0].longitude)
			}
			if(this.type === 'dropoff') {
				this.getAddressForm(this.customer_address.latitude,this.customer_address.longitude);
				this.showUserLocationOnTheMap(this.customer_address.latitude,this.customer_address.longitude)
			}
		  }
	},

	watch : {
		'type'() {
			if(this.type === 'pickup') {
				this.getAddressForm(this.store_address[0].latitude, this.store_address[0].longitude);
				this.showUserLocationOnTheMap(this.store_address[0].latitude, this.store_address[0].longitude)
			}
			if(this.type === 'dropoff') {
				this.address = this.drop_off_address
				this.getAddressForm(this.customer_address.latitude,this.customer_address.longitude);
				this.showUserLocationOnTheMap(this.customer_address.latitude,this.customer_address.longitude)
			}
		}
	},

	created() {
		// if(this.type === 'pickup') {
		// 	this.getAddressForm(this.store_address[0].latitude, this.store_address[0].longitude);
		// 	this.showUserLocationOnTheMap(this.store_address[0].latitude, this.store_address[0].longitude)
		// }
		// if(this.type === 'dropoff') {
		// 	this.address = this.drop_off_address
		// 	this.getAddressForm(this.customer_address.latitude,this.customer_address.longitude);
		// 	this.showUserLocationOnTheMap(this.customer_address.latitude,this.customer_address.longitude)
		// }
	},

	methods : {
		locatorButtonPressed() {
			if(navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(position => {
					this.getAddressForm(position.coords.latitude , position.coords.longitude);
					this.showUserLocationOnTheMap(position.coords.latitude , position.coords.longitude);
				},
				error => {
					console.log(error.message);
				}
				)
			}

		},

		initAutocomplete() {
			let autocomplete = new google.maps.places.Autocomplete(
		        this.$refs["autocomplete"],
		        {
		          bounds: new google.maps.LatLngBounds(
		            new google.maps.LatLng(45.4215296, -75.6971931)
		          ),
		        }
		      );


		      autocomplete.addListener("place_changed", () => {
		        let place = autocomplete.getPlace();
		  		this.address = place.formatted_address;
		        this.showUserLocationOnTheMap(place.geometry.location.lat(), place.geometry.location.lng())
		      });
		},

		getAddressForm(lat, long) {
			axios.get(`/api/google-address?latitude=${lat}&longitude=${long}`)
			.then(response => {
				if (response.data.error_message) {
	              	console.log(response.data.error_message);
	            } else {
	            	this.address = response.data.results[0].formatted_address;
	            	this.google_places_id = response.data.results[0].place_id;
	            	// console.log(this.address);
	            }
			}).catch(error => {
				console.log(error.message);
			})
		},

		showUserLocationOnTheMap(latitude, longitude) {
			let map = new google.maps.Map(document.getElementById("map"), {
				zoom:15,
				center: new google.maps.LatLng(latitude, longitude),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			this.latitude = latitude;
        	this.longitude = longitude;

			let marker = new google.maps.Marker({
				position: new google.maps.LatLng(latitude, longitude),
				map: map ,
				draggable : true,
			});

			marker.addListener("dragend", (event) => {
				let newLat = event.latLng.lat();
				var newLng = event.latLng.lng();

				// console.log(newLat, newLng);

				this.getAddressForm(newLat, newLng);
          		this.showUserLocationOnTheMap(newLat, newLng);
			})
		},

		closeThisModal() {
			setTimeout(() => this.$refs.closeMap.click(), 100);
		},

		onSubmit() {
			this.$emit('updated', this.address , this.google_places_id , this.latitude , this.longitude);
			this.closeThisModal();
		},
	},
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
    top: 50%;
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
	height: 389px;
    margin-top: 31px;
  }

</style>
