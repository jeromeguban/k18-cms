<template>

    <div>
        <div class="intro-y box p-5 mt-5">
            <a  v-on:click="$router.go(-2)" class="flex items-center cursor-pointer mb-4">
                <svg class="fill-current w-5 h-5" viewBox="0 0 448 512"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/></svg>
                <span class=" ml-1">Back</span>
            </a>
            <div class="text-lg text-black font-semibold leading-loose">Show Details - Career</div>
            <p class="text-gray-700 leading-loose border-b border-gray-200 pb-2 mb-4">This page contains information about your Career details</p>
            <div class="w-full bg-gray-100 h-px"></div>
            <span class="flex flex-col box rounded border border-gray-200 px-6 py-4 gap-y-2" style="width: fit-content">
                <div class="font-bold leading-loose border-b border-gray-300 border-dotted pb-1 mb-1">Details</div>
                <div class="flex gap-x-6">
                    <!-- row must be balance -->
                    <!-- maximum of 7-8 rows when adding 2nd column -->
                    <!-- 1st column example -->
                    <!-- add this class in first column div when adding second column: 'border-r border-gray-200' -->
                    <div class="flex flex-col pb-3 gap-y-3 border-r border-gray-200">
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Position Title</div>
                            <div class="w-96 pr-3">{{ career.position_title }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Job Description</div>
                            <div class="w-96 pr-3">{{ career.desc }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Employment Type</div>
                            <div class="w-96 pr-3">{{ career.employment_type }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Position Level</div>
                            <div class="w-96 pr-3">{{ career.position_level }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Specialtzation</div>
                            <div class="w-96 pr-3">{{ career.job_specialization }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Role</div>
                            <div class="w-96 pr-3">{{ career.job_role }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Work Location</div>
                            <div class="w-96 pr-3">{{ career.work_location[0].address }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Monthly Salary</div>
                            <div class="w-96 pr-3">{{ career.monthly_salary[0].to }} - {{ career.monthly_salary[0].from }}</div>
                        </div>
                    </div>
                    <!-- 2nd column example -->
                    <div class="flex flex-col pb-3 gap-y-3">
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Education Level</div>
                            <div class="w-96 pr-3">{{ career.job_requirements[0].education_level }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Field of Studies</div>
                            <div class="w-96 pr-3">{{ career.job_requirements[0].field_of_studies }}</div>
                        </div>
                        <div class="flex leading-loose">
                            <div class="w-52 font-semibold pr-3">Year(s) of Experience</div>
                            <div class="w-96 pr-3">{{ career.job_requirements[0].year_of_experience }}</div>
                        </div>
                    </div>
                </div>
            </span>
        </div>

        <career-applicant />
    </div>

    </template>
    <script>
    import CareerApplicant from '../career-applicant/index';
        export default {
            components: {
                CareerApplicant
            },

            data() {
                return {
                    career: {}
                }
            },

            watch : {
                'career' () {
                    this.career.job_requirements = JSON.parse(this.career.job_requirements)
                    this.career.work_location = JSON.parse(this.career.work_location)
                    this.career.monthly_salary = JSON.parse(this.career.monthly_salary)
                },
            },

            created() {
                this.show();
            },

            methods: {
                show() {
                       axios.get('/careers/' + this.$route.params.id)
                        .then((response) => {
                            this.career = response.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        }
    </script>
