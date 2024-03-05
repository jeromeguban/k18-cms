<template>
<div>
    <h2 class="intro-y text-lg font-medium mt-10"> Quicklinks </h2>

    <div class="intro-y box overflow-hidden mt-5">
        <div class="overflow-x-auto mt-4">
            <div class="box rounded-sm p-5 table-box-shadow">
                <div class="flex flex-col ">
                    <div class="flex items-center mb-12">
                         <div class="flex lg:w-full justify-end md:w-full justify-end sm:w-32 justify-start">
                            <a href="" @click.prevent="" data-toggle="modal" data-target="#quicklink-create"  class="btn btn-primary shadow-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fill-current w-3 h-3 mr-2"><path d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z"></path></svg>
                                Add New
                            </a>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="lg:w-full md:w-full sm:w-32">
                            <draggable
                                v-model="quicklinks"
                                @change="updateSequenced"
                                :options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}" >
                                <div class="border mb-4 border-gray-300 border-solid shadow-md rounded w-full " v-for="(quicklink, index) in quicklinks" :key="quicklink.id">
                                    <div class="flex relative">
                                        <li class="absolute mt-4 ml-4 cursor-pointer list-none item">
                                            <svg class="fill-current w-4 h-5" viewBox="0 0 512 512"><path d="M352.201 425.775l-79.196 79.196c-9.373 9.373-24.568 9.373-33.941 0l-79.196-79.196c-15.119-15.119-4.411-40.971 16.971-40.97h51.162L228 284H127.196v51.162c0 21.382-25.851 32.09-40.971 16.971L7.029 272.937c-9.373-9.373-9.373-24.569 0-33.941L86.225 159.8c15.119-15.119 40.971-4.411 40.971 16.971V228H228V127.196h-51.23c-21.382 0-32.09-25.851-16.971-40.971l79.196-79.196c9.373-9.373 24.568-9.373 33.941 0l79.196 79.196c15.119 15.119 4.411 40.971-16.971 40.971h-51.162V228h100.804v-51.162c0-21.382 25.851-32.09 40.97-16.971l79.196 79.196c9.373 9.373 9.373 24.569 0 33.941L425.773 352.2c-15.119 15.119-40.971 4.411-40.97-16.971V284H284v100.804h51.23c21.382 0 32.09 25.851 16.971 40.971z"/></svg>
                                        </li>

                                        <div class="flex p-12 w-full">
                                            <div class="border border-gray-300 bg-cover bg-center md:image-fit" :style=" 'width: 311px; height: 150px; background-image: url('+quicklink.icon+' ) '">
											</div>
                                            <div class="flex w-full ml-6">
                                                <div class="w-full flex flex-col justify-center">
                                                    <span class="text-lg">{{ quicklink.label }}</span>
                                                    <span class="text-theme-1 underline hover:no-underline cursor-pointer mt-2">{{ quicklink.link }}</span>

                                                    <div class="flex mt-2">
                                                        <!-- <a class="btn btn-link mr-2">
                                                            <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
                                                        </a> -->

                                                        <a class="flex text-theme-1" href="" data-toggle="modal" data-target="#quicklink-edit"   @click.prevent="setQuickLink(quicklink)">
                                                            	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-1 text-gray-600 mr-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>Edit
                                                        </a>
                                                        <!-- <a class="btn btn-link mr-2">
                                                            <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M296 432h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zm-160 0h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zM440 64H336l-33.6-44.8A48 48 0 0 0 264 0h-80a48 48 0 0 0-38.4 19.2L112 64H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h24v368a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V96h24a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8zM171.2 38.4A16.1 16.1 0 0 1 184 32h80a16.1 16.1 0 0 1 12.8 6.4L296 64H152zM384 464a16 16 0 0 1-16 16H80a16 16 0 0 1-16-16V96h320zm-168-32h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8z"/></svg>Delete
                                                        </a> -->
                                                    </div>


                                                </div>

                                                <div class="flex items-center w-24">
                                                    <label class="w-20 text-left text-md mr-2">Enabled:</label>
                                                    <div class="w-24 mt-1">
                                                        <label for="settings" class="cursor-pointer">
                                                            <input type="checkbox"  class="show-code form-check-switch mr-0 ml-3" :id="'enable-'+index" :checked="quicklink.active == 1" @change="changeQuickLinkStatus(quicklink.id, index)">
                                                            <label :for="'enable-'+index" class="font-semibold text-gray-600" v-if="quicklink.active == 1"></label>
                                                            <label :for="'enable-'+index" class="font-semibold text-gray-600" v-else></label>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a @click.prevent="deleteQuickLink(quicklink.id)" class=" bg-theme-1 hover:bg-theme-6 w-16 flex items-center justify-center text-white cursor-pointer">
                                            <svg class="fill-current w-6 h-6" viewBox="0 0 448 512"><path d="M296 432h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zm-160 0h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8zM440 64H336l-33.6-44.8A48 48 0 0 0 264 0h-80a48 48 0 0 0-38.4 19.2L112 64H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h24v368a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V96h24a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8zM171.2 38.4A16.1 16.1 0 0 1 184 32h80a16.1 16.1 0 0 1 12.8 6.4L296 64H152zM384 464a16 16 0 0 1-16 16H80a16 16 0 0 1-16-16V96h320zm-168-32h16a8 8 0 0 0 8-8V152a8 8 0 0 0-8-8h-16a8 8 0 0 0-8 8v272a8 8 0 0 0 8 8z"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </draggable>
                        </div>

                        </div>
                    </div>


					<div v-if="quicklinks.length < 1" class="h-full flex items-center">
                        <div class="mx-auto text-center">
                                <div class="overflow-hidden mx-auto">
                                <img alt="No-Result" class="rounded-md w-36 h-36" src="images/no-result.png">
                            </div>
                            <div class="mt-2">
                                <div class="font-medium">No Result to Show.</div>
                            </div>
                        </div>
                    </div>

                    <create @created="getQuickLinks()"></create>
                    <edit v-if="quicklink" @updated="getQuickLinks()" :quicklink="quicklink"></edit>

        </div>
        </div>
    </div>
</div>




</template>
<script>
import draggable from 'vuedraggable';
import Create from "./create";
import Edit from "./edit";
export default {
	components: {
		draggable, Create, Edit
	},
	data() {
		return {
			quicklink: null,
			quicklinks: [],
		}
	},
	computed : {
		'quickLinkSequenceIds'() {
			return this.quicklinks.map((quicklink) => { return quicklink.id})
		}
	},
	created() {
		this.getQuickLinks();
	},
	methods: {
		getQuickLinks() {
			axios.get('quicklinks')
			.then(({data}) => {
				this.quicklinks = data;
			}).catch({});
		},
		updateSequenced() {
			axios.post(`quicklinks/sequence`, {
				sequence: this.quickLinkSequenceIds,
			}).then(response => {

			});
		},
		changeQuickLinkStatus(id, index) {
			this.quicklinks[index].active = !this.quicklinks[index].active;
			axios.patch(`quicklinks/${id}/status`,{
			    active: this.quicklinks[index].active,
			}).then(({data})=>{})
			.catch((error)=> console.log());
		},

		deleteQuickLink(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this quick link?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {
				axios.delete(`quicklinks/${id}`)
				.then(()=>{
			        this.$modal.success({
					 	message: 'You successfully deleted a quick link',
					 	title: 'Deleted'
					 });
					// Reload page
				 this.getQuickLinks();
				})
			})
			.catch();
		},
		setQuickLink(quicklink){
			this.quicklink = quicklink;
		}
	}
}
</script>
