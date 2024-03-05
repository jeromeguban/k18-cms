<template>
	<div class="w-full">
		<table-template link="/store-inquiries" :fields="fields" :params="params"  modalIdentifier="#"
			:addNewBtn="false">
			<template slot="label">
				<h4>List of Store Inquiries</h4>
			</template>
			<template slot="name" slot-scope="props">
	      		<span class="font-medium">{{ props.item.name }}</span>
		  
	    	</template>
	    	<template slot="additionals">
	    		<div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Date Range</label
                    >
                    <VueDatePicker
                        class="form-control h-9"
                        v-model="date"
                        :color="color"
                        :min-date="minDate"
                        :max-date="maxDate"
                        placeholder="From - To"
                        range
                        range-header-text="From %d To %d"
                        range-input-text="From %d To %d"
                        fullscreen-mobile
                        validate
                    />
                </div>
				<div class="sm:flex items-center sm:mr-4">
					<label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Status</label>
					<select id="tabulator-html-filter-field"
						class="form-select w-full 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="status">
						<option value="Open">Open</option>
						<option value="In Progress">In Progress</option>
						<option value="Answered">Answered</option>
						<option value="On Hold">On Hold</option>
						<option value="Cancelled">Cancelled</option>
						<option value="Closed">Closed</option>
					</select>
				</div>
				<div class="mt-2 xl:mt-0">
					<button id="tabulator-html-filter-go" type="button" class="btn btn-primary w-full sm:w-16"
						@click.prevent="reset()">Reset</button>

					<button
                        id="tabulator-html-filter-go"
                        type="button"
                        class="btn btn-primary w-full sm:w-16"
                        @click.prevent="exportData()"
                    >
                        Export
                    </button>
				</div>
				<div class="mt-2 xl:mt-0">
                    
                </div>
			</template>
	    	<template slot="status" slot-scope="props">
	    		<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'Open'" class=" flex flex-row text-theme-8">
						<span class="w-4 h-4 rounded-full bg-theme-8  text-white"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>

				<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'In Progress'" class=" flex flex-row text-theme-9">
						<span class="w-4 h-4 rounded-full bg-theme-9  text-white"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>

				<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'Answered'" class=" flex flex-row text-theme-7">
						<span class="w-4 h-4 rounded-full bg-theme-7  text-white mr-1"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>

				<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'On Hold'" class=" flex flex-row text-theme-7">
						<span class="w-4 h-4 rounded-full bg-theme-12  text-white"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>

				<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'Cancelled'" class=" flex flex-row text-theme-6">
						<span class="w-4 h-4 rounded-full bg-theme-6  text-white"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>

				<a data-toggle="modal" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<div v-show="props.item.status == 'Closed'" class=" flex flex-row text-theme-10">
						<span class="w-4 h-4 rounded-full bg-theme-10  text-white"></span>
						<span class="font-medium ml-2">{{ props.item.status }}</span>
					</div>
				</a>
	    	</template>

	    	<template slot="slug" slot-scope="props">
				<a v-if="props.item.posting_id" :href="'https://hmr.ph/postings/' + props.item.slug" target="_blank"
					class="font-medium ">
					<u>https://hmr.ph/postings/{{ props.item.slug }}</u>
				</a>

				<a v-if="!props.item.posting_id" class="font-medium text-theme-8">
					<u>N/A</u>
				</a>

			</template>
			
	    	<template slot="id" slot-scope="props">
	    		<router-link :to="'/store-inquiries/' + props.item.id +'?tag=inquiries'" v-can="'view.store-inquiry'">
					<a class="flex items-center mr-3 " href="javascript:;">
						<svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
					</a>
				</router-link>
	    		<a class="flex items-center mr-3 mt-1" href="javascript:;" data-toggle="modal"
					data-target="#change-status" @click.prevent="setStoreInquiry(props.item, props.index)"
					v-can="'update.store-inquiry'">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-width="1" stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
						data-lucide="circle" class="lucide lucide-circle block mx-auto w-4 h-4 mr-1">
						<circle cx="12" cy="12" r="10"></circle>
					</svg>Status
				</a>
				<!-- <a class="flex items-center text-theme-6" href="javascript:;"  @click.prevent="deleteInquiry(props.item.id)" v-can="'delete.bank'">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
				</a> -->
	    	</template>
	    </table-template>
	    <status :store_inquiry="store_inquiry" />
	</div>
</template>
<script>
import TableTemplate from "../../Table";
import Status from "./status";
export default {
	components: { TableTemplate, Status},
	data() {
		return {
			fields: {
				full_name: 'Name',
				mobile_no: 'Mobile No.',
				email: 'Email',
				created_at: 'Date of inquiry',
				message: 'Message',
				status: 'Status',
				slug: 'Item Link',
				// priority: 'Priority',
				id: 'Actions'
			},
			store_inquiry: null,
			index : 0,
			status : null,
			params: {
				status: null,
				from : null,
				to : null,
			},

			date: new Date(),
            currentDate: new Date(),
		}
	},

	computed: {
		minDate() {
            return new Date(
                this.currentDate.getFullYear() - 1,
                this.currentDate.getMonth(),
                this.currentDate.getDate()
            );
        },
        maxDate() {
            return new Date(
                this.currentDate.getFullYear() + 1,
                this.currentDate.getMonth(),
                this.currentDate.getDate()
            );
        },
	},

	watch: {
		'status'() {
			this.params.status = this.status ? this.status : null;
			app.$emit('reload');
		},

		date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
        },
	},

	methods: {
		deleteInquiry(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Inquiry?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {
				axios.delete(`/store-inquiries/${id}`)
					.then(() => {
						this.$modal.success({
							message: 'Inquiry Successfully Deleted',
							title: 'Success'
						});
						// Reload page
						app.$emit('reload');
					})
					.catch();
			}).catch();
		},

		setStoreInquiry(store_inquiry, index) {
			console.log(index);
			this.store_inquiry = store_inquiry;
			this.index = index;
		},

		reset() {
			this.params.status = null;
			this.status = null;
            app.$emit('reload');
		},

		exportData() {
            if(this.params.from && this.params.to){
                let link = 'store-inquiries-export?'

                let parameters = Object.keys(this.params)
                                        .filter(parameter => {
                                            return this.params[parameter]
                                        })
                                        .map(parameter => {
                                            return `${parameter}=${this.params[parameter]}`
                                        })
                                        .join("&")

                window.open(link + parameters, "_blank")
            }
        },

	}
}
</script>
