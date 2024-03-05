<template>
    <div ref="close" id="change-status" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Update Status</h2>

                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="input-form">
                                <label class="text-2xs font-semibold">Status :</label>
                                <div class="col-sm-10">
                                    <select
                                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                        name="status" v-model="form.status">
                                        <option value="Open" disabled>Open</option>
                                        <option value="Answered"> Answered</option>
                                        <option value="Closed">Closed</option>
                                    </select>

                                    <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                        v-if="form.errors.has('status')" v-text="form.errors.get('status')"></span>
                                </div>
                                <label class="form-label w-full flex flex-col sm:flex-row">
                                    Remarks
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('remarks')" v-html="form.errors.get('remarks')" />
                                </label>
                                <input v-model="form.remarks" type="text" name="remarks" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>

                    <vue-snotify></vue-snotify>
                    <loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
                        opacity="5" disableScrolling="false" name="dots"></loader>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button @click.prevent="submit()" class="btn btn-primary w-20">Update</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { VueEditor } from "vue2-editor";
export default {
    components: { VueEditor },
    props:['store_inquiry'],
    data() {
        return {
            form      : {},
            isLoading : false,
        }
    },
    created() {
        this.form = new Form(this.store_inquiry);
    },
    watch:{
        'store_inquiry' () {
            this.form = new Form(this.store_inquiry);
        },

        'company'() {
            this.form.company_id = this.company.id;
        },
    },
    methods: {
        submit() {
            this.isLoading = true;
            this.form.patch(`/store-inquiries/${this.store_inquiry.id}/status`)
            .then(() => {
             this.isLoading = false;
             this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                setTimeout(() => resolve({
                    title: 'Success!',
                    body: 'Store Successfuly Updated!',
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
            .catch(()=>{
                this.isLoading = false;
            });
        },


        closeModal() {
            setTimeout(() => this.$refs.close.click(), 3000);
        },
    }
}
</script>
