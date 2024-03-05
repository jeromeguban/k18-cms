<template>
	<div class="flex w-full mt-8">
		<div class="border border-solid border-gray-300 rounded px-8 py-6 mt-4  w-full">
			<div class="flex flex-col md:flex-row justify-between items-center mt-5">
				<h4 class="text-lg font-medium">Costs</h4>
			</div>
              <div class="overflow-x-auto mt-6">
			  <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
				<table class="table table-report -mt-2">
					<thead>
						<tr>
								<th> 
									<div class="relative block pl-6 min-h-6 mt-1">
										<input 
											type="checkbox" 
										
											v-model="select_all_costs" 
											id="cost">	
										<label 
											class="custom-checkbox-label relative mb-0 align-top font-semibold" 
											for="cost"/> 
									</div>
							   </th>
								<th>Company Code</th>
								<th>Company Name</th>
								<th>Amount</th>
								<th>Remarks</th>
								<!-- <th>Remarks</th> -->
							</tr>
					</thead>
					<tbody>
						<tr v-for="(cost, index) in costs" v-if="costs.length > 0">
								<td>
									<div class="relative block pl-6 min-h-6 mt-4" >
										<input 
											type="checkbox" 
											:id="cost.id" 
											:value="cost" 
											v-model="form.costs">
										<label class="custom-checkbox-label relative mb-0 align-top font-semibold" :for="cost.id"  ></label> 
									</div> 
							   </td>
								<td>{{ cost.store_company_code }}</td>
								<td>{{ cost.company_name }}</td>
								<td>{{ cost.amount | moneyFormat }}</td>
								<td>{{ cost.remarks }}</td>
								
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

		props: ['company_id'],

		data() {
			return {
				costs 		: [],
				form		: {
					costs: []
				},
				select_all_costs: false
			}
		},

		watch: {
			'select_all_costs'() {

				if(this.select_all_costs)
					this.form.costs = this.costs

				if(!this.select_all_costs)
					this.form.costs = []

			},
			'form.costs'() {
				app.$emit('setCosts', this.form.costs)
			}
		},

		created() {
			this.getcompanyCost()
		},

		methods: {
			getcompanyCost() {
				axios.get(`/companies/${this.company_id}/costs`)
				.then(({data})=>{
					this.costs = data;
				});
			},
		}
	}
</script>