<template>

<div>
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
								<div class="font-medium whitespace-nowrap">Payload Uuid</div>
							</td>
							<td class="text-block border-b dark:border-dark-5 w-32 font-medium">{{ payload.uuid }}</td>
						</tr>
                        <tr>
							<td class="border-b dark:border-dark-5">
								<div class="font-medium whitespace-nowrap">Display Name</div>
							</td>
							<td class="text-blockt border-b dark:border-dark-5 w-32 font-medium">{{ payload.displayName }}</td>
						</tr>
                        <tr>
							<td class="border-b dark:border-dark-5">
								<div class="font-medium whitespace-nowrap">Data</div>
							</td>
							<td class="text-block border-b dark:border-dark-5 w-32 font-medium">{{ payload.data }}</td>
						</tr>
                         <tr>
							<td class="border-b dark:border-dark-5">
								<div class="font-medium whitespace-nowrap">Error Exception</div>
							</td>
							<td class="text-block border-b dark:border-dark-5 w-32 font-medium">{{ failed_job.exception }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
</div>

</template>
<script>
	export default {
		data() {
			return {
                failed_job: {},
                payload: {}
			}
		},
		created() {			
			this.show();			
		},
		methods: {
			show() {
				   axios.get('/failed-jobs/' + this.$route.params.id)
			        .then((response) => {
                        this.failed_job = response.data;
                        this.payload = JSON.parse(this.failed_job.payload);
			        })
			        .catch((error) => {
			            console.log(error);
			        });
			}
		}
	}
</script>