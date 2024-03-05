<template>
    <div>
        <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">Navigation Customizer</h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            </div>
        </div>
        <div class="intro-y box overflow-hidden mt-5">
            <div class="flex flex-row items-center justify-center px-5 sm:px-16 py-10 sm:py-20">
                <div class="intro-x flex flex-row items-center">
                    <div class="flex flex-row flex-wrap items-center justify-center w-full gap-12">
                        <router-link :to="'/navigations/header'">
                            <div
                                :class="'zoom-in mx-4 mb-4 p-4 cursor-pointer bg-theme-13 bg-opacity-50 w-40 h-40 lg:w-80 lg:h-40'">
                                <div
                                    class="w-full h-full flex items-center justify-center transition bg-theme-13 hover:bg-theme-13 bg-opacity-70">
                                    <div class="flex flex-col  items-center justify-center">
                                        <span class="uppercase text-center text-2xl lg:text-3xl text-theme-2">
                                            Header Menu
                                        </span>
                                    </diV>
                                </div>
                            </div>
                        </router-link>
                        <router-link :to="'/navigations/sidebar'">
                            <div
                                :class="'zoom-in mx-4 mb-4 p-4 cursor-pointer bg-theme-13 bg-opacity-50 w-40 h-40 lg:w-80 lg:h-40'">
                                <div
                                    class="w-full h-full flex items-center justify-center transition bg-theme-13 hover:bg-theme-13 bg-opacity-70">
                                    <div class="flex flex-col  items-center justify-center">
                                        <span class="uppercase text-center text-2xl lg:text-3xl text-theme-2">
                                            Sidebar Menu
                                        </span>
                                    </diV>
                                </div>
                            </div>
                        </router-link>
                        <router-link :to="'/navigations/footer'">
                            <div
                                :class="'zoom-in mx-4 mb-4 p-4 cursor-pointer bg-theme-13 bg-opacity-50 w-40 h-40 lg:w-80 lg:h-40'">
                                <div
                                    class="w-full h-full flex items-center justify-center transition bg-theme-13 hover:bg-theme-13 bg-opacity-70">
                                    <div class="flex flex-col  items-center justify-center">
                                        <span class="uppercase text-center text-2xl lg:text-3xl text-theme-2">
                                            Footer Menu
                                        </span>
                                    </diV>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
}
</script>
