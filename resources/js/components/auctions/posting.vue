<template>
    <div class="w-full">
        <table-template
            :link="'/auctions/' + $route.params.id + '/postings'"
            :fields="fields"
            :params="params"
            :addNewBtn="false"
        >
            <template slot="label">
                <h4>List of Postings</h4>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
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

            <template slot="posting_id" slot-scope="props">
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
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
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
                    :to="'/postings/' + props.item.posting_id"
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
            </template>
        </table-template>
    </div>
</template>
<script>
import TableTemplate from "../../Table";

export default {
    components: { TableTemplate },
    data() {
        return {
            fields: {
                sequence            : "Sequence",
                lot_number          : "Lot Number",
                banner              : "Banner",
                name                : "Name",
                description         : "Description",
                extended_description: "Extended Description",
                starting_time       : "Starting Time",
                ending_time         : "Ending Time",
                category            : "Category",
                vat                 : "Vat",
                duties              : "Duties",
                increment_type      : "Increment Type",
                posting_id          : "Actions",
            },
            posting: null,
            index: 0,
            category: null,
            status: null,

            params: {
                category: null,
                status: null,
                for_viewing: null,
                auction_id: this.$route.params.id,
            },
        };
    },

    watch: {
        category() {
            this.params.category = this.category ? this.category : null;
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
    },

    methods: {
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

        reset() {
            this.params.category = null;
            this.category = null;
            this.params.status = null;
            this.status = null;
            this.for_viewing = null;
            app.$emit("reload");
        },

        clearPosting() {
            this.posting = null;
            this.index = 0;
        },
    },
};
</script>
