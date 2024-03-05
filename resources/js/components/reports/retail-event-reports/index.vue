<template>
    <div class="w-full">
        <table-template :link="link" :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Event Reports</h4>
            </template>

            <template slot="additionals">

                <div class="flex flex-col w-2/5 pr-1 mr-5 ml-5">
                    <label class="text-2xs font-semibold">Event</label>
                    <multiselect
                        v-model="event"
                        :options="events"
                        :custom-label="getEventLabel"
                        name="getEventLabel"
                        customMaxWidth="100%"
                    ></multiselect>
                </div>


                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generate()"> Generate </button>
                </div>
                <div class="flex justify-between items-center py-4 mr-3 ml-5">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="exportEventReport()"> Export </button>
                </div>
            </template>

        </table-template>
    </div>
</template>
<script>
    import TableTemplate from "../../../Table";

    export default {
        components: { TableTemplate },
    	data() {
    		return {
                fields : {
                    event_name        : 'Event Name',
                    starting_time     : 'Starting Time',
                    item_name         : 'Item Name',
                    price             : 'Item Price',
                    ordered_quantity  : 'Ordered Quantity',
                    total             : 'Ordered Total',
                    customer_firstname : 'Customer First Name',
                    customer_lastname  : 'Customer Last Name',
                    email             : 'Customer Email',
                    mobile_no         : 'Customer Mobile no.'
                },
    			event     : null,
    			events    : [],
    			event_id  : '',
    			show        : false,
                link        : '',
                print_link  : '',
                params: {
                    filter     : null,
                    event_id : null,
                },
    		}
    	},

    	created() {
			this.getEvents();
		},

		watch: {
			'event'() {
				this.event_id         = this.event.event_id;
                this.params.event_id  = this.event.event_id;
			},

        },
    	methods: {
    		getEvents() {
				axios.get('/events')
				.then(({data})=>{
					this.events =data;
				});
			},

            getEventLabel({event_name}) {
				return `${event_name}`;
			},

			generate() {
                this.link = 'event-reports/generate'
                app.$emit('reload');
			},

            exportEventReport() {

                let link = 'event-reports/export?'

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
