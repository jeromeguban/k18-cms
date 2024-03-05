<template>
<div class="intro-y w-full h-full mb-2">
		<div class="flex flex-col mt-6 lg:flex-row">
				<div class="box flex flex-col w-full lg:w-1/2 mr-0 lg:mr-2 mb-2 lg:mb-0 border border-gray-300 border-solid shadow-md rounded-b pb-2">
				<span class="text-center mb-4 py-2 uppercase text-white bg-theme-1 italic">User Import</span>
				<div>
					<div class="intro-y mx-auto text-center">
                        <p v-if="loading">Please wait......</p>                         
                        <form data-single="true" action="/file-upload" class="mt-4">
                            <div class="fallback">
                                <input type="file" name="excel" @change="userUpload"/>
                            </div>
                           
                        </form>
                        <div class="mt-4"></div>
                    </div>
				</div>
				
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				loading : false,
				form: new FormUpload({
					file: ''
				})
			}
		},
		methods: {
			userUpload(event) {
				this.form.file = event.target.files[0];
				this.loading = true;
				this.form.post('/user-upload')
				.then(response => {
					alert('Done importing!');
					this.loading = false;
					document.querySelector('input[type=file]').value = "";
				})
				.catch(error => { 
					alert('Something went wrong...');
					this.loading = false;
					document.querySelector('input[type=file]').value = "";
				});
			}
		}
	}
</script>
