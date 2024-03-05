
<template>
    <div class="mb-4 lg:overflow-y-auto" style="max-height: 500px">
        <div class="flex justify-center p-4 bg-white border border-gray-200 rounded-lg cursor-pointer"  v-if="!previous_products.length">
            <div class="h-full flex items-center">
                <div class="mx-auto text-center">
                    <div class="overflow-hidden mx-auto">
                        <img alt="No Result" class="rounded-md w-36 h-36"
                            src="/images/no-result.png">
                    </div>
                    <div class="mt-2">
                        <div class="font-medium">No Current Products</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2">
          <div class="products-box-shadow bg-white rounded-lg grid grid-cols-12 mb-2" v-for="product in previous_products" :key="product.name" v-if="previous_products && previous_products.length">
              <div class="col-span-1 rounded-l-lg flex items-center justify-center bg-gray-100">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#afaeae" stroke="#afaeae" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="  text-gray-500 lucide lucide-more-vertical stroke-1.5"><circle cx="20" cy="12" r="1"></circle><circle cx="20" cy="5" r="1"></circle><circle cx="20" cy="19" r="1"></circle></svg>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#afaeae" stroke="#afaeae" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class=" text-gray-500 lucide lucide-more-vertical stroke-1.5"><circle cx="6" cy="12" r="1"></circle><circle cx="6" cy="5" r="1"></circle><circle cx="6" cy="19" r="1"></circle></svg>
              </div>

              <div class="col-span-11 p-2 gap-2">
                  <div class="flex gap-2 items-center">
                      <img class="lg:w-8 lg:h-8 xl:w-10 xl:h-10 border rounded-full" :src="product.banner" alt="">
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
              </div>
          </div>
        </div>
      </div>
</template>

<script>
  export default {
      name: 'previous-products',
      props: {
          previous_products: {
              type: [Array,Object],
              default: null
          },
          current_product: {
              type: Object,
              default: null
          }
      },
      methods: {
          getDiscountPercentage(product) {
              if (parseFloat(product.suggested_retail_price) > parseFloat(product.unit_price))
                  return 0

              return ((parseFloat(product.unit_price) - parseFloat(product.suggested_retail_price)) / parseFloat(product.unit_price)) * 100
          },
      }

  }
</script>

<style scoped>
.products-box-shadow {
  box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}

@media (min-width: 1280px) {
  /* START OF PRODUCTS */
  .now-playing {
      padding: 1px 6px 1px 10px !important;
      font-size: 10px !important;
  }

  .play-now {
      padding: 1px 16px 1px 16px !important;
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
