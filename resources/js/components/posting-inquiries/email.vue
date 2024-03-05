<template>
    <div ref="close" id="send-email" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto flex flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="send" data-lucide="send" class="lucide lucide-send block mx-auto">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        &nbsp; Send Email
                    </h2>

                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="sm:grid grid-cols-2 gap-2">
                                <div class="mt-3">
                                    <label class="form-label">
                                        To
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="email_form.errors.get('email')"
                                            v-html="email_form.errors.get('email')" />
                                    </label>
                                    <input v-model="email_form.email" type="text" name="email" class="form-control"
                                        placeholder="" disabled>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">
                                        Subject
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="email_form.errors.get('subject')"
                                            v-html="email_form.errors.get('subject')" />
                                    </label>
                                    <input v-model="email_form.subject" type="text" name="subject" class="form-control"
                                        placeholder="">
                                </div>
                            </div>
                            <br>
                            <vue-editor v-model="email_form.message"></vue-editor>
                        </div>
                    </div>
                    <vue-snotify></vue-snotify>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button @click.prevent="send()" class="btn btn-primary w-20">Send</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {
    VueEditor
} from "vue2-editor";
export default {
    components: { Datepicker, VueEditor },
    props: ['posting_inquiry'],
    data() {
        return {
            form: {},
            email_form: new Form({
                email: null,
                subject: null,
                message: null,
                inquiry_id: null
            }),
        }
    },

    watch: {
        'posting_inquiry'() {
            this.form = new Form(this.posting_inquiry);
            this.email_form.email = this.form.email ?? null;
            this.email_form.inquiry_id = this.posting_inquiry.id ?? null;
        },
    },

    created() {
        this.form = new Form(this.posting_inquiry);
        this.email_form.email = this.form.email ?? null;
    },


    methods: {

        send() {
            this.email_form.post('send-email')
                .then(() => {
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Your Email Successfuly Sent!',
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                    this.closeModal();
                })
                .catch(() => {
                });
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 1);
        },

    }

}
</script>
