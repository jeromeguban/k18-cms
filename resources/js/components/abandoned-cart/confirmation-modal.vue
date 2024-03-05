<template>
    <div ref="close" id="confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2">Do you really want to {{ type }} these
                            Cart?
                            <br>
                            This process cannot be undone.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">
                            No
                        </button>
                        <button type="button" class="btn btn-primary w-24" @click.prevent="submit()">
                            Yes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'ConfirmationModal',
    components: {},
    props: ['type'],
    data() {
        return {

        }
    },

    methods: {

        submit() {
            app.$emit('proceedProcess', this.type)
            this.$refs.close.click()
        }

    }

}
</script>