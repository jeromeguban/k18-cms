
<template>
        <div class="mb-4 lg:overflow-y-auto" style="max-height: 500px">
            <div class="flex justify-center p-4 bg-white border border-gray-200 rounded-lg cursor-pointer"  v-if="!on_queue_products.length">
                <div class="h-full flex items-center">
                    <div class="mx-auto text-center">
                        <div class="overflow-hidden mx-auto">
                            <img alt="No Result" class="rounded-md w-36 h-36"
                                src="/images/no-result.png">
                        </div>
                        <div class="mt-2">
                            <div class="font-medium">No On Queue Products</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-2 pb-16">
                <draggable
                    v-model="products"
                    @change="updateSequenced"
                    :options="{handle:'.item', animation:150, scrollSensitivity: 200, forceFallback: true}">

                <div class="products-box-shadow bg-white rounded-lg grid grid-cols-12 mb-2" v-for="product in products" :key="product.name" v-if="on_queue_products && on_queue_products.length">
                    <div class="item col-span-1 rounded-l-lg flex items-center justify-center bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#afaeae" stroke="#afaeae" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="  text-gray-500 lucide lucide-more-vertical stroke-1.5"><circle cx="20" cy="12" r="1"></circle><circle cx="20" cy="5" r="1"></circle><circle cx="20" cy="19" r="1"></circle></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#afaeae" stroke="#afaeae" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class=" text-gray-500 lucide lucide-more-vertical stroke-1.5"><circle cx="6" cy="12" r="1"></circle><circle cx="6" cy="5" r="1"></circle><circle cx="6" cy="19" r="1"></circle></svg>
                    </div>

                    <div class="col-span-11 p-2 gap-2">
                        <div class="flex gap-2 items-center">
                            <img class="w-8 h-8 xl:w-10 xl:h-10 border rounded-full" :src="product.banner" alt="">

                            <div>
                                <h6 class="text-black font-semibold lg:text-xs 2xl:text-sm col-span-12">{{ product.name | limitDescription(30)}}</h6>
                                <div class="flex items-center gap-2 col-span-12 mb-2">
                                    <div class=" text-theme-6 font-semibold lg:text-xs 2xl:text-sm">{{ product && parseFloat(product.suggested_retail_price) < parseFloat(product.unit_price) ? product.suggested_retail_price : product.unit_price | moneyFormat }}</div>
                                    <div
                                        :style="'font-size: 10px'" class="relative text-theme-31 font-semibold lg:text-xs discounted-price"
                                        v-if="product && parseFloat(product.suggested_retail_price) < parseFloat(product.unit_price)">
                                        {{ product.unit_price | moneyFormat }}
                                        <span class="absolute top-2 left-0 right-0 h-0.5 bg-theme-31 bottom-0"></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="flex justify-end gap-2">
                            <a :style="'padding: 1.5px 5px 1.5px 6px; font-size: 8px;'" href="javascript:;" data-toggle="modal" data-target="#product-details"
                                @click.prevent="showProductDetails(product)" class="details text-white rounded-md bg-theme-33 flex justify-center items-center gap-1">
                                <span>Details</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="info" data-lucide="info" class="lucide lucide-info stroke-1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            </a>

                            <div v-if="current_product.posting_id == product.posting_id" :style="'padding: 1.5px 4.5px 1.5px 6px; font-size: 8px;'" class="now-selling text-white rounded-md bg-theme-6 flex items-center justify-center">
                                <span class="mr-1">Now Selling</span>
                                <i class="fa-solid fa-caret-right"></i>
                            </div>

                            <button
                                @click.prevent="setActiveProduct(product)"
                                v-if="current_product.posting_id != product.posting_id" :style="'padding: 1.5px 4.5px 1.5px 6px; font-size: 8px;'" class="sell-now text-white rounded-md bg-theme-31 flex justify-center items-center relative">
                                <span class="mr-1">Sell Now</span>
                                <i class="fa-solid fa-caret-right"></i>
                                <i class="fa-solid fa-caret-right"></i>
                            </button>

                        </div>
                    </div>
                </div>

                </draggable>
            </div>
            <product-detail v-if="product" :product="product"/>
      </div>
</template>

<script>
import ProductDetail from './product-detail';
import draggable from 'vuedraggable';
    export default {
        name: 'on-queue-products',
        components: {
            draggable,
            ProductDetail
        },
        props: {
            on_queue_products: {
                type: [Array,Object],
			    default: null
            },
            current_product: {
                type: Object,
			    default: null
            }
        },
        data() {
            return {
                products : this.on_queue_products,
                product : null
            }
        },
        computed : {
            'productSequencedIds'() {
                return this.products.map((product) => { return product.posting_id})
            }
        },
        watch: {

        },
        methods: {
            getDiscountPercentage(product) {
                if (parseFloat(product.suggested_retail_price) > parseFloat(product.unit_price))
                    return 0

                return ((parseFloat(product.unit_price) - parseFloat(product.suggested_retail_price)) / parseFloat(product.unit_price)) * 100
            },
            updateSequenced() {
                axios.post(`event-postings/sequence`, {
                    sequence: this.productSequencedIds,
                }).then(response => {
                    this.$emit('refreshOnQueueProducts', this.products)
                });
            },
            // setCurrentProduct(product) {
            //     this.$emit('setCurrentProduct', this.product)
            // }
            setActiveProduct(product) {
                this.$emit('setActiveProduct', product)
            },
            showProductDetails(product) {
                this.product = product;
            },
        }

    }
</script>

<style scoped>
.products-box-shadow {
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}

svg {
    color: white !important;
}

@media (min-width: 768px) {
    /* START OF PRODUCTS */
    .now-selling {
        padding: 1px 8px 1px 10px !important;
        font-size: 10px !important;
    }

    .details {
        padding: 1.5px 10px 2px 10px !important;
        font-size: 10px !important;
    }

    .sell-now {
        padding: 1px 8px 1px 10px !important;
        font-size: 10px !important;
    }
    /* END OF PRODUCTS */
}

@media (min-width: 1280px) {
    /* START OF PRODUCTS */
    .now-selling {
        padding: 1px 8px 1px 10px !important;
        font-size: 10px !important;
    }

    .details {
        padding: 1.5px 10px 2px 10px !important;
        font-size: 10px !important;
    }

    .sell-now {
        padding: 1px 8px 1px 10px !important;
        font-size: 10px !important;
    }
    /* END OF PRODUCTS */
}

@media (min-width: 1536px) {
    /* START OF PRODUCTS */
    .discounted-price {
        font-size: 12px !important;
    }
    /* END OF PRODUCTS */
}
</style>
