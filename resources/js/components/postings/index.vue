<template>
    <div class="w-full">
        <table-template
            link="/postings"
            :fields="fields"
            :other_fields="other_fields" 
            :params="params"
            modalIdentifier="#postings-create"
        >
            <template slot="label">
                <h4>List of Postings</h4>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="additionals">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Filter type</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="filter_type"
                    >
                        <option value="published_date">Published Date</option>
                        <option value="created_at">Created At</option>
                        <option value="updated_at">Updated At</option>
                    </select>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Category</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="category"
                    >
                        <option value="Auction">Auction</option>
                        <option value="Retail">Retail</option>
                        <option value="Whole Sale">Whole Sale</option>
                        <option value="Expression of Interest">
                            Expression of Interest
                        </option>
                        <option value="International Trade">
                            International Trade
                        </option>
                    </select>
                </div>

                <div
                    v-if="category == 'Retail' || category === 'Auction'"
                    class="sm:flex items-center sm:mr-4"
                >
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Store</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="store"
                    >
                        <option v-if="storeList.length >= 1" value="all">
                            All
                        </option>
                        <option v-for="store in storeList" :value="store.id">
                            {{ store.store_name }}
                        </option>
                    </select>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Status</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="status"
                    >
                        <option value="publish">Published</option>
                        <option value="unpublish">Unpublished</option>
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

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2"
                        >Order By</label
                    >
                    <select
                        id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                        v-model="order_type"
                    >
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
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

            <template slot="banner" slot-scope="props">
                <span v-if="!props.item.banner" class="font-medium"
                    >No Banner Found</span
                >
                <img
                    alt="Banner"
                    v-if="props.item.banner"
                    :src="props.item.banner"
                    data-action="zoom"
                    class="w-1/2 h-1/2 my-5 image-fit"
                />
            </template>

            <template slot="publish_by" slot-scope="props">
                <span
                    v-if="
                        props.item.category != 'Auction' &&
                        props.item.category != 'Expression of Interest' &&
                        props.item.total_published_item != null &&
                        props.item.total_item != null &&
                        props.item.total_published_item.published_count ==
                            props.item.total_item.count
                    "
                    class="font-medium text-theme-9"
                    >{{ props.item.total_published_item.published_count }} of
                    {{ props.item.total_item.count }} Published Item</span
                >
                <span
                    v-if="
                        props.item.category != 'Auction' &&
                        props.item.category != 'Expression of Interest' &&
                        props.item.total_published_item != null &&
                        props.item.total_item != null &&
                        props.item.total_published_item.published_count !=
                            props.item.total_item.count
                    "
                    class="font-medium text-theme-6"
                    >{{ props.item.total_published_item.published_count }} of
                    {{ props.item.total_item.count }} Published Item</span
                >
                <span
                    v-if="
                        props.item.category == 'Auction' ||
                        props.item.category == 'Expression of Interest'
                    "
                    class="font-medium"
                ></span>
            </template>

            <template slot="posting_id" slot-scope="props">
                <div class="flex items-center">
                    <span class="mr-4 uppercase font-semibold">Actions:</span>

                    <a
                        v-if="!props.item.published_date"
                        class="flex items-center mr-3"
                        href="javascript:;"
                        @click.prevent="publishPosting(props.item.posting_id)"
                        v-can="'publish.posting'"
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
                            <polygon
                                points="22 2 15 22 11 13 2 9 22 2"
                            ></polygon>
                        </svg>
                        Publish
                    </a>

                    <a
                        v-if="props.item.published_date"
                        class="flex items-center mr-3"
                        href="javascript:;"
                        @click.prevent="unpublishPosting(props.item.posting_id)"
                        v-can="'unpublish.posting'"
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
                            class="feather feather-inbox w-4 h-4 mr-2"
                        >
                            <polyline
                                points="22 12 16 12 14 15 10 15 8 12 2 12"
                            ></polyline>
                            <path
                                d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"
                            ></path>
                        </svg>
                        Unpublish
                    </a>

                    <router-link
                        :to="'/postings/' + props.item.posting_id + '?tag=posting'"
                        v-can="'view.posting'"
                    >
                        <a class="flex items-center mr-3" href="javascript:;">
                            <svg
                                class="fill-current w-3 h-3 mr-1"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <path
                                    d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"
                                /></svg
                            >Show
                        </a>
                    </router-link>

                    <a v-if="props.item.category === 'Retail'"
                        class="flex items-center text-theme-6 mr-3 mt-1"
                        href="javascript:;"
                        @click.prevent="inventoryRemoval(props.item.posting_id)"
                        v-can="'inventory-removal.posting'"
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
                            class="feather feather-trash-2 w-4 h-4 mr-1"
                        >
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                            ></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        Zero Out
                    </a>

                    <div
                        class="intro-x dropdown w-8 h-8"
                        v-if="
                            props.item.category === 'Whole Sale' ||
                            props.item.category === 'Wholesale' ||
                            props.item.category === 'Expression of Interest'
                        "
                    >
                        <div class="dropdown-toggle w-8 h-8">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 448 512"
                            >
                                <path
                                    d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"
                                />
                            </svg>
                        </div>

                        <div class="dropdown-menu w-56">
                            <div
                                class="dropdown-menu__content box bg-white-26 dark:bg-dark-6"
                            >
                                <div
                                    class="p-2"
                                    v-if="
                                        props.item.category === 'Whole Sale' ||
                                        props.item.category === 'Wholesale' ||
                                        props.item.category ===
                                            'Expression of Interest'
                                    "
                                >
                                    <a
                                        v-if="
                                            !props.item.published_date &&
                                            props.item.total_published_item
                                                .published_count ==
                                                props.item.total_item.count
                                        "
                                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;"
                                        @click.prevent="
                                            publishPosting(
                                                props.item.posting_id
                                            )
                                        "
                                        v-can="'publish.posting'"
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
                                            <line
                                                x1="22"
                                                y1="2"
                                                x2="11"
                                                y2="13"
                                            ></line>
                                            <polygon
                                                points="22 2 15 22 11 13 2 9 22 2"
                                            ></polygon>
                                        </svg>
                                        Publish
                                    </a>

                                    <a
                                        v-if="props.item.published_date"
                                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;"
                                        @click.prevent="
                                            unpublishPosting(
                                                props.item.posting_id
                                            )
                                        "
                                        v-can="'unpublish.posting'"
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
                                            class="feather feather-inbox w-4 h-4 mr-2"
                                        >
                                            <polyline
                                                points="22 12 16 12 14 15 10 15 8 12 2 12"
                                            ></polyline>
                                            <path
                                                d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"
                                            ></path>
                                        </svg>
                                        Unpublish
                                    </a>

                                    <!-- <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#postings-show"  @click.prevent="showPosting(props.item, props.index)" v-can="'update.brand'">
                                <svg class="fill-current w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>Show
                            </a> -->

                                    <router-link
                                        :to="
                                            '/postings/' + props.item.posting_id
                                        "
                                        v-can="'view.posting'"
                                    >
                                        <a
                                            class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                            href="javascript:;"
                                        >
                                            <svg
                                                class="fill-current w-3 h-3 mr-1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                            >
                                                <path
                                                    d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"
                                                /></svg
                                            >Show
                                        </a>
                                    </router-link>

                                    <a
                                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;"
                                        data-toggle="modal"
                                        :data-target="
                                            '#posting-uppy' +
                                            props.item.posting_id
                                        "
                                        v-if="
                                            props.item.category ===
                                                'Whole Sale' ||
                                            props.item.category ===
                                                'Wholesale' ||
                                            props.item.category ===
                                                'Expression of Interest' ||
                                            props.item.category ==
                                                'International Trade'
                                        "
                                    >
                                        <svg
                                            class="fill-current w-3 h-3 mr-1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                        >
                                            <path
                                                d="M342.7 144H464v288H48V144h121.3l24-64h125.5l23.9 64zM324.3 32h-131c-20 0-37.9 12.4-44.9 31.1L136 96H48c-26.5 0-48 21.5-48 48v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48h-88l-14.3-38c-5.8-15.7-20.7-26-37.4-26zM256 408c-66.2 0-120-53.8-120-120s53.8-120 120-120 120 53.8 120 120-53.8 120-120 120zm0-192c-39.7 0-72 32.3-72 72s32.3 72 72 72 72-32.3 72-72-32.3-72-72-72z"
                                            />
                                        </svg>
                                        Upload Image
                                    </a>

                                    <a
                                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;"
                                        data-toggle="modal"
                                        data-target="#postings-edit"
                                        @click.prevent="
                                            setPosting(props.item, props.index)
                                        "
                                        v-can="'update.brand'"
                                        v-if="
                                            props.item.category ===
                                                'Wholesale' ||
                                            props.item.category ===
                                                'Expression of Interest' ||
                                            props.item.category ==
                                                'International Trade'
                                        "
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
                                            class="feather feather-edit w-4 h-4 mr-1 text-gray-600"
                                        >
                                            <path
                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                            ></path>
                                            <path
                                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                            ></path>
                                        </svg>
                                        Edit
                                    </a>

                                    <a
                                        v-if="
                                            props.item.category ===
                                                'Whole Sale' ||
                                            props.item.category ===
                                                'Wholesale' ||
                                            props.item.category ===
                                                'Expression of Interest'
                                        "
                                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-26 hover:text-white dark:hover:bg-dark-3 rounded-md"
                                        href="javascript:;"
                                        @click.prevent="
                                            deletePosting(props.item.posting_id)
                                        "
                                        v-can="'delete.posting'"
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
                                            class="feather feather-trash-2 w-4 h-4 mr-1"
                                        >
                                            <polyline
                                                points="3 6 5 6 21 6"
                                            ></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                            ></path>
                                            <line
                                                x1="10"
                                                y1="11"
                                                x2="10"
                                                y2="17"
                                            ></line>
                                            <line
                                                x1="14"
                                                y1="11"
                                                x2="14"
                                                y2="17"
                                            ></line>
                                        </svg>
                                        Delete
                                    </a>

                                    <div
                                        :id="
                                            'posting-uppy' +
                                            props.item.posting_id
                                        "
                                        class="modal"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <!-- BEGIN: Modal Header -->
                                                <div
                                                    class="modal-header bg-primary-1 text-theme-2"
                                                >
                                                    <h2
                                                        class="font-medium text-base mr-auto"
                                                    >
                                                        Add Images Posting
                                                    </h2>
                                                    <!-- <button class="btn btn-outline-secondary hidden sm:flex">
                                                <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                                            </button> -->
                                                </div>
                                                <div
                                                    class="modal-body flex justify-center"
                                                >
                                                    <uppy-dashboard
                                                        :url="
                                                            '/postings/' +
                                                            props.item
                                                                .posting_id +
                                                            '/images'
                                                        "
                                                        class="-mt-0"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </table-template>

        <create></create>

        <edit v-if="posting" :posting="posting" />

        <!-- <show v-if="posting" :posting="posting"></show> -->
    </div>
