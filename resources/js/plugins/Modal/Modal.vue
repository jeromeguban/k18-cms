<template>
    <transition name="fade">
<!--         Close modal when clicked in the background
        @click.self="close" -->
        <div v-if="active" class="fixed inset-0 w-full h-screen flex items-center justify-center bg-semi-65" style="z-index: 99;">
            <div class="box relative lg:w-1/4 md:w-2/4 w-full max-w-2xl shadow-lg rounded-lg p-8">
                <button aria-label="close" class="absolute top-0 right-0 text-xl text-gray-500 my-2 mx-4" @click.prevent="close">
                    <svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/></svg>
                </button>
                <div>
                    <slot></slot>
                </div>
                <slot name="footer"/>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.4s;
    }
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>

<script>
    export default {
        props: ['active'],
        methods: {
            close() {
                this.$emit('close');

            }
        }
    }
</script>

