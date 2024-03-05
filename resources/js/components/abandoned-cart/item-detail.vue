<template>
    <div ref="close" id="item-details" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto flex flex-row">
                        <svg class="fill-current w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
                        </svg>
                        &nbsp; Item Details
                    </h2>

                </div>
                <div class="modal-body p-10">
                    <di class="justify-items-start">
                        <span class="font-medium text-lg">{{ cart.name }}</span>
                        <br>
                        <br>
                        <img alt="Banner" v-if="cart.banner" :src="cart.banner"
                            class=" w-1/2 h-1/2 my-5 image-fit rounded">
                        <br>
                        <a :href="'https://hmr.ph/product/' + cart.slug" target="_blank" class="font-medium text-lg ">
                            <u>https://hmr.ph/product/{{ cart.slug }}</u>
                        </a>
                        <br>
                        <br>
                        <span class="mt-4" v-html="cart.description"></span>
                    </di>
                </div>
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-right">
                </div>
                <!-- END: Modal Footer -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'ItemDetail',
    components: {},
    props: ['cart'],
    data() {
        return {

        }
    },

    methods: {


    }

}
</script>
