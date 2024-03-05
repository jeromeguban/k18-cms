<template>
<div class="w-full">
    <table-template :link="link" :params="params" :fields="fields" modalIdentifier="#event-access-create" addNewBtnName="Add Customer">
        <template slot="label">
            <h4>List of Event Access Requests</h4>
        </template>

        <template slot="additionals">

            <div class="flex flex-col w-full pr-1 mr-2">
                <label class="text-2xs font-semibold">Filter By</label>
                <multiselect v-model="filter" :options="filters" label="name"
                    customMaxWidth="100%"></multiselect>
            </div>

            <div v-if="params.filter=='Event'" class="flex flex-col w-full pr-1 mr-2">
                <label class="text-2xs font-semibold">Event</label>
                <multiselect v-model="event" :options="events" :custom-label="getEventLabel"
                    name="getEventLabel" customMaxWidth="100%"></multiselect>
            </div>

            <div v-if="params.filter=='Customer'" class="flex flex-col w-full pr-1 mr-2">
                <label class="text-2xs font-semibold">Customer</label>
               <select id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-2 sm:w-auto" v-model="params.decrypt">
                    <option v-for="(customer) in customers" :value="customer">{{customer}}</option>
                </select>
            </div>

             <div class="flex flex-row 2xl:w-full mt-2 sm:mt-2">
                <div v-if="params.filter == 'Event' || 'Customer'" class="flex justify-between items-center">
                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary" @click.prevent="reset()" >Reset</button>
                </div>

                <div  v-if="params.filter=='Event'" class="flex justify-between items-center">
                    <button id="tabulator-html-filter-go" type="button" class="btn btn-primary ml-2" @click.prevent="exportEventAccessRequest()" >Export</button>
                </div>
            </div>

        </template>

        <template slot="name" slot-scope="props">
          <span class="font-medium">{{ props.item.name }}</span>
        </template>
        <template slot="id" slot-scope="props">
            <a v-if="!props.item.status || props.item.status == 'Declined'" class="flex items-center mr-3" href="javascript:;" @click.prevent="approve(props.item.id)" v-can="'create.event-access-request'" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Approve
            </a>
            <a v-if="!props.item.status || props.item.status == 'Approved'" class="flex items-center mr-3" href="javascript:;" @click.prevent="decline(props.item.id)" v-can="'create.event-access-request'" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Decline
            </a>
            
            <a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="remove(props.item.id)" v-can="'delete.event-access-request'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
            </a>
        </template>
    </table-template>
    <create></create>
</div>
</template>
<script>
import TableTemplate from "../../Table";
import Create from "./create";
export default {
components: { TableTemplate, Create},
    data() {
        return {
            fields: {
                event_name          : 'Event Name',
                customer_firstname  : 'First Name',
                customer_lastname   : 'Last Name',
                mobile_no           : 'Mobile No.',
                email               : 'Email',
                status              : 'Status',
                evaluated_at        : 'Date Evaluated',
                id                  : 'Actions',
            },
            print_link        : '',
            event  : null,
            events : [],
            filter   : null,
            filters : [
                {
                    name  : 'Event',
                    value : 'Event', 
                },

                 {
                    name  : 'Customer',
                    value : 'Customer', 
                }
            ],

            customers 		: [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Mobile No'
            ],
                
            customer 	: null,

            params : {
				    decrypt     : null,
            },
                
            link        : '',

            params: {
                filter: null,
                event_id: null,
            },
        }
    },

    created() {
		this.getEvents();
	},

    watch: {
        'filter'() {
            this.params.filter = this.filter.value;
            this.getEvents();
        },

        'event'() {
            this.params.event_id = this.event.event_id;
            this.link = '/event-access-requests';
            app.$emit('reload');
        }
    },

    methods : {
        approve(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to Approve this Customer?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Block'
                })
                .then(confirmed => {
                    axios.post(`/events/access-requests/${id}/approved`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You successfully approved a Customer',
                         title: 'Success'
                         });
                        // Reload page
                        app.$emit('reload');
                    })
                   .catch((error)=>{
                       if(error.response.status)
                             if(error.response.data.message = "Call to a member function toArray() on null") {
                                    this.$modal.error({
                                        message: "You Must Create Event Access Request Template First",
                                    })
                             }
                             else {
                                 this.$modal.error({
                                         message:error.response.data.message,
                                    })
                             }
				    })
                });
        },

        decline(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to Decline this Customer?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Block'
                })
                .then(confirmed => {
                    axios.post(`/events/access-requests/${id}/declined`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You successfully declined a Customer',
                         title: 'Success'
                         });
                        // Reload page
                        app.$emit('reload');
                    })
                     .catch((error)=>{
                         if(error.response.status)
                             if(error.response.data.message = "Call to a member function toArray() on null") {
                                    this.$modal.error({
                                        message: "You Must Create Event Access Request Template First",
                                    })
                             }
                             else {
                                 this.$modal.error({
                                         message:error.response.data.message,
                                    })
                             }
				    })
                });
        },

        remove(id) {
            this.$modal.dialog({
                    message: 'Are you sure you want to remove the Request?',
                    confirmButton: 'Okay',
                    cancelButton: 'Cancel',
                    title: 'Delete'
                })
                .then(confirmed => {
                    axios.delete(`/event-access-requests/${id}`)
                    .then(()=>{
                        this.$modal.success({
                         message: 'You Successfully Removed the Request',
                         title: 'Success'
                         });
                        // Reload page
                     app.$emit('reload');
                    })
                    .catch();
                }).catch();
        },

        getEvents() {
            axios.get('/events')
            .then(({data})=>{
                this.events =data;
            });
        },

        getEventLabel({event_name, type}) {
            return `${event_name} - ${type}`;
        },

        reset() {
            this.params.event_id = null;
            this.event = null;
            this.params.filter = null;
            this.filter = null;
            app.$emit('reload');
         },

         exportEventAccessRequest() {
                let print_link = 'export-event-access-requests?'

                let parameters = Object.keys(this.params)
                                        .filter(parameter => {
                                            return this.params[parameter]
                                        })
                                        .map(parameter => {
                                            return `${parameter}=${this.params[parameter]}`
                                        })
                                        .join("&")

                window.open(print_link + parameters , "_blank")
         },
    },

}
</script>
