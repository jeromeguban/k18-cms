<template>

     <div class="w-full h-full text-gray-600">
        <loader v-if="isLoading" object="#1C3FAA"  size="9" speed="2" bg="#1E1E1E" objectbg="#999793" opacity="5" disableScrolling="false" name="dots"></loader>
        <div class="px-8 pt-6">
            <h2 class="text-xl-font-bold">Change Password</h2>
        </div>
        <form class="flex flex-col h-full" enctype="multipart/form-data">
            <div class="h-80 p-8 overflow-auto sideform-container--height">
                <div class="flex flex-col w-full mb-3 pr-1">
                    <label class="text-2xs font-semibold">New Password</label>
                    <input type="password" class="border border-solid border-gray-300 focus:border-gray-400 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1" label="New Password" v-model="form.password">
                    <span class="text-red-500 my-2 flex items-center text-2xs" v-if="form.errors.get('password')" v-html="form.errors.get('password')">
                    </span>
                </div>

                <div class="flex flex-col w-full mb-3 pr-1">
                    <label class="text-2xs font-semibold">Confirm Password</label>
                    <input type="password" class="border border-solid border-gray-300 focus:border-gray-400 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1" label="Confirm Password" v-model="form.password_confirmation">
                    <span class="text-red-500 my-2 flex items-center text-2xs" v-if="form.errors.get('password_confirmation')" v-html="form.errors.get('password_confirmation')">
                    </span>
                </div>
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 transition w-full absolute bottom-0 py-8 text-white flex items-center justify-center" @click.prevent="submit">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM272 80v80H144V80h128zm122 352H54a6 6 0 0 1-6-6V86a6 6 0 0 1 6-6h42v104c0 13.255 10.745 24 24 24h176c13.255 0 24-10.745 24-24V83.882l78.243 78.243a6 6 0 0 1 1.757 4.243V426a6 6 0 0 1-6 6zM224 232c-48.523 0-88 39.477-88 88s39.477 88 88 88 88-39.477 88-88-39.477-88-88-88zm0 128c-22.056 0-40-17.944-40-40s17.944-40 40-40 40 17.944 40 40-17.944 40-40 40z"/></svg>
                Update
            </button>
        </form>
	</div>

</template>

<script>

    export default {
        props: [ 'user'],
        data(){
            return{
                form    : new Form({
                    password                : '',
                    password_confirmation   : ''
                }),
                profile: {},
                isLoading : false,
            }
        },

        created() {
            this.getProfile();
        },

        methods: {
            submit() {
                this.isLoading = true;
                this.form.patch(`/profiles/change-password/${this.user}`)
                .then(()=>{
                    this.isLoading = false;
                    this.$modal.success({
                        message: 'You have successfully updated your password. Your account will logout',
                        title: 'Success'
                    });
                     // Reload to logout
                    app.$emit('reload');
                    this.$emit('created', this.user);
                    this.closeModal();
                })
                .catch(()=>{
				    this.isLoading = false;
			    });
            },

            getProfile() {
                axios.get('/profiles/')
                    .then(response => {
                        this.profile = response.data;
                    })
                    .catch(error => {
                        console.log('There must be an errors : ' + error);
                    });
            },

            closeModal() {
			    app.$emit('close-modal');
		    },
        }
    }
</script>
