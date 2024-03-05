<template>
    <!-- BEGIN: Top Bar -->
    <div class="top-bar">
        <!-- BEGIN: Breadcrumb -->
        <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
            <a href="" @click="removeLocalStorageForLiveSelling">Application</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href=""
                class="breadcrumb--active uppercase" v-text="$route.query.tag"></a>
            <a href="" @click.prevent="reload()" class="ml-2 flex items-center text-theme-1 dark:text-theme-10"> <i
                    data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Refresh </a>
        </div>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Store Name -->
        <div class="intro-x dropdown ml-2 sm:mr-6">
            <h4 class=" text-xs dark:text-slate-500 font-medium leading-none">{{ store }}</h4>
        </div>
        <!-- END: Store Name -->

        <notifications v-can="'list.system-notification'"/>

        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button"
                aria-expanded="false">
                <img alt="HMR.ph CMS" src="/images/user.png">
            </div>
            <div class="dropdown-menu w-56">
                <div class="dropdown-menu__content box bg-theme-26 dark:bg-dark-6 text-white">
                    <div class="p-4 border-b border-theme-27 dark:border-dark-3">
                        <div v-show="user" class="font-medium">{{ user.first_name }} {{ user.last_name }}</div>
                        <div class="text-xs text-theme-28 mt-0.5 dark:text-gray-600"></div>
                    </div>
                    <div class="p-2">
                        <router-link :to="'/user/profile'"
                            class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="user" class="w-4 h-4 mr-2"></i> Profile </router-link>
                        <a @click.prevent="switchStore()"
                            class="cursor-pointer flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="home" class="w-4 h-4 mr-2"></i> Switch Store </a>
                        <a href=""
                            @click="removeLocalStorageForLiveSelling"
                            class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="lock" class="w-4 h-4 mr-2"></i> Reset Password </a>
                        <a href=""
                            @click="removeLocalStorageForLiveSelling"
                            class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="help-circle" class="w-4 h-4 mr-2"></i> Help </a>
                    </div>
                    <div class="p-2 border-t border-theme-27 dark:border-dark-3">
                        <a href="" @click.prevent="logOut()"
                            class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
</template>

<script>
export default {

    props: {
        store: {
            required: true,
            type: String
        }
    },

    data() {
        return {

            user: '',
        }
    },

    created() {
        this.getProfile();
    },
    methods: {
        logOut() {
            axios.post('/logout')
                .then(() => {
                    window.location.reload();
                    this.removeLocalStorageForLiveSelling();
                });
        },

        switchStore() {
            axios.delete('/store-validation')
                .then(() => {
                    window.location.reload();
                });
        },

        reload() {
            window.location.reload();
        },

        removeLocalStorageForLiveSelling() {
            localStorage.removeItem('isLiveSellingControllerRoute');
        },

        getProfile() {
            axios.get('profiles')
                .then(({ data }) => {
                    this.user = data;
                });
        },

    }
}
</script>
