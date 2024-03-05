<template>
    <div ref="close" id="large-slide-over-size-create-preview" class="modal modal-slide-over" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-5 bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Create Section</h2>
                </div>
                <form>
                    <div class="modal-body">

                        <div class="input-form">
                            <div>
                                <label>Template Name</label>
                                <div class="mt-2">
                                    <input v-model="form.name" type="text" name="name" class="form-control"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label>Activate Section</label>
                                <div class="mt-2">
                                    <div class="form-check form-switch">
                                        <input id="activate-section" class="form-check-input" type="checkbox"
                                            v-model="form.activated">
                                        <label class="form-check-label" for="activate-section">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="border border-gray-300 py-6 px-4 mt-4">
                                <div class="">
                                    <label>Banner Size (Base Per Row)</label>
                                    <div class="mt-2">
                                        <input v-model="form.properties.per_row" type="text" name="name"
                                            class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label>Section Height (in pixels)</label>
                                    <div class="mt-2">
                                        <input v-model="form.properties.section_height" type="text" name="name"
                                            class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label">
                                        Section Name
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                    </label>
                                    <input v-model="form.properties.section_name" type="text" name="name"
                                        class="form-control" placeholder="">
                                </div>
                                <div class="mt-4">
                                    <label class="form-label">
                                        Text Color
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                    </label>
                                    <div class="flex flex-col sm:flex-row mt-2">
                                        <div class="form-check mr-4 mt-2 sm:mt-0">
                                            <input id="dark" class="form-check-input" type="radio" value="dark"
                                                v-model="form.properties.text_color">
                                            <label class="form-check-label" for="radio-switch-5">Dark</label>
                                        </div>
                                        <div class="form-check mr-4 mt-2 sm:mt-0">
                                            <input id="light" class="form-check-input" type="radio" value="light"
                                                v-model="form.properties.text_color">
                                            <label class="form-check-label" for="radio-switch-6">Light</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label">
                                        Caption Style
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                    </label>
                                    <div class="flex flex-col sm:flex-row mt-2">
                                        <div class="form-check mr-4 mt-2 sm:mt-0">
                                            <input id="plain-caption" class="form-check-input" type="radio"
                                                value="plain-caption" v-model="form.properties.caption_style">
                                            <label class="form-check-label" for="radio-switch-5">Plain Caption</label>
                                        </div>
                                        <div class="form-check mr-4 mt-2 sm:mt-0">
                                            <input id="overlay-caption" class="form-check-input" type="radio"
                                                value="overlay-caption" v-model="form.properties.caption_style">
                                            <label class="form-check-label" for="radio-switch-6">Overlay Caption</label>
                                        </div>
                                        <div class="form-check mr-4">
                                            <input id="no-caption" class="form-check-input" type="radio"
                                                value="no-caption" v-model="form.properties.caption_style">
                                            <label class="form-check-label" for="radio-switch-4">No Caption</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="" v-if="form.properties.caption_style != 'no-caption'">
                                    <div class="mt-4">
                                        <label>Caption Position</label>
                                        <div class="flex flex-col sm:flex-row mt-2">
                                            <div class="form-check mr-4">
                                                <input id="left" class="form-check-input" type="radio" value="left"
                                                    v-model="form.properties.position">
                                                <label class="form-check-label" for="radio-switch-4">Left</label>
                                            </div>
                                            <div class="form-check mr-4 mt-2 sm:mt-0">
                                                <input id="center" class="form-check-input" type="radio" value="center"
                                                    v-model="form.properties.position">
                                                <label class="form-check-label" for="radio-switch-5">Center</label>
                                            </div>
                                            <div class="form-check mr-4 mt-2 sm:mt-0">
                                                <input id="right" class="form-check-input" type="radio" value="right"
                                                    v-model="form.properties.position">
                                                <label class="form-check-label" for="radio-switch-6">Right</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="form-label">
                                            Caption Title
                                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                        </label>
                                        <input v-model="form.properties.title" type="text" name="name"
                                            class="form-control" placeholder="">
                                    </div>
                                    <div class="mt-4">
                                        <label class="form-label">
                                            Event Details
                                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                        </label>
                                        <input v-model="form.properties.event_details" type="text" name="name"
                                            class="form-control" placeholder="">
                                    </div>

                                    <div class="mt-4">
                                        <label>Caption With Button</label>
                                        <div class="flex flex-col sm:flex-row mt-2">
                                            <div class="form-check mr-2">
                                                <input id="caption-with-button" class="form-check-input" type="checkbox"
                                                    v-model="form.properties.button">
                                                <label class="form-check-label" for="caption-with-button">Yes</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="form.properties.button">
                                        <div class="mt-4">
                                            <label class="form-label">
                                                Button Label
                                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                            </label>
                                            <input placeholder="" v-model="form.properties.button_label" type="text"
                                                name="name" class="form-control">
                                        </div>

                                        <div class="mt-4">
                                            <label class="form-label">
                                                Button Link
                                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                            </label>
                                            <input placeholder="" v-model="form.properties.button_link" type="text"
                                                name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label">
                                        Label
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                    </label>
                                    <input v-model="form.properties.label" type="text" name="name" class="form-control"
                                        placeholder="">
                                </div>

                                <div class="mt-4">
                                    <label class="form-label">
                                        Event Link
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " />
                                    </label>
                                    <input placeholder="" v-model="form.properties.event_link" type="text" name="name"
                                        class="form-control">
                                </div>

                                <div class="flex flex-row mt-4">
                                    <input-file id="image" class="mt-4" label="Background Image" name="image"
                                        :multiple="false" v-model="form_upload.file"
                                        :errorMessage="form.errors.has('image') ? form.errors.get('image') : ''"
                                        accept="image/png, image/jpeg, image/jpg" />
                                    <button @click.prevent="uploadImage(index)"
                                        class="btn btn-primary mt-11 h-10 w-20">Upload</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <vue-snotify></vue-snotify>
                    <loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
                        opacity="5" disableScrolling="false" name="dots"></loader>
                    <div class="modal-footer text-right">
                        <button type="button" data-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button @click.prevent="submit()" class="btn btn-primary w-20">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                name: '',
                activated: false,
                properties: {
                    image: '',
                    label: '',
                    title: '',
                    button: true,
                    per_row: "1",
                    position: "left",
                    event_link: null,
                    text_color: "dark",
                    button_link: "#",
                    button_label: '',
                    section_name: '',
                    caption_style: "overlay-caption",
                    event_details: '',
                    section_height: "400",
                }
            }),
            form_upload: new FormUpload({
                file: ''
            }),
            isLoading: false,
        }
    },

    watch: {

    },
    methods: {

        submit() {
            this.isLoading = true;
            this.form.post(`/retail-sections`)
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Retail Section Successfuly Created!',
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

        uploadImage(index) {
            console.log(index);
            this.form_upload.post(`retail/section-images`)
                .then(({
                    data
                }) => {
                    this.form.properties.image = data;
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
