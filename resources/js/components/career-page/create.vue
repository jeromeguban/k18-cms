<template>
	<div>
		<div ref="close" id="careers-create" class="modal" tabindex="-1" aria-hidden="true">
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

                                <input-file id="banner" class="mt-4" label="Desktop Banner" name="banner" :multiple="false"
                                    v-model="form.banner"
                                    :errorMessage="form.errors.has('banner') ? form.errors.get('banner') : ''"
                                    accept="image/png, image/jpeg" />

                                <input-file id="mobile_banner" class="mt-4" label="Mobile Banner" name="mobile banner"
                                    :multiple="false" v-model="form.mobile_banner"
                                    :errorMessage="form.errors.has('mobile_banner') ? form.errors.get('mobile_banner') : ''"
                                    accept="image/png, image/jpeg" />

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
                                <input v-model="form.employment_type" type="text" name="employment_type" class="form-control" placeholder="Full-time, Part-time etc..">


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
                                    Store Name
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('work_location')" v-html="form.errors.get('work_location')" />
                                </label>
                                <input v-model="form.work_location[0].store_name" type="text" name="store_name" class="form-control" placeholder="Store Name">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Work Location
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('work_location')" v-html="form.errors.get('work_location')" />
                                </label>
                                <input v-model="form.work_location[0].address" type="text" name="address" class="form-control" placeholder="Work Location">


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
                                <input v-model="form.job_requirements[0].education_level" type="text" name="job_requirements" class="form-control" placeholder="Education Level">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Field of Studies
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_requirements.field_of_studies')" v-html="form.errors.get('job_requirements.field_of_studies')" />
                                </label>
                                <input v-model="form.job_requirements[0].field_of_studies" type="text" name="job_requirements" class="form-control" placeholder="Field of Studies">

                                <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                    Year(s) of Experience
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('job_requirements.year_of_experience')" v-html="form.errors.get('job_requirements.year_of_experience')" />
                                </label>
                                <input v-model="form.job_requirements[0].year_of_experience" type="text" name="job_requirements" class="form-control" placeholder="Year Of Experience">

                                <div class="flex w-1/2 mt-4">
                                    <a href="" @click.prevent="addRow()"
                                        class="btn btn-primary btn-primary-shadow mt-4 md:mt-0 "><svg
                                            class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
                                        </svg>Add HR Email</a>
                                </div>

                                <span class="text-red-500 my-2 flex items-center text-2xs"
                                v-if="form.errors.has('receiver')" v-text="form.errors.get('receiver')" />

                                <div class="sm:mt-6 mt-0">
                                    <div class="flex w-full" v-for="(receiver, index) in form.receiver">

                                        <div class="flex justify-center w-full ">
                                            <div class="flex w-8 py-3 pl-3">
                                                <span>{{ index + 1 }}</span>
                                            </div>
                                            <div class="w-full ml-4">
                                                <input v-model="receiver.email" type="text"
                                                    class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
                                                    placeholder="Email">

                                                <span class="text-red-500 my-2 flex items-center text-2xs"
                                                    v-if="form.errors.has('receiver.'+index+'.email')"
                                                    v-text="form.errors.get('receiver.'+index+'.email')" />
                                            </div>

                                            <div class="flex flex-col w-full mt-4">
                                                <a href="">
                                                    <div class="flex items-center justify-center text-blue-600 "
                                                        @click.prevent="deleteRow(index)">
                                                        <svg class="fill-current w-4 h-4 mr-1" viewBox="0 0 320 512">
                                                            <path
                                                                d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
                                                        </svg>
                                                        <span>Remove</span>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>

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
	data() {
		return {
            form:new FormUpload({

                banner                 : '',
				mobile_banner          : '',
				position_title         : '',
				job_description        : '',
				employment_type        : '',
				position_level         : '',
				job_specialization     : '',
				job_role               : '',
				monthly_salary_display : false,
                receiver             : [],

                work_location          : [{
                    store_name          : '',
                    address             : '',
                }],

                monthly_salary         : [{
                    to   : '',
                    from : ''
                }],

                job_requirements       : [{
                    education_level    : '',
                    field_of_studies   : '',
                    year_of_experience : ''
                }]
			}),
			stores      : [],
            store       : null,
            isLoading   : false,

		}
	},

    watch : {
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
			this.form.post('/careers')
			.then(() => {
				 this.isLoading = false;
				 this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Bank successfully created!',
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

        addRow() {
            this.form.receiver.push({
                email: null
            })
        },

        deleteRow(index) {
            this.form.receiver.splice(index, 1)
        },

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
