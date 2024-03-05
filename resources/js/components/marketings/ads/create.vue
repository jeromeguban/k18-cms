<template>
    <div ref="close" id="ads-create" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Create New Ads</h2>
                </div>

                <form class="" @keydown.enter.prevent="" @submit.prevent="store">
                    <div class="modal-body p-10">

                        <div class="input-form">

                            <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
                                Image Orientation <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
                                    v-if="form.errors.get('orientation')" v-html="form.errors.get('orientation')" />
                            </label>
                            <select id="tabulator-html-filter-field"
                                class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto"
                                v-model="orientation">
                                <option value="Portrait">Portrait</option>
                                <option value="Landscape">Landscape</option>
                            </select>

                            <input-file id="banner" class="mt-4" label="Banner" name="banner" :multiple="false"
                                v-model="form.banner"
                                :errorMessage="form.errors.has('banner') ? form.errors.get('banner') : ''"
                                accept="image/png, image/jpeg" />

                            <input-file id="mobile_banner" class="mt-4" label="Mobile Banner" name="mobile_banner"
                                :multiple="false" v-model="form.mobile_banner"
                                :errorMessage="form.errors.has('mobile_banner') ? form.errors.get('mobile_banner') : ''"
                                accept="image/png, image/jpeg" />

                            <label class="form-label w-full flex flex-col sm:flex-row">
                                Link
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('ads_link')" v-html="form.errors.get('ads_link')" />
                            </label>
                            <input v-model="form.ads_link" type="url" name="link" class="form-control" placeholder="">

                            <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3">
                                Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6"
                                    v-if="form.errors.get('ads_name')" v-html="form.errors.get('ads_name')" />
                            </label>
                            <input v-model="form.ads_name" type="text" name="ads_name" class="form-control"
                                placeholder="">

                            <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row mt-3"
                                @change="changeFeatured">
                                Featured
                            </label>
                            <div class="w-24 mt-1">
                                <label for="settings" class="cursor-pointer">
                                    <input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
                                        :checked="form.featured" @change="changeFeatured">
                                </label>
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
                        <button @click.prevent="store()" class="btn btn-primary w-20">Save</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: new FormUpload({
                banner: '',
                mobile_banner: '',
                ads_link: '',
                ads_name: '',
                orientation: 'Portrait',
                featured: false,
            }),

            isLoading: false,

        }
    },

    methods: {
        store() {
            this.isLoading = true;
            this.form.post('ads')
                .then(() => {
                    this.isLoading = false;
                    this.form.icon = '';
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Ads successfully created!',
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
                    this.$emit('created');
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },

        changeFeatured() {
            this.form.featured = !this.form.featured;
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 3000);
        },
    }
}
</script>
