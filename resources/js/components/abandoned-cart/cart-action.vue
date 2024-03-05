<template>
    <div ref="close" id="cart-action" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">{{ cart.name }}</h2>

                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="input-form mt-4">
                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Status
                                </label>
                                <select id="tabulator-html-filter-field" class="form-select w-full"
                                    v-model="form.status">
                                    <option value="Return to Cart">Return to Cart</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                    <option value="No Respond">No Respond</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-3">
                                    Remarks
                                </label>
                                <input v-model="form.remarks" type="text" name="remarks" class="form-control"
                                    placeholder="">
                            </div>
                        </div>
                    </div>
                    <vue-snotify></vue-snotify>
                    <loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
                        opacity="5" name="dots"></loader>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">
                            Cancel
                        </button>

                        <a v-if="form.status != 'Return to Cart'" class="btn btn-primary w-24 mr-1" href="javascript:;"
                            data-toggle="modal" data-target="#confirmation-modal" @click.prevent="setType('Cancel')">
                            Proceed
                        </a>

                        <a v-if="form.status == 'Return to Cart'" class="btn btn-primary w-24 mr-1" href="javascript:;"
                            data-toggle="modal" data-target="#confirmation-modal" @click.prevent="setType('Transfer')">
                            Transfer
                        </a>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
        <confirmation-modal v-if="type" :type="type" />
    </div>
</template>
<script>
import ConfirmationModal from "./confirmation-modal";
export default {
    components: { ConfirmationModal },
    props: ['cart'],
    data() {
        return {
            form: new Form({
                status: null,
                remarks: null
            }),
            isLoading: false,
            type: null,
        }
    },
    created() {
        // this.form = new Form(this.cart);
        this.form.status = 'Return to Cart'

        app.$on('proceedProcess', (data) => {

            if (data == 'Transfer') {
                console.log(data)
                this.transferCart()
            }

            if (data == 'Cancel') {
                console.log(data)
                this.cancelCart()
            }
        })
    },

    watch: {
        'cart'() {
            // this.form = new Form(this.cart);
            this.form.status = 'Return to Cart'
        },
    },
    methods: {
        transferCart() {
            this.isLoading = true;
            this.form.patch(`/transfer/${this.cart.id}/abandoned-cart`)
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Cart Successfuly Transfer!',
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                    // Reload page
                    this.closeModal();
                    app.$emit('reload');
                })
                .catch(() => {
                    this.isLoading = false;
                    this.$snotify.async('Ooops!', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Error!',
                            body: error.response.data.message,
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                });
        },
        cancelCart() {
            this.isLoading = true;
            this.form.post(`/carts/${this.cart.id}/cancellation`)
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Cart Successfuly Cancelled!',
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                    // Reload page
                    this.closeModal();
                    app.$emit('reload');
                })
                .catch(() => {
                    this.isLoading = false;
                    this.$snotify.async('Ooops!', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Error!',
                            body: error.response.data.message,
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                });
        },
        closeModal() {
            // this.$refs.close.click()
            setTimeout(() => this.$refs.close.click(), 3000);
        },
        setType(type) {
            this.type = type;
        },
        clearType() {
            this.cart = null;
        },
    }
}
</script>
