<template>
    <div class="flex flex-col text-xs md:text-sm" :class="selfChat ? 'text-gray-400' : 'text-gray-600'">
        <span v-if="showFullMessage" class="mt-1 break-all text-xl">{{ comment }}</span>
        <span v-else class="mt-1 break-all text-xl">{{ truncatedMessage }}</span>
        <button class="underline hover:no-underline mt-1" :class="selfChat ? 'text-blue-50' : 'text-blue-500'" v-if="comment.length > 200" @click="toggleShowFullMessage">
            {{ showFullMessage ? "Hide less" : "See more" }}
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            comment: {
                type: String,
                required: true
            },
            selfChat: {
                type: Boolean,
                default: false,
            }
        },
        name: 'see-more',
        data() {
            return {
                showFullMessage: false,
                maxChars: 200,
            }
        },
        computed: {
            truncatedMessage() {
                return this.comment.slice(0, this.maxChars);
            }
        },
        methods: {
            toggleShowFullMessage() {
                this.showFullMessage = !this.showFullMessage
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>