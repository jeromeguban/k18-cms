<template>
    <div ref="close" id="set-account-executive" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto flex flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="user" data-lucide="user" class="lucide lucide-user block mx-auto">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        &nbsp; Assign
                    </h2>

                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <div class="input-form">
                                <multiselect v-model="account_executive" :multiple="false" :options="account_executives"
                                    :custom-label="getAccountExecutiveLabel" name="getAccountExecutiveLabel"
                                    customMaxWidth="100%" />
                            </div>
                        </div>
                    </div>
                    <vue-snotify></vue-snotify>
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
import Datepicker from 'vuejs-datepicker';
export default {
    components: { Datepicker },
    props: ['posting_inquiry'],
    data() {
        return {
            form: {},
            account_executive: '',
            account_executives: [],
        }
    },

    created() {
        this.form = new Form(this.posting_inquiry);
        this.getAccountExecutives();
    },

    watch: {
        'posting_inquiry'() {
            this.form = new Form(this.posting_inquiry);
            this.getAccountExecutives();
        },

        'account_executive'() {
            this.form.account_executive_id = this.account_executive.id
        },
    },

    methods: {

        submit() {
            this.form.patch(`/posting-inquiries/${this.posting_inquiry.id}/account-executive`)
                .then(() => {
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Successfully Assigned!',
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
                    this.$emit('updated');
                    app.$emit('reload');
                })
                .catch(() => {
                    this.isLoading = false;

                });
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 3000);
        },

        getAccountExecutives() {
            axios.get('/account-executives')
                .then(({
                    data
                }) => {
                    this.account_executives = data;
                    this.getAccountExecutiveDetail();
                });
        },

        getAccountExecutiveDetail() {
            if (this.account_executives && this.form.account_executive_id) {
                this.account_executive = this.account_executives.find((account_executive) => {
                    return account_executive.id == this.form.account_executive_id
                });
            }
        },

        getAccountExecutiveLabel({
            first_name,
            last_name,
            email
        }) {

            return `${first_name}  ${last_name} - ${email}`;
        },

    }

}
</script>