</template>

<script>
import UppyDashboard from "../../defaults/uppy-dashboard.vue";
import TableTemplate from "../../Table";
import Create from "./create";
import Edit from "./edit";
// import Show from "./show";

export default {
    components: {
        TableTemplate,
        Create,
        Edit,
        UppyDashboard,
    },
    props: {
        user: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            fields: {
                banner                : "Banner",
                name                  : "Name",
                desc                  : "Description",
                extended_desc         : "Extended Description",
                category              : "Category",
                unit_price            : "Unit Price",
                suggested_retail_price: "SRP",
                publish_by            : "Published Item",
                published_date        : "Published Date",
                starting_time         : "Starting Time",
                ending_time           : "Ending Time",
                increment_type        : "Increment Type",
                quantity              : "Quantity",
            },

            other_fields: {
                posting_id: "Actions",
            },

            posting: null,
            index: 0,
            category: null,
            status: null,
            filter_type: null,
            order_type: null,
            store: [],
            stores: [],
            date: new Date(),
            currentDate: new Date(),
            color: "#1C3FAA",

            params: {
                category: null,
                status: null,
                for_viewing: null,
                stores: null,
                from: null,
                to: null,
                filter_type: null,
                order_type: null,
            },
        };
    },

    created() {
        this.activeUser();
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

        storeList() {
            return this.stores.filter((store) => {
                return store.store_type
                    .toLowerCase()
                    .includes(this.category.toLowerCase());
            });
        },
    },

    watch: {
        category() {
            this.params.category = this.category ? this.category : null;
            if (
                this.category == "Retail" ||
                this.category == "Auction" ||
                this.category == "Whole Sale" ||
                this.category == "Wholesale" ||
                this.category == "Expression of Interest" ||
                this.category == "International Trade"
            )
                app.$emit("reload");
        },

        status() {
            this.params.status = this.status ? this.status : null;
            app.$emit("reload");
        },

        for_viewing() {
            this.params.for_viewing = this.for_viewing
                ? this.for_viewing
                : null;
            app.$emit("reload");
        },

        store() {
            this.params.store = this.store ? this.store : null;
            app.$emit("reload");
        },

        date() {
            this.params.from = this.date.start ? this.date.start : null;
            this.params.to = this.date.end ? this.date.end : null;
            app.$emit("reload");
        },

        filter_type() {
            this.params.filter_type = this.filter_type
                ? this.filter_type
                : null;
        },

        order_type() {
            this.params.order_type = this.order_type ? this.order_type : null;
            app.$emit("reload");
        },
    },

    methods: {
        deletePosting(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to delete this Posting?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    axios
                        .delete(`/postings/${id}`)
                        .then(() => {
                            this.$modal.success({
                                message: "Posting Successfully Deleted",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },

        inventoryRemoval(id) {
            this.$modal
                .dialog({
                    message:
                        "Are you sure you want to Zero Out this Posting Inventory ?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Delete",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/postings/${id}/inventory-removal`)
                        .then(() => {
                            this.$modal.success({
                                message:
                                    "Posting Successfully Zero Out Inventory",
                                title: "Success",
                            });
                            // Reload page
                            app.$emit("reload");
                        })
                        .catch();
                })
                .catch();
        },

        publishPosting(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to Publish this posting?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Publish",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/postings/${id}/publish`)
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
        unpublishPosting(id) {
            this.$modal
                .dialog({
                    message: "Are you sure you want to unpublish this posting?",
                    confirmButton: "Okay",
                    cancelButton: "Cancel",
                    title: "Unpublish",
                })
                .then((confirmed) => {
                    axios
                        .patch(`/postings/${id}/unpublish`)
                        .then(() => {
                            this.$modal.success({
                                message: "You Unpublish this posting",
                                title: "Unpublish",
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

        activeUser() {
            axios
                .get("/users/" + this.user, {
                    params: {
                        relations: ["stores"],
                    },
                })
                .then((response) => {
                    this.stores = response.data.stores;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getStore() {
            axios.get("/stores").then(({ data }) => {
                this.stores = data;
            });
        },

        reset() {
            this.params.to = null;
            this.params.from = null;
            this.params.category = null;
            this.params.status = null;
            this.params.filter_type = null;
            this.params.order_type = null;
            this.category = null;
            this.status = null;
            this.for_viewing = null;
            this.store = null;
            this.date = null;
            this.filter_type = null;
            this.order_type = null;

            app.$emit("reload");
        },

        setPosting(posting, index) {
            console.log(index);
            this.posting = posting;
            this.index = index;
        },

        showPosting(posting, index) {
            console.log(index);
            this.posting = posting;
            this.index = index;
        },

        clearPosting() {
            this.posting = null;
            this.index = 0;
        },
    },
};
</script>
