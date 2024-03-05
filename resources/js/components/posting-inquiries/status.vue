<template>
    <div ref="close" id="change-status" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto flex flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="circle" data-lucide="circle" class="lucide lucide-circle block mx-auto mb-1">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        &nbsp; Status
                    </h2>

                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="input-form">
                                <a class="flex rounded-sm p-2 text-theme-10  hover:bg-theme-10  hover:text-white"
                                    href="#" @click.prevent="submit(status = 'Closed')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">Closed</span>
                                    </div>
                                </a>

                                <a class="flex rounded-sm p-2 text-theme-6  hover:bg-theme-6  hover:text-white" href="#"
                                    @click.prevent="submit(status = 'Cancelled')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">Cancelled</span>
                                    </div>
                                </a>

                                <a class="flex rounded-sm p-2 text-theme-12  hover:bg-theme-12  hover:text-white"
                                    href="#" @click.prevent="submit(status = 'On Hold')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">On Hold</span>
                                    </div>
                                </a>

                                <a class="flex rounded-sm p-2 text-theme-7  hover:bg-theme-7  hover:text-white" href="#"
                                    @click.prevent="submit(status = 'Answered')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">Answered</span>
                                    </div>
                                </a>

                                <a class="flex rounded-sm p-2 text-theme-9  hover:bg-theme-9  hover:text-white" href="#"
                                    @click.prevent="submit(status = 'In Progress')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">In Progress</span>
                                    </div>
                                </a>

                                <a class="flex rounded-sm p-2 text-theme-8  hover:bg-theme-8  hover:text-white" href="#"
                                    @click.prevent="submit(status = 'Open')">
                                    <div class=" flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="circle"
                                            data-lucide="circle" class="lucide lucide-circle block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <span class="font-medium ml-2">Open</span>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>
                    <vue-snotify></vue-snotify>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
export default {
    components: { Datepicker },
    props: ['posting_inquiry'],
    data() {
        return {
            form: {},
        }
    },

    watch: {
        'posting_inquiry'() {
            this.form = new Form(this.posting_inquiry);
        },
    },

    created() {
        this.form = new Form(this.posting_inquiry);
    },


    methods: {

        submit(status) {
            this.closeModal();
            this.form.status = status;
            this.form.patch(`/posting-inquiries/${this.posting_inquiry.id}/status`)
                .then(() => {
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Priority Status Successfully Updated!',
                            config: {
                                timeout: 2000,
                                showProgressBar: true,
                                closeOnClick: false,
                                pauseOnHover: true
                            }
                        }), 1000);
                    }));
                    // Reload page
                    this.$emit('updated');
                    app.$emit('reload');
                })
                .catch(() => {
                    this.isLoading = false;

                });
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 1);
        },

    }

}
</script>
