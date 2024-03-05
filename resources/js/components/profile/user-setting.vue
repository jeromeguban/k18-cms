<template>
  	<div class="w-full">
		<div class="flex-grow overflow-y-auto">
			<div class="pl-5 pr-5 pb-5 mt-5">
					<div class="w-full mt-4">
						<h4>My Profile</h4>
                        <div class="mt-2">
                            <div class="show-content__row">
                                <img src="https://i.ibb.co/c3cSpXB/user-placeholder.png" class=" ml-5 justify-center w-16 h-full" alt="User Image"><br />

                                    <div class=" relative inline-block dropdown">
                                        <span class="rounded-md shadow-sm"
                                        ><button class="inline-flex justify-center px-4 py-1 rounded hover:bg-yellow-800 w-28 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-yellow-500 border border-gray-300 rounded-md hover:bg-yellow-600  focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800" 
                                        type="button" aria-haspopup="true" aria-expanded="true" aria-controls="headlessui-menu-items-117">
                                            <span>Actions</span>
                                            </button
                                        ></span>
                                        <div class="opacity-0 invisible dropdown-menu transition-all duration-300 transform -translate-y-2 scale-95">
                                            <div class="absolute w-40 mt-2 bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none" aria-labelledby="headlessui-menu-button-1" id="headlessui-menu-items-117" role="menu">
                                                <button  :id="'edit'" @click.prevent="" tabindex="0" class="btn btn-link text-gray-700 flex justify-center w-full px-4 py-2 text-sm leading-5 text-right hover:bg-gray-200" role="menuitem" >Edit Details</button>
                                                <button  :id="'change-password'" tabindex="1"  @click.prevent="" class=" btn btn-link text-gray-700 flex justify-center w-full px-4 py-2 text-sm leading-5 text-right hover:bg-gray-200"  role="menuitem" >Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                <br />
                                <p class="mt-1">{{ profile.first_name }} {{ profile.last_name }}</p>
                            </div>
                            
                            <div class="show-content__row">
                                <p class="mt-1">Email: {{ profile.email }}</p>
                            </div>

                            <div class="show-content__row">
                                <p class="mt-1">Phone: {{ profile.phone }}</p>
                            </div>
                            
                            <div class="show-content__row">
                                <p class="mt-1">Created Date: {{ profile.created_at }}</p>
                            </div>
                            
                        </div>
					</div>
			</div>
		</div>
        <popup-modal 
            identifier="#edit"
            modalSize="md">
            <edit 
                :user="profile.id"
                    @created ="pushToProfile" 
                    :profile="profile"/>
        </popup-modal>
        
        <popup-modal 
            identifier="#change-password"
            modalSize="md">
            <change-password 
                :user="profile.id"
                    @created ="pushToProfile" 
                    :profile="profile"/>
        </popup-modal>
	</div>
</template>

<script>
import Edit from "./edit";
import ChangePassword from "./change-password";

    export default{
		components: { Edit, ChangePassword },
        data(){
            return {
                profile : {}
            }
        },

        created() {
            this.getProfile();
        },

        methods : {
            getProfile() {
                axios.get('/settings/profiles/')
                    .then(response => {
                        this.profile = response.data;
                    })
                    .catch(error => {
                        console.log('There must be an errors : ' + error);
                    });
            },

            pushToProfile(data) {
				this.getProfile();
			},
        }
    }
</script>
