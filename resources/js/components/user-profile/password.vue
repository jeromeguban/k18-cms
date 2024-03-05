<template>
    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
        <div class="intro-y box lg:mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Change Password</h2>
            </div>
            <div class="p-5">
                <div>
                   <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                        Password
                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('password')" v-html="form.errors.get('password')"/>
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control">
                </div>
                <div class="mt-3">
                    <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                        Password Confirmation
                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('password_confirmation')" v-html="form.errors.get('password_confirmation')"/>
                    </label>
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        class="form-control">
                </div>
                <button type="button" class="btn btn-primary mt-4" @click.prevent="submit">Change Password</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            form:new Form({
                    password              : '',
                    password_confirmation : '',
            }),
            users : {},
        }
    },

    created() {
        this.users = new Form(this.user);
    },

    methods: {
        submit() {
            this.isLoading = true;
            this.form.post(`/profiles/${this.users.id}/change-password`)
                .then(()=>{
                    this.isLoading = false;
                    this.$modal.success({
                        title: 'Success',
                        message: 'Password has been changed.',
                    });

                    app.$emit('reload');
                })
                .catch(()=>{
                    this.isLoading = false;
                });
        },
    }
}
</script>
