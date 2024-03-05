<template>
    <div ref="close" id="tags-mobile-banner" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Set Mobile Banner</h2>
                </div>
                <form class="">
                    <div class="modal-body p-10">

                        <div class="input-form">

                            <input-file id="mobile_banner" class="mt-4" label="Mobile Banner" name="mobile_banner"
                                :multiple="false" v-model="form.mobile_banner"
                                :errorMessage="form.errors.has('mobile_banner') ? form.errors.get('mobile_banner') : ''"
                                accept="image/png, image/jpeg, image/jpg" />

                        </div>

                    </div>

                    <vue-snotify></vue-snotify>
                    <loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
                        opacity="5" disableScrolling="false" name="dots"></loader>
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button @click.prevent="submit()" class="btn btn-primary w-20">Save</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['tags'],
    data() {
        return {
            form: new FormUpload({
                mobile_banner: '',
            }),
            tag: '',
            isLoading: false,
        }
    },

    created() {
        this.tag = this.tags;
        this.isLoading = false;
    },

    watch: {
        'tags'() {
            this.isLoading = false;
        },
    },

    methods: {
        submit() {
            this.isLoading = true;
            this.form.post(`/tags/${this.tags.id}/mobile-banner`)
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Mobile Banner successfully Uploaded!',
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
                }).catch((error) => {
                    this.isLoading = false;
                });

        },

        closeModal() {

            setTimeout(() => this.$refs.close.click(), 3000);

        },
    }
}
</script>
