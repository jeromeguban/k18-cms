<template>
	<div>
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <img alt="HMR.ph CMS" class="w-6" src="/images/logo.svg">
                        <span class="text-white text-lg ml-3">HMR.ph &nbsp;<span class="font-medium">CMS</span> </span>
                    </a>
                    <div class="my-auto">
                        <img alt="HMR Shop N' Bid" class="-intro-x w-1/2 -mt-16" src="/images/hmrph.png">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            A few more clicks to
                            <br>
                            setup your e-commerce platform.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-gray-500">Manage all your e-commerce accounts in one place</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Forgot Password
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                        <form @submit.prevent="submit()" @keydown="login.errors.clear($event.target.name)">
							<div class="intro-x mt-8">
								<div v-show="alert">
									<div class="alert alert-outline-success alert-dismissible show flex items-center mb-2" role="alert"> <i data-feather="check-circle" class="w-6 h-6 mr-2"></i> Email Send Successfully!  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <i data-feather="x" class="w-4 h-4"></i> </button> </div>
								</div>
								<div v-show="login.errors.has('email')">
									<div class="alert alert-outline-danger alert-dismissible show flex items-center mb-2" role="alert"> <i data-feather="x-circle" class="w-6 h-6 mr-2"></i>
										<span class="text-red-500 my-2 flex items-center text-2xs" v-if="login.errors.get('email')" v-html="login.errors.get('email')"/>
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <i data-feather="x" class="w-4 h-4"></i> </button>
									</div>
								</div>
								<input type="text" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" placeholder="Email" v-model="login.email" required >
							</div>
							<div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
								<div class="flex items-center mr-5">
									<p>Don't have an account? <a href="register#/" class="underline font-semibold">Register here.</a></p>
								</div>
							</div>
							<div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
								<button class="btn btn-primary py-3 px-4 w-full xl:w-36 xl:mr-3 align-top">Reset Password</button>
							</div>
						</form>
                        <div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                            By signin up, you agree to our
                            <br>
                            <a class="text-theme-1 dark:text-theme-10" href="">Terms and Conditions</a> & <a class="text-theme-1 dark:text-theme-10" href="">Privacy Policy</a>
                        </div>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
	</div>

</template>
<script>
	export default {
		data() {
			return {
				login: new Form({
					email : '',
				}),
				alert : false,
			}
		},
		methods: {
			submit() {
				this.login.post('/forgot-password')
				.then((res) => {
					location.reload(true);
					this.alert = true;
				})
				.catch((error) => console.log(error));

			},
		}
	}
</script>
