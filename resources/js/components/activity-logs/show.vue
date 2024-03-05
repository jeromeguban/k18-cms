<template>
	<div class="w-full">
		<div class="flex-grow overflow-y-auto">
			<div class="pl-5 pr-5 pb-5 mt-5">
				<div class="bg-white rounded-sm p-5 table-box-shadow">
					<a  v-on:click="$router.go(-2)" class="flex">
						<svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
						<span class=" ml-1">Back</span>		
					</a>
					<div class="w-full mt-4">
						<h4>Activiy Log Details of ( {{logs.description}} By {{logs.created_by}} )</h4>	
						<br>
						<br>
						<div class="show-content">			    			
						<div class="flex mb-1">
							<div class="w-1/2 h-50">
								<div class="show-content__row w-full" v-for="(logs, key, index) in logs.properties">
									<div class="flex">
									<label class="font-semibold"> {{key}} &nbsp;</label>
									<label v-if="logs" class="font-semibold hide" v-html="logs" style="visibility: hidden;">  </label>
									<label v-if="!logs" class="font-semibold hide" style="visibility: hidden;"> N/A  </label>
									</div>
								</div>
							</div>

								<div class="w-1/2 h-50">
								<div class="show-content__row w-full" v-for="(logs, key, index) in logs.properties">
									<div class="flex">
									<label v-if="logs" class="font-semibold hide" v-html="logs">  </label>
									<label v-if="!logs" class="font-semibold hide" > N/A  </label>
									<label class="font-semibold" style="visibility: hidden;"> {{key}} &nbsp;</label>
									</div>
								</div>
							</div>
						</div>   
						</div>
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
                logs: {
                    properties:{} 
                },
			}
		},

		created() {			
			this.show();			
		},

		methods: {
			
        show() {
        axios.get("/activity-logs/" + this.$route.params.id)
            .then(({ data }) => {
                this.logs = new Form(data);
                this.logs.properties = JSON.parse(
                    data.properties
                );
            })
            .catch(error => {
                console.log(error);
            });
        },
        
		}
	}
</script>