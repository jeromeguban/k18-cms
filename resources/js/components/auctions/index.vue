<template>
    <div class="w-full" ref="close">

        <table-template link="/auctions" :fields="fields" :params="params" :addNewBtn="false">
            <template slot="label">
                <h4>List of Auctions</h4>
            </template>

            <template slot="additionals">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Category</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto" v-model="category">
                        <option value="Online Bidding">Online Bidding</option>
                        <option value="Simulcast">Simulcast</option>
                        <option value="Buy Now">Buy Now</option>
                    </select>
                </div>
                <div class="w-64 sm:flex items-center sm:mr-4">
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

                <div class="mt-2 xl:mt-0">
                    <button
                        id="tabulator-html-filter-go"
                        type="button"
                        class="btn btn-primary w-full sm:w-16"
                        @click.prevent="reset()"
                    >
                        Reset
                    </button>
                </div>
            </template>

            <template slot="for_approval" slot-scope="props">
                <div
                    v-if="props.item.for_approval != 1"
                    class="parent-div flex flex-col"
                >
                    <div
                        class="label-div w-full break-words pr-4 font-semibold block"
                    >
                        &nbsp;
                    </div>
                    <div class="content-div flex-1">No</div>
                    <div
                        class="label-div w-full break-words pr-4 font-semibold block"
                    >
                        &nbsp;
                    </div>
                </div>

                <div
                    v-if="props.item.for_approval == 1"
                    class="parent-div flex flex-col"
                >
                    <div
                        class="label-div w-full break-words pr-4 font-semibold block"
                    >
                        &nbsp;
                    </div>
                    <div class="content-div flex-1">Yes</div>
                    <div
                        class="label-div w-full break-words pr-4 font-semibold block"
                    >
                        &nbsp;
                    </div>
                </div>
            </template>

            <template slot="banner" slot-scope="props">
                <span v-if="!props.item.banner" class="font-medium"
                    >No Banner Found</span>

                <img
                    alt="Banner"
                    v-if="props.item.banner"
                    :src="props.item.banner"
                    data-action="zoom"
                    class="w-1/2 h-1/2 my-5 image-fit"/>
            </template>

            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="published_date" slot-scope="props">
                <span class="font-medium text-theme-9" v-if="props.item.published_date">Published</span>
                <span class="font-medium text-theme-6" v-if="!props.item.published_date">Not Published</span>
            </template>

            <template slot="auction_id" slot-scope="props">
                <span class="mr-4 uppercase font-semibold">Actions:</span>
                <router-link
                    :to="'/auctions/' + props.item.auction_id"
                    v-can="'view.auction'">
                    <a class="flex items-center mr-3" href="javascript:;">
                        <svg
                            class="fill-current w-3 h-3 mr-1"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                        >
                            <path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"
                            />
                        </svg>Show
                    </a>
                </router-link>

                <input type="hidden" :id="props.item.slug"
                    :value="'https://hmr.ph/auctions/' + props.item.auction_id + '/details#'">

                <a class="flex items-center mr-3" href="javascript:;" @click.prevent="copyUrl(props.item.slug)">
                    <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/>
                    </svg>Copy Url
                </a>

                <a
                    class="flex items-center mr-3"
                    href="javascript:;"
                    @click.prevent="setToForApproval(props.item.auction_id)"
                    v-can="'auction.for-approval'"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send w-4 h-4 mr-2"
                    >
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    For Approval
                </a>

                <a class="flex items-center my-1"
                    href="javascript:;"
                    data-toggle="modal"
                    data-target="#catalog-pdf"
                    v-if="props.item.category === 'Simulcast'"
                    @click.prevent="setAuction(props.item, props.index)"
                    v-can="'list.auction'" >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-edit w-4 h-4 mr-1 text-gray-600 ml-2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Upload Catalog
                </a>

                <div class="dropdown" v-if="props.item.category == 'Simulcast' && props.item.published_date">

                    <div class="flex items-center mr-3 dropdown-toggle" role="button">
                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M401 438.6l-49.19-30.75C409.88 367.39 448 300.19 448 224 448 100.29 347.71 0 224 0S0 100.29 0 224c0 76.19 38.12 143.39 96.23 183.85L47 438.6a32 32 0 0 0-15 27.14V480a32 32 0 0 0 32 32h320a32 32 0 0 0 32-32v-14.26a32 32 0 0 0-15-27.14zM32 224c0-106 86-192 192-192s192 86 192 192-86 192-192 192S32 330 32 224zm352 256H64v-14.26L127.62 426a221.84 221.84 0 0 0 192.76 0L384 465.74zm0-256a160 160 0 1 0-160 160 160 160 0 0 0 160-160zm-288 0a128 128 0 1 1 128 128A128.14 128.14 0 0 1 96 224zm144-80a16 16 0 0 0-16-16 96.1 96.1 0 0 0-96 96 16 16 0 0 0 32 0 64.07 64.07 0 0 1 64-64 16 16 0 0 0 16-16z"/>
                        </svg>
                        Live Auction Settings
                    </div>
                    <div class="dropdown-menu w-56">
                        <div class="dropdown-menu__content box bg-white-26 dark:bg-dark-6">
                            <div class="p-2">
                                <router-link :to="'/live-auction/' + props.item.auction_id + '/stream-settings'" v-can="'view.auction'">
                                    <a @click="close()" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
                                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M401 438.6l-49.19-30.75C409.88 367.39 448 300.19 448 224 448 100.29 347.71 0 224 0S0 100.29 0 224c0 76.19 38.12 143.39 96.23 183.85L47 438.6a32 32 0 0 0-15 27.14V480a32 32 0 0 0 32 32h320a32 32 0 0 0 32-32v-14.26a32 32 0 0 0-15-27.14zM32 224c0-106 86-192 192-192s192 86 192 192-86 192-192 192S32 330 32 224zm352 256H64v-14.26L127.62 426a221.84 221.84 0 0 0 192.76 0L384 465.74zm0-256a160 160 0 1 0-160 160 160 160 0 0 0 160-160zm-288 0a128 128 0 1 1 128 128A128.14 128.14 0 0 1 96 224zm144-80a16 16 0 0 0-16-16 96.1 96.1 0 0 0-96 96 16 16 0 0 0 32 0 64.07 64.07 0 0 1 64-64 16 16 0 0 0 16-16z"/>
                                        </svg>
                                        Go To Stream Settings
                                    </a>
                                </router-link>

                                <router-link :to="'/live-auction/' + props.item.auction_id" v-can="'view.auction'">
                                    <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
                                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M401 438.6l-49.19-30.75C409.88 367.39 448 300.19 448 224 448 100.29 347.71 0 224 0S0 100.29 0 224c0 76.19 38.12 143.39 96.23 183.85L47 438.6a32 32 0 0 0-15 27.14V480a32 32 0 0 0 32 32h320a32 32 0 0 0 32-32v-14.26a32 32 0 0 0-15-27.14zM32 224c0-106 86-192 192-192s192 86 192 192-86 192-192 192S32 330 32 224zm352 256H64v-14.26L127.62 426a221.84 221.84 0 0 0 192.76 0L384 465.74zm0-256a160 160 0 1 0-160 160 160 160 0 0 0 160-160zm-288 0a128 128 0 1 1 128 128A128.14 128.14 0 0 1 96 224zm144-80a16 16 0 0 0-16-16 96.1 96.1 0 0 0-96 96 16 16 0 0 0 32 0 64.07 64.07 0 0 1 64-64 16 16 0 0 0 16-16z"/>
                                        </svg>
                                        Go To Clerk Controller
                                    </a>
                                </router-link>

                                <router-link :to="'/live-auction/' + props.item.auction_id + '/view'" v-can="'view.auction'">
                                    <a class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
                                        <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M401 438.6l-49.19-30.75C409.88 367.39 448 300.19 448 224 448 100.29 347.71 0 224 0S0 100.29 0 224c0 76.19 38.12 143.39 96.23 183.85L47 438.6a32 32 0 0 0-15 27.14V480a32 32 0 0 0 32 32h320a32 32 0 0 0 32-32v-14.26a32 32 0 0 0-15-27.14zM32 224c0-106 86-192 192-192s192 86 192 192-86 192-192 192S32 330 32 224zm352 256H64v-14.26L127.62 426a221.84 221.84 0 0 0 192.76 0L384 465.74zm0-256a160 160 0 1 0-160 160 160 160 0 0 0 160-160zm-288 0a128 128 0 1 1 128 128A128.14 128.14 0 0 1 96 224zm144-80a16 16 0 0 0-16-16 96.1 96.1 0 0 0-96 96 16 16 0 0 0 32 0 64.07 64.07 0 0 1 64-64 16 16 0 0 0 16-16z"/>
                                        </svg>
                                        Show Display Page
                                    </a>
                                </router-link>



                                <a @click.prevent="pdf(props.item.auction_id)" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
                                   Generate Catalog (Pdf)
                                </a>

                                <a @click.prevent="excel(props.item.auction_id)" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md" href="javascript:;">
                                    Generate Catalog (Excel)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </table-template>

        <upload-catalog v-if="auction" :auction="auction" />
    </div>
