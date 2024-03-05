<template>
    <div class="w-full">
        <table-template :link="'/product-catalogs/' + $route.params.id " :params="params" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Event Product Catalogue</h4>
            </template>

            <template slot="additionals">

                <div class="flex flex-col w-1/5 pr-1 mr-5 ml-2">
                    <label class="text-2xs font-semibold">Type</label>
                    <select  class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1" v-model="type">
                        <option value="with">With Pictures</option>
                        <option value="without">Without Pictures</option>
                    </select>
                </div>

                <div class="flex justify-between items-center py-4 mr-3">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generateExcel()"> Export </button>
                </div>

                <div class="flex justify-between items-center py-4 mr-3">
                    <button class="btn btn-primary d-paddingTop10" @click.prevent="generatePDF()"> PDF </button>
                </div>

            </template>

            <template slot="banner" slot-scope="props">
                <span v-if="!props.item.banner" class="font-medium"
                    >No Banner Found</span>

                <img
                    alt="Banner"
                    v-if="props.item.banner"
                    :src="props.item.banner"
                    data-action="zoom"
                    class="w-1/2 h-1/2 my-5 image-fit"/>
            </template>

        </table-template>
    </div>
</template>

<script>
    import TableTemplate from "../../Table";

    export default {
        components: { TableTemplate },

    	data() {
    		return {
                fields : {
                    banner                  : 'Banner',
                    name                    : 'Product Name',
                    description             : 'Description',
                    unit_price              : 'Unit Price',
                    suggested_retail_price  : 'Suggested Retail Price'
                },



                type                 : 'without',
    			link                 : '',

    			print_link           : null,
                param_link           : null,

                params                  : {
                    type       : 'without',
                    filter     : null,
                    event_id   : this.$route.params.id,
                },

            }
        },

        watch: {

            'type'() {
                this.params.type = this.type;
            }

        },

        methods: {


            generateExcel() {
                let params = Object.keys(this.params)
                                    .filter(parameter => {
                                        return this.params[parameter]
                                    })
                                    .map(parameter => {
                                        return `${parameter}=${this.params[parameter]}`
                                    })
                                    .join("&")

                window.open(`/product-catalog-excel?` + params, "_blank")
            },

            generatePDF() {
               let params = Object.keys(this.params)
                                    .filter(parameter => {
                                        return this.params[parameter]
                                    })
                                    .map(parameter => {
                                        return `${parameter}=${this.params[parameter]}`
                                    })
                                    .join("&")

                window.open(`/product-catalog-pdf?` + params, "_blank")
            },

            generate() {
                this.link = '/product-catalogs';
                app.$emit('reload');
            },
        },
    }
</script>
