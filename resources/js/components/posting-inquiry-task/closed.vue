<template>
    <!-- ACTIVITIES -->
    <div class="form-inline items-start flex-col xl:flex-row mt-2 first:mt-0 first:pt-0">
        <div class="w-full xl:mt-0 flex-1">
            <div class="relative pl-5 pr-5 xl:pr-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
                <div class="form-inline items-start first:mt-0">
                    <draggable v-model="form.task" @change="updateTask()" group="task"
                        :options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
                        class="w-full" style="max-height: 900px;">
                        <div class="flex-1" v-for="(list, index) in form.task">
                            <div class="xl:flex items-center mt-4 first:mt-0">
                                <div class="input-group mr-2">
                                    <span>{{ index + 1 }}</span>
                                    <input v-model="list.finished" @change="updateTask()"
                                        class="form-check-input border border-slate-500 w-6 h-6 ml-2" type="checkbox">
                                </div>
                                <div class="input-group flex-1">
                                    <input v-if="!list.finished" v-model="list.item" @keyup.enter="updateTask()"
                                        type="text" class="form-control w-full" placeholder="Press Enter to save Task">

                                    <input v-if="list.finished" v-model="list.item" @keyup.enter="updateTask()"
                                        type="text" class="form-control w-full" placeholder="" disabled>

                                    <span v-if="!list.finished" class="input-group-text">In
                                        Progress</span>
                                    <span v-if="list.finished" class="input-group-text">Completed</span>
                                    <span class="text-theme-6 my-2 flex items-center text-2xs ml-2"
                                        v-if="form.errors.has('task.' + index + '.item')">
                                        Task Field is Required
                                    </span>
                                </div>
                                <div class="w-10 flex text-slate-500 mt-3 xl:mt-0">
                                    <a href="#" class="item xl:ml-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="move"
                                            data-lucide="move" class="lucide lucide-move w-4 h-4">
                                            <polyline points="5 9 2 12 5 15"></polyline>
                                            <polyline points="9 5 12 2 15 5"></polyline>
                                            <polyline points="15 19 12 22 9 19"></polyline>
                                            <polyline points="19 9 22 12 19 15"></polyline>
                                            <line x1="2" y1="12" x2="22" y2="12"></line>
                                            <line x1="12" y1="2" x2="12" y2="22"></line>
                                        </svg>
                                    </a>
                                    <a href="#" v-if="!list.finished" @click.prevent="deleteRow(index)"
                                        class="ml-3 xl:ml-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2"
                                            data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>

                                    <a href="#" v-if="list.finished" class="ml-3 xl:ml-5" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2"
                                            data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
                <div class="px-10 mt-5">
                    <button v-if="posting_inquiry.account_executive_id == account_executive"
                        class="btn btn-outline-primary border-dashed w-full" @click.prevent="addRow()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="plus" data-lucide="plus" class="lucide lucide-plus w-4 h-4 mr-2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg> Add Task
                    </button>
                    <button v-if="posting_inquiry.account_executive_id != account_executive"
                        class="btn btn-outline-primary border-dashed w-full" @click.prevent="addRow()" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            icon-name="plus" data-lucide="plus" class="lucide lucide-plus w-4 h-4 mr-2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg> Add Task
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import draggable from 'vuedraggable';
export default {
    props: {
        account_executive: {
            type: String,
            default: null
        },
        developer: {
            type: String,
            default: null
        },
        posting_inquiry: {
            type: Object,
            default: null
        },
    },
    components: { draggable },
    data() {
        return {
            posting_inquiry_task: [],
            files: {},
            form: new Form({
                task: [],
            }, false),
            params: {
                status: 'Closed',
            }

        }
    },
    created() {
        this.getTask()
    },

    methods: {

        getTask() {
            axios.get('/posting-inquiries/' + this.$route.params.id + '/tasks', {
                params: this.params
            })
                .then((response) => {
                    this.posting_inquiry_task = response.data[0]
                    this.form.task = JSON.parse(response.data[0].task)
                })
                .catch((error) => {
                    console.log(error);
                });
        },


        updateTask() {
            this.form.patch(`/posting-inquiries/${this.posting_inquiry_task.id}/task`)
                .then((data) => {
                    this.form.task = JSON.parse(data.task)
                });
        },

        addRow() {
            this.form.task.push({
                finished: null,
                item: null,
            })
        },

        deleteRow(index) {
            this.form.task.splice(index, 1)
            this.updateTask()
        },

    }
}
</script>