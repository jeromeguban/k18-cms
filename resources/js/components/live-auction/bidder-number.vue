<template>
    <div ref="close" id="set-bidder-number" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto flex flex-row">
                       Select Bidder Number
                    </h2>
                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="input-form flex flex-row">
                                <multiselect v-model="bidder_number" :multiple="false" :options="bidder_numbers"
                                    :custom-label="getBidderLabel" name="getBidderLabel" customMaxWidth="100%" />
                                <!-- <select id="tabulator-html-filter-field"
                                    class="form-select w-full" v-model="bidder_number">
                                    <option v-for="(bidder_number) in bidder_numbers" :value="bidder_number">Bidder #{{ bidder_number.bidder_number }}  - {{ bidder_number.customer_lastname }}, {{ bidder_number.customer_firstname }}</option>
                                </select> -->
                                <button class="btn w-12 h-10 mt-1 ml-2" @click.prevent="getBidderNumbers()">
                                    <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> 
                                </button>
                            </div>
                            
                        </div>
                    </div>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button class="btn btn-info w-20 " @click.prevent="onSubmit()">Submit</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'bidder-number',
    props: {
        auction: {
            type: Object,
            required: true
        },
        for_approval: {
            type: [Boolean, Number],
            default: false
        },
    },
    data() {
        return {
            bidder_number: '',
            bidder_numbers: [],
            form: {
                bidder_number_id: null,
                customer_id: null,
                for_approval: 0
            },
        }
    },

    watch: {
        'auction'(){
           this.getBidderNumbers()
        },
        'bidder_number'(){
          this.form.bidder_number_id = this.bidder_number.bidder_number_id
          this.form.customer_id = this.bidder_number.customer_id
          this.form.for_approval = this.for_approval ? 1 : 0
        }
    },

    created() {
        this.getBidderNumbers()
    },


    methods: {
        getBidderNumbers(){
            axios.get('/auctions/' + this.auction.auction_id + '/bidder-number')
            .then(({data}) => {
                this.bidder_numbers = data
            })
            .catch((error) => {
                console.log(error)
            })
        },

        onSubmit() {
            this.$emit('initiateFunction', this.form);
            this.closeModal();
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 1);
        },

        getBidderLabel({bidder_number, customer_lastname, customer_firstname}) {
            return `${bidder_number} - ${customer_lastname}, ${customer_firstname}`;
        },

    }

}
</script>
