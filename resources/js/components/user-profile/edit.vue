<template>
    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
        <div class="intro-y box mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Update Personal Information</h2>
            </div>
            <div class="p-5">
                    <div class="col-span-12 xl:col-span-6">
                        <div>
                            <label for="update-profile-form-6" class="form-label">
                                Firstname
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('first_name')" v-html="form.errors.get('first_name')"/>
                            </label>
                            <input v-model="form.first_name" type="text" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="update-profile-form-7" class="form-label">
                                Lastname
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('last_name')" v-html="form.errors.get('last_name')"/>
                            </label>
                            <input v-model="form.last_name" type="text" class="form-control">
                        </div>
                       <div class="mt-3">
                            <label for="update-profile-form-13" class="form-label">
                                Phone
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 " v-if="form.errors.get('phone')" v-html="form.errors.get('phone')"/>
                            </label>
                            <input v-model="form.phone" type="text" class="form-control">
                        </div>
                    </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="btn btn-primary mr-auto" @click.prevent="submit">Update Profile</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            form : {}
        }
    },

    created() {
        this.form = new Form(this.user);
    },

    methods: {
        submit() {
            this.isLoading = true;
            this.form.post(`/profile/${this.form.id}/edits`)
                .then(()=>{
                    this.isLoading = false;
                    this.$modal.success({
                        message: 'User Successfully Updated',
                        title: 'Success'
                    })

                    app.$emit('roload');
                })
                .catch(()=>{
                    this.isLoading = false;
                })
        },
    }
}
</script>
