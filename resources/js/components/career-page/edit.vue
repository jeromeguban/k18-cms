<template>
	<div>
		<div ref="close" id="careers-edit" class="modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Career</h2>
						<!-- <button class="btn btn-outline-secondary hidden sm:flex">
							<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
						</button> -->
					</div>
					<form class="">
						<div class="modal-body p-10">

							<div class="input-form">

								<label class="form-label w-full flex flex-col sm:flex-row">
									Position Title
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('position_title')" v-html="form.errors.get('position_title')" />
								</label>
								<input v-model="form.position_title" type="text" name="position_title" class="form-control" placeholder="Position Title">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
									Job Description
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('job_description')"
                                    v-html="form.errors.get('job_description')" />
								</label>
                                <vue-editor v-model="form.job_description"></vue-editor>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Employment Type
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('employment_type')" v-html="form.errors.get('employment_type')" />
                                </label>
                                <div class="col-sm-10">
                                    <select
                                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                        name="bidding_type" v-model="form.employment_type">
                                        <option value="Full time">Full Time</option>
                                        <option value="Part time">Part Time</option>
                                    </select>
                                </div>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Position Level
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('position_level')" v-html="form.errors.get('position_level')" />
                                </label>
                                <input v-model="form.position_level" type="text" name="position_level" class="form-control" placeholder="Position Level">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Job Specialization
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_specialization')" v-html="form.errors.get('job_specialization')" />
                                </label>
                                <input v-model="form.job_specialization" type="text" name="job_specialization" class="form-control" placeholder="Position Level">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Job Role
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_role')" v-html="form.errors.get('job_role')" />
                                </label>
                                <input v-model="form.job_role" type="text" name="job_role" class="form-control" placeholder="Job Role">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Work Location
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('work_location')" v-html="form.errors.get('work_location')" />
                                </label>
                                <multiselect searchable="true" :options="stores" name="store" v-model="store"
									:custom-label="storeCustomLabel" customMaxWidth="100%"/>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Monthly Salary
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('monthly_salary')" v-html="form.errors.get('monthly_salary')" />
                                </label>
                                <div class="sm:grid grid-cols-2 gap-2">
                                    <div class="mt-3">
                                        <input v-model="form.monthly_salary[0].to" type="text" name="monthly_salary" class="form-control" placeholder="Monthly Salary">
                                    </div>
                                    <div class="mt-3">
                                        <input v-model="form.monthly_salary[0].from" type="text" name="monthly_salary" class="form-control" placeholder="Monthly Salary">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label>Display Monthly Salary</label>
                                    <div class="mt-2">
                                        <label for="settings" class="cursor-pointer">
									<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
										:checked="form.monthly_salary_display" @change="changeMonthlySalaryDisplay">
								</label>
                                    </div>
                                </div>

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Education Level
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_requirements.education_level')" v-html="form.errors.get('job_requirements.education_level')" />
                                </label>
                                <input v-model="form.job_requirements[0].education_level" type="text" name="job_requirements" class="form-control" placeholder="Job Role">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Field of Studies
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_requirements.field_of_studies')" v-html="form.errors.get('job_requirements.field_of_studies')" />
                                </label>
                                <input v-model="form.job_requirements[0].field_of_studies" type="text" name="job_requirements" class="form-control" placeholder="Job Role">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Year(s) of Experience
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_requirements.year_of_experience')" v-html="form.errors.get('job_requirements.year_of_experience')" />
                                </label>
                                <input v-model="form.job_requirements[0].year_of_experience" type="text" name="job_requirements" class="form-control" placeholder="Job Role">

							</div>

						</div>

						<vue-snotify></vue-snotify>
						<loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
							opacity="5" disableScrolling="false" name="dots"></loader>
						<!-- BEGIN: Modal Footer -->
						<div class="modal-footer text-right">
							<button type="button" data-dismiss="modal"
								class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
							<button @click.prevent="submit()" class="btn btn-primary w-20">Save</button>
						</div>
						<!-- END: Modal Footer -->
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import {VueEditor} from "vue2-editor";
export default {
    components: {VueEditor},
	props: ['career'],

	data() {
		return {
			form        : {},
			stores      : [],
            store       : null,
            isLoading   : false,
		}
	},

    created() {
		this.form = new Form(this.career);
	},

    watch : {

        'career' () {
            this.form = new Form(this.career);

            this.form.job_requirements = JSON.parse(this.form.job_requirements)
            this.form.work_location = JSON.parse(this.form.work_location)
            this.form.monthly_salary = JSON.parse(this.form.monthly_salary)

        },

        'store'() {
            this.form.work_location[0].store_name = this.store.store_name;
            this.form.work_location[0].address = this.store.address_line;
            this.form.work_location[0].store_company_name = this.store.store_company_name;
        },

    },

    created() {
        this.getStores();
    },

	methods: {
		submit() {
			this.isLoading = true;
			this.form.patch(`/careers/${this.career.career_id}`)
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Career successfully Updated!',
						config: {
                            timeout: 2000,
                            showProgressBar: true,
                            closeOnClick: false,
                            pauseOnHover: true
						}
					}), 1000);
				}));
				 // Reload page
				this.closeModal();
				app.$emit('reload');
			})
			.catch(()=>{
                this.isLoading = false;
			});
		},

		closeModal() {

			setTimeout(() => this.$refs.close.click(), 3000);

		},

        getStores() {
            axios.get('/stores')
            .then(({data})=>{
                this.stores = data;
            });
        },

        storeCustomLabel ({ store_name, store_company_name}) {
            return `${store_name} - ${store_company_name}`;
        },

        changeMonthlySalaryDisplay() {
            this.form.monthly_salary_display = !this.form.monthly_salary_display;
        },
	}
}
</script>
