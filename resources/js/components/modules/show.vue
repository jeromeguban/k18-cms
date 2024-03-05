<template>
<div class="intro-y box overflow-hidden mt-5">
		<div class="px-5 sm:px-16 py-10 sm:py-20">
			<a  v-on:click="$router.go(-1)" class="flex cursor-pointer">
				<svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
				<span class=" ml-1">Back</span>		
			</a>
			 
			<div class="overflow-x-auto mt-4">
				<table class="table">
					<tbody>
						<tr>
							<td class="border-b dark:border-dark-5">
								<div class="font-medium whitespace-nowrap">Module Name</div>
							</td>
							<td class="text-right border-b dark:border-dark-5 w-32 font-medium">{{ module.name }}</td>
						</tr>
						<tr>
							<td class="border-b dark:border-dark-5">
								<div class="font-medium whitespace-nowrap">Permissions</div>
							</td>
							<td class="text-right border-b dark:border-dark-5 w-32 font-medium">
								<div class="parent-div flex flex-row mt-4">
									<span class="flex items-center px-2 py-1 rounded-full bg-theme-1 text-white mr-1" 
									v-for="(module_permission, index) in module.module_permissions">
										{{ module_permission.permission }}
									</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				module: {},
				params:{
				relations: ['module_permissions']
			}
			}
		},
		created() {			
			this.show();			
		},
		methods: {
			show() {
				   axios.get('/modules/' + this.$route.params.id,{
				   	params: {
				   		relations: [ 'module_permissions']
				   	}
				   })
			        .then((response) => {
			            this.module = response.data;
			        })
			        .catch((error) => {
			            console.log(error);
			        });
			}
		}
	}
</script>