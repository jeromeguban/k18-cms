<template>
    <div class="flex-grow overflow-y-auto">
			<div class="pl-5 pr-5 pb-5 mt-5">
                <span class="font-medium pb-4 block text-lg">Auction Increment Table</span>
				<div class="bg-white rounded-sm p-5 table-box-shadow mt-4">
					<div class="flex">
						<div class="flex flex-col w-full">
                            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                                <table class="table  -mt-2" >
                                    <thead>
                                        <tr>
                                            <th class=" uppercase text-left" >Minimum</th>
                                            <th class=" uppercase text-left" >Maximum </th>
                                            <th class=" uppercase text-left" >Increment</th>
                                        </tr>
                                    </thead>

                                    <tr class="border-t border-dotted border-gray-300" v-for="(item, index) in items"  >
                                        <td class="btn-container">
                                            {{ item.minimum }}
                                        </td>
                                        <td class="btn-container">
                                            {{ item.maximum }}
                                        </td>
                                        <td class="btn-container">
                                            {{ item.increment }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<!-- <div class="w-full">
		<table-template link="/bid-increments" :addNewBtn="false" :fields="fields" :params="params">
			<template slot="label">
				<h4>List Increment Table</h4>
			</template>
		    <template slot="name" slot-scope="props">
		    	<!-- <span class="font-medium">{{ props.item.name }}</span> -->
		    </template>
        </table-template>
	</div> -->
</template>

<script>
	import TableTemplate from '../../Table';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';

    export default {
		components: { TableTemplate, Datepicker, moment },

        data(){
            return {
                item  : null,
                items : [],
            }
        },

        created() {
            this.getAuctionIncreateTables();
        },

        methods : {

            getAuctionIncreateTables() {
                axios.get('bid-increments?auction_id='+this.$route.params.id)
                .then(({data})=>{
                    this.items = data;
                })
            }
        }
    }
</script>
