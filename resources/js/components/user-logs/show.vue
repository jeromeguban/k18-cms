<template>

    <div class="intro-y box overflow-hidden mt-5">
        <div class="px-5 sm:px-16 py-10 sm:py-20">
            <a  v-on:click="$router.go(-1)" class="flex cursor-pointer">
                <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
                <span class=" ml-1">Back</span>
            </a>

            <div class="overflow-x-auto mt-4">
                <table class="table">
                    <tbody v-for="(logs, key, index) in logs.properties">
                        <tr>
                            <td class="border-b dark:border-dark-5">
                                <div class="font-medium whitespace-nowrap">{{key}}</div>
                            </td>
                            <td class="text-right border-b dark:border-dark-5 w-32 font-medium">
                                 <div v-if="logs" class="font-semibold hide" v-html="logs"> </div>
                                    <div v-if="!logs" class="font-semibold hide"> N/A </div>
                                    <div class="font-semibold" style="visibility: hidden;"> {{key}} &nbsp;</div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
        <div class="intro-y box mt-5">

            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Activiy Log Details of ( {{logs.description}} By
                    {{logs.created_by}} )</h2>
            </div>
            <div class="p-5">
                <div class="col-span-12 xl:col-span-6">
                    <div class="flex mb-1">
                        <div class="w-1/2 h-50">
                            <div class="show-content__row w-full" v-for="(logs, key, index) in logs.properties">
                                <div class="flex">
                                    <label class="font-semibold"> {{key}} &nbsp;</label>
                                    <label v-if="logs" class="font-semibold hide" v-html="logs"
                                        style="visibility: hidden;"> </label>
                                    <label v-if="!logs" class="font-semibold hide" style="visibility: hidden;"> N/A
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="w-1/2 h-50">
                            <div class="show-content__row w-full" v-for="(logs, key, index) in logs.properties">
                                <div class="flex">
                                    <label v-if="logs" class="font-semibold hide" v-html="logs"> </label>
                                    <label v-if="!logs" class="font-semibold hide"> N/A </label>
                                    <label class="font-semibold" style="visibility: hidden;"> {{key}} &nbsp;</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

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
            axios.get("user/activity-logs/" + this.$route.params.id)
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