</template>
<script>
import TableTemplate from "../../Table";
import UploadCatalog from "./upload.vue";
export default {
    components: { TableTemplate, UploadCatalog },
    props: {
        url: {
			type: String,
			default: null
		},
    },
    data() {
        return {
            fields: {
                banner        : 'Banner',
                auction_number: 'Auction Number',
                name          : 'Name',
                description   : 'Description',
                auction_type  : 'Auction Type',
                category      : 'Category',
                bidding_type  : 'Bidding type',
                for_approval  : 'For Approval',
                starting_time : 'Starting Name',
                ending_time   : 'Ending Time',
                published_date: 'Status',
                auction_id    : 'Actions'
            },

            other_fields: {
                auction_id: "Actions",
            },

            date            : new Date(),
            currentDate     : new Date(),
            color           : '#1C3FAA',
            category        : null,
            auction         : null,
            index           : 0,

            params: {
                category: null,
                from    : null,
                to      : null,
            },
        };
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
        'category'() {
            this.params.category = this.category ?? null;
            app.$emit('reload');
        },

        'date'() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit("reload");
        },
    },

    methods: {
        reset() {

            this.params.category = null;
            this.params.to = null;
            this.params.from = null;
            this.date = null;
            app.$emit("reload");
        },

        copyUrl(slug) {
            let url = document.querySelector(`[id='${slug}']`);
            url.setAttribute("type", "text");
            url.select();

            try {
                var successful = document.execCommand("copy");
                var msg = successful ? "successful" : "unsuccessful";
                alert(" Url was copied " + msg);
            } catch (err) {
                alert("Oops, unable to copy Url");
            }

            /* unselect the range */
            url.setAttribute("type", "hidden");
            window.getSelection().removeAllRanges();
        },

        setToForApproval(id) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to Set this Auction to For Approval?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Publish",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/auctions/${id}/for-approval`)
                        .then(() => {
                            this.$modal.success({
                                message: "You successfully publish a posting",
                                title: "Publish",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch((error) => {
                            if (error.response.status)
                                this.$modal.error({
                                    message: error.response.data.message,
                                });
                        });
                });
        },

        close() {
            setTimeout(() => this.$refs.close.click(), 1000);
        },

        pdf(id){
            window.open(`/auction-catalog/pdf?auction_id=${id}`, "_blank");
        },

        excel(id){
            window.open(`/auction-catalog/excel?auction_id=${id}`, "_blank");
        },

        setAuction(auction, index) {
            this.auction = auction;
            this.index = index;
        },

        clearAuction() {
            this.auction = null;
            this.index = 0;
        },

    }
}

</script>
