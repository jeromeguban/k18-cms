<template>
    <div class="w-full">
        <table-template link="/abandoned-cart-histories" :params="params" :fields="fields" modalIdentifier="#"
            :addNewBtn="false">
            <template slot="label">
                <h4>List of Abandoned Cart History</h4>
            </template>

            <template slot="search-filter">

                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Filter</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select mr-2 w-full sm:w-32 2xl:w-full mt-2 sm:mt-0" v-model="params.decrypt">
                        <option v-for="(filter) in filters" :value="filter">{{ filter }}</option>
                    </select>
                </div>

            </template>

            <template slot="customer_firstname" slot-scope="props">
                <span class="w-40">{{ props.item.customer_firstname }} {{ props.item.customer_lastname }}</span>
            </template>


            <template slot="id" slot-scope="props">
                <div class=" justify-left flex flex-col">
                    <a v-if="props.item.status != 'Return to Cart'" class="flex items-center text-theme-6"
                        href="javascript:;" @click.prevent="undoCart(props.item.id)"
                        v-can="'update.abandoned-cart-history'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="corner-up-left" data-lucide="corner-up-left"
                            class="w-4 h-4 mr-1 lucide lucide-corner-up-left block mx-auto">
                            <polyline points="9 14 4 9 9 4"></polyline>
                            <path d="M20 20v-7a4 4 0 00-4-4H4"></path>
                        </svg> Undo
                    </a>
                </div>
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
            cart: null,
            fields: {
                customer_firstname: 'Customer Name',
                mobile_no: 'Mobile No.',
                email: 'Email',
                status: 'Status',
                remarks: 'Remarks',
                id: 'Actions'
            },
            filters: [
                '',
                'First Name',
                'Last Name',
                'Email',
                'Mobile No'
            ],
            filter: null,
            params: {
                decrypt: null,
            },

        }
    },


    methods: {

        undoCart(id) {
            this.$modal.dialog({
                message: 'Are you sure you want to Undo this Abandoned Cart History?',
                confirmButton: 'Okay',
                cancelButton: 'Cancel',
                title: 'Delete'
            })
                .then(confirmed => {
                    axios.patch(`/undo/${id}/abandoned-cart-histories`)
                        .then(() => {
                            this.$modal.success({
                                message: 'Successfully Moved to Abandoned Carts',
                                title: 'Success'
                            });

                            app.$emit('reload');

                        })
                        .catch();
                }).catch();
        },

        reset() {
            this.status = null;
            app.$emit('reload');
        },

    }
}
</script>
