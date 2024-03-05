<template>
    <div>
        <div ref="close" id="account-executive-create" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header bg-primary-1 text-theme-2">
                        <h2 class="font-medium text-base mr-auto">Create New Account Executive</h2>
                        <!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
                    </div>
                    <form class="">
                        <div class="modal-body p-10">

                            <div class="input-form">
                                <input-text label="First Name" v-model="form.first_name"
                                    :errorMessage="form.errors.has('first_name') ? form.errors.get('first_name') : ''">
                                </input-text>

                                <input-text label="Last Name" v-model="form.last_name"
                                    :errorMessage="form.errors.has('last_name') ? form.errors.get('last_name') : ''">
                                </input-text>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4 ml-1">
                                    User Account
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('user_id')" v-html="form.errors.get('user_id')" />
                                </label>
                                <multiselect v-model="user" :multiple="false" :options="users"
                                    :custom-label="getUserLabel" name="getUserLabel" customMaxWidth="100%" />
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
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: new Form({
                first_name: '',
                last_name: '',
                user_id: '',
            }),
            user: '',
            users: [],
            isLoading: false,
        }
    },

    watch: {

        'user'() {
            this.form.user_id = this.user.id
        },

    },

    created() {
        this.getUsers();
    },

    methods: {
        submit() {
            this.isLoading = true;
            this.form.post('/account-executives')
                .then(() => {
                    this.isLoading = false;
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Account Executive successfully created!',
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

                });
        },

        closeModal() {

            setTimeout(() => this.$refs.close.click(), 3000);

        },

        getUsers() {
            axios.get('/users')
                .then(({
                    data
                }) => {
                    this.users = data;
                });
        },

        getUserLabel({
            first_name,
            last_name,
            email
        }) {

            return `${first_name}  ${last_name} - ${email}`;
        },


    }
}
</script>