<template>
	<div class="w-full">
		<table-template link="/activity-logs" :addNewBtn="false" :fields="fields" :params="params">
			<template slot="label">
				<h4>List of Activity Logs</h4>
			</template>
            <template slot="additionals">
                <div class="flex flex-row mt-4">
                    <div>
                        <label class="text-3xs font-semibold mt-4 ml-1">From </label>
                        <datepicker v-model="from" input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"></datepicker>
                    </div>
                    <div>
                        <label class="text-3xs font-semibold mt-4 ml-5">To</label>
                        <datepicker v-model="to" input-class="mb-2 border border-solid border-gray-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1 ml-4"></datepicker>
                    </div>
                </div>
            </template>
		    <template slot="name" slot-scope="props">
		    	<span class="font-medium">{{ props.item.name }}</span>
		    </template>
            <template slot="id" slot-scope="props">
				<router-link class="btn btn-link mr-2" :to="'/activity-logs/' + props.item.id" v-can="'view.fees'">
					<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
				</router-link>
		    </template>
            <template slot="id" slot-scope="props">
				<router-link class="btn btn-link mr-2" :to="'/activity-logs/' + props.item.id" v-can="'view.branch'">
					<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
				</router-link>
		    </template>
		  </table-template>

	</div>
</template>

<script>
	import TableTemplate from '../../Table';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';

    export default {
		components: { TableTemplate, Datepicker, moment },

        data(){
            return {

                fields: {
                    description     :   'Description',
                    created_by 		:   'Activity By',
				    created_at 		:   'Activity Date',
                    id              :   'Auction'
                },

                from       : null,
                to         : null,

                params : {
                    from   : null,
				    to     : null,
                }

            }
        },

        watch: {

            'from'() {
                this.params.from = moment(this.from).format('YYYY-MM-DD') ? moment(this.from).format('YYYY-MM-DD') : null;
            },

            'to'() {
			    this.params.to = moment(this.to).format('YYYY-MM-DD') ? moment(this.to).format('YYYY-MM-DD') : null;
                app.$emit('reload');
            },

        },

        methods : {

        }
    }
</script>
