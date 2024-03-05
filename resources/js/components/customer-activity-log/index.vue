<template>
<div ref="close" id="customer-activity-log" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body overflow-auto">
                    <table-template :link="'/customers/'+customer.customer_id+'/activity-logs'" class="-mt-5" :fields="fields" :addNewBtn="false">

                        <template slot="label">
                            <h4>Customer Activity Logs</h4>
                        </template>

                        <template slot="name" slot-scope="props">
                            <span class="font-medium">{{ props.item.name }}</span>
                        </template>

                    </table-template>
                </div>
            </div>
        </div>
    </div>
</template>
 <script>
    import TableTemplate from "../../Table";
    export default {
        components: { TableTemplate},
        props:['customer'],
        data() {
            return {
				fields: {
                    description  			: 'Description',
                    created_by				: 'Activity By',
                    created_at 				: 'Activity Date',
                }
            }
        },

        methods : {

            closeModal() {
                setTimeout(() => this.$refs.close.click(), 3000);
            },

        }
    }
    </script>
