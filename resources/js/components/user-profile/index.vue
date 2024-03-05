<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="w-12 h-12 image-fit">
                        <img alt="Midone - HTML Admin Template" class="rounded-full"
                            src="images/user.png">
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{user.first_name}} {{user.last_name}}</div>
                        <!-- <div class="text-slate-500">Backend Engineer</div> -->
                    </div>
                </div>
                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                    <a class="flex items-center text-primary font-medium" href="javascript:;" @click='component = null'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="user" data-lucide="user" class="lucide lucide-box w-4 h-4 mr-2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg> Personal Information
                    </a>
                    <a class="flex items-center mt-5" href="javascript:;" @click='component = "changePassword"'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="lock" data-lucide="lock" class="lucide lucide-lock w-4 h-4 mr-2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0110 0v4"></path>
                        </svg> Change Password
                    </a>
                    <a class="flex items-center mt-5" href="javascript:;" @click='component = "UserActivityLog"'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="activity" data-lucide="activity" class="lucide lucide-activity w-4 h-4 mr-2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg> Activity Log
                    </a>
                </div>
            </div>
        </div>

    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">

        <div v-if="this.component">
            <component :is='component' :user='user' />
        </div>

        <div v-if="!this.component">
            <div class="intro-y box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">Personal Information</h2>
                </div>
                <div class="p-5">
                    <div class="col-span-12 xl:col-span-6">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="border-b dark:border-dark-5">
                                        <div class="font-medium whitespace-nowrap">Firstname</div>
                                    </td>
                                    <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                        {{ user.first_name }}</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">
                                        <div class="font-medium whitespace-nowrap">Lastname</div>
                                    </td>
                                    <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                        {{ user.last_name }}</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">
                                        <div class="font-medium whitespace-nowrap">Phone</div>
                                    </td>
                                    <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                        {{ user.phone }}</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">
                                        <div class="font-medium whitespace-nowrap">Email</div>
                                    </td>
                                    <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                        {{ user.email }}</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">
                                        <div class="font-medium whitespace-nowrap">Created At</div>
                                    </td>
                                    <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                        {{ moment(user.created_at).format("YYYY-MM-DD") }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn btn-primary mr-auto" @click.prevent="editProfile">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</template>

<script>
import Edit from "./edit";
import ChangePassword from "./password";
import UserActivityLog from "../user-logs/index";
import moment from 'moment';

export default {
    components: {
        Edit,
        ChangePassword,
        UserActivityLog,
    },

    data() {
        return {
            component   : null,
            user        : null,
        }
    },

    created() {
        this.moment = moment;
        this.show();
    },

    methods: {
        show() {
            axios.get('profiles')
                .then(({data}) => {
                    this.user = data;
                })
        },

        editProfile() {
            this.component = 'Edit';
        },
    },
}
</script>
