<template>
<div class="intro-y w-full h-full mb-2">
		<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
			<h2 class="text-lg font-medium mr-auto">Excel Importer</h2>
		</div>
		
		<div class="flex flex-col mt-6 lg:flex-row">
				<div class="box flex flex-col w-full lg:w-1/2 mr-0 lg:mr-2 mb-2 lg:mb-0 border border-gray-300 border-solid shadow-md rounded-b pb-2">
				<span class="text-center mb-4 py-2 uppercase text-white bg-theme-1 italic">Customer Import</span>
				<div>
					<div class="intro-y mx-auto text-center">
                        <p v-if="loading">Please wait......</p>                         
                        <form data-single="true" action="/file-upload" class="mt-4">
                            <div class="fallback">
                                <input type="file" name="excel" @change="upload"/>
                            </div>
                           
                        </form>
                        <div class="mt-4"></div>
                    </div>
				</div>
				
			</div>
		</div>

		<customer-login-credential></customer-login-credential>
		<addresses></addresses>
		<product></product>
		<image-upload></image-upload>
		<user></user>
		<viewing-details></viewing-details>
		<quantity-uploader></quantity-uploader>
		<starting-amount-uploader></starting-amount-uploader>
		
	</div>
</template>
<script>
	import CustomerLoginCredential from './customer-login-credential';
	import Addresses from './addresses';
	import Product from './product';
	import imageUpload from './image-upload';
	import User from './user';
	import ViewingDetails from './viewing-details';
	import QuantityUploader from './quantity-uploader';
	import StartingAmountUploader from './starting-amount-uploader';

	export default {
		components: {
			CustomerLoginCredential, Addresses, Product, imageUpload, User, ViewingDetails, QuantityUploader, StartingAmountUploader
		},
		data() {
			return {
				loading : false,
				form: new FormUpload({
					file: ''
				})
			}
		},
		methods: {
			upload(event) {
				this.form.file = event.target.files[0];
				this.loading = true;
				this.form.post('/customer-upload')
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
