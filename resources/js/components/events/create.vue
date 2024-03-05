<template>
	<div style="z-index: 100;">
		<div ref="close" id="events-create" class="modal" tabindex="-1" aria-hidden="true" >
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- BEGIN: Modal Header -->
					<div class="modal-header bg-primary-1 text-theme-2">
						<h2 class="font-medium text-base mr-auto">Create New Event</h2>
					</div>
					<form class="">
						<div class="modal-body p-10">
							<div class="input-form">

								<label class="form-label w-full flex flex-col sm:flex-row">
									Event Name
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('event_name')" v-html="form.errors.get('event_name')" />
								</label>
								<input v-model="form.event_name" type="text" name="event_name" class="form-control"
									placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-2">
									Event Description
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('description')" v-html="form.errors.get('description')" />
								</label>
								<input v-model="form.description" type="text" name="description" class="form-control"
									placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">Category</label>
								<div class="col-sm-10">
									<select
										class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
										name="type" v-model="form.category">
										<option value="Buy Now">Buy Now</option>
										<option value="Live Selling">Live Selling</option>
									</select>

									<span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
										v-if="form.errors.has('category')" v-text="form.errors.get('category')"></span>
								</div>

								<div class="flex" v-if="form.category == 'Live Selling'">
									<div class="w-24 mr-1">
										<label for="validation-form-2"
											class="form-label w-full flex flex-col sm:flex-row mt-3"
											@change="changeTopUp">
											Top Up
										</label>

										<label for="settings" class="cursor-pointer">
											<input type="checkbox" class="show-code form-check-switch mr-0 ml-3 mt-1"
												:checked="form.top_up" @change="changeTopUp">
										</label>
									</div>
									<div class="w-full mr-1" v-if="form.top_up">
										<label class="form-label w-full flex flex-col sm:flex-row mt-3">
											Top Up Amount
											<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
												v-if="form.errors.get('top_up_amount')"
												v-html="form.errors.get('top_up_amount')" />
										</label>
										<input v-model="form.top_up_amount" type="number" name="top_up_amount"
											class="form-control" placeholder="">
									</div>
								</div>

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Starting Time
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('starting_time')"
										v-html="form.errors.get('starting_time')" />
								</label>
								
								<date-picker v-model="starting_time" type="datetime" class="w-full"></date-picker>

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Purchase Limit
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('purchase_limit')"
										v-html="form.errors.get('purchase_limit')" />
								</label>
								<input v-model="form.purchase_limit" type="number" name="purchase_limit"
									class="form-control" placeholder="">

								<label class="form-label w-full flex flex-col sm:flex-row mt-3">
									Terms and Condition :
									<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
										v-if="form.errors.get('term_id')" v-html="form.errors.get('term_id')" />
								</label>
								<multiselect v-model="term" :options="terms" name="terms" :custom-label="getTermLabel"
									label="getTermLabel" customMaxWidth="100%"></multiselect>


								<label class="form-label w-full flex flex-col sm:flex-row mt-3">Event Type</label>
								<div class="col-sm-10">
									<select
										class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
										name="type" v-model="form.type">
										<option value="Public">Public</option>
										<option value="Private">Private</option>
									</select>

									<span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
										v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
								</div>
								<div class="flex">
									<div class="w-24 mr-1">
										<label for="validation-form-2"
											class="form-label w-full flex flex-col sm:flex-row mt-3"
											@change="changeRestriction">
											Restriction
										</label>

										<label for="settings" class="cursor-pointer">
											<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
												:checked="form.restriction" @change="changeRestriction">
										</label>
									</div>
									<div class="w-50 mr-1">
										<label for="validation-form-2"
											class="form-label w-full flex flex-col sm:flex-row mt-3"
											@change="changeAutoApproveCustomer">
											Auto Approve Customer
										</label>

										<label for="settings" class="cursor-pointer">
											<input type="checkbox" class="show-code form-check-switch mr-0 ml-3"
												:checked="form.auto_approve" @change="changeAutoApproveCustomer">
										</label>
									</div>
								</div>

								<div class="mt-2" v-if="form.type == 'Private'">
									<label class="form-label w-full flex flex-col sm:flex-row mt-2">
										Title
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('access_request_info.title')"
											v-html="form.errors.get('access_request_info.title')" />
									</label>

									<input v-model="form.access_request_info.title" type="text" class="form-control"
										placeholder="">

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">
										From
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('access_request_info.from')"
											v-html="form.errors.get('access_request_info.from')" />
									</label>
									<date-picker  value-type="format" format="YYYY/MM/DD" v-model="from" type="datetime" class="w-full"></date-picker>
								

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">
										To
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('access_request_info.to')"
											v-html="form.errors.get('access_request_info.to')" />
									</label>
									<date-picker  value-type="format" format="YYYY/MM/DD" v-model="to" type="datetime" class="w-full"></date-picker>
									

									<label class="form-label w-full flex flex-col sm:flex-row mt-2">
										Hotlines
										<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
											v-if="form.errors.get('access_request_info.hotlines')"
											v-html="form.errors.get('access_request_info.hotlines')" />
									</label>
									<input v-model="form.access_request_info.hotlines" type="text" class="form-control"
										placeholder="">

									<div class="flex w-1/2 mt-4">
										<a href="" @click.prevent="addRow()"
											class="btn btn-primary btn-primary-shadow mt-4 md:mt-0 "><svg
												class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
												viewBox="0 0 512 512">
												<path
													d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
											</svg>Add Valid Domain</a>
									</div>

									<div class="sm:mt-6 mt-0">
										<div class="flex w-full" v-for="(valid_domains, index) in form.valid_domains">
											<div class="flex justify-center w-full ">
												<div class="flex w-8 py-3 pl-3">
													<span>{{ index + 1 }}</span>
												</div>
												<div class="w-full ml-4">
													<input v-model="form.valid_domains[index]" type="text"
														class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
														placeholder="Domain">

													<span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
														v-if="form.errors.get('valid_domains.'+index)"
														v-html="form.errors.get('valid_domains.'+index)" />

												</div>
												<div class="flex flex-col w-full mt-4">
													<a href="">
														<div class="flex items-center justify-center text-blue-600 "
															@click.prevent="deleteRow(index)">
															<svg class="fill-current w-4 h-4 mr-1"
																viewBox="0 0 320 512">
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
<style>
.mx-datepicker-popup {
	z-index: 99999;
}
.mx-datepicker {
	width: 100%;
}

</style>
<script>
import moment from 'moment';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
export default {
	components: { DatePicker, moment },
	data() {
		return {
			form: new Form({
				event_name: '',
				type: 'Public',
				description: '',
				purchase_limit: 0,
				term_id: '',
				starting_time: '',
				restriction: false,
				auto_approve: false,
				valid_domain: false,
				access_request_info: {
					title: "",
					from: "",
					to: "",
					hotlines: "8634-0526 | 0999-886-2137"
				},
				valid_domains: [],
				category : null,
				top_up : false,
				top_up_amount : 0
			}),
			starting_time: null,
			from: null,
			to: null,
			term: null,
			terms: [],
			isLoading: false,
		}
	},

	created() {
		this.getTerms();
	},

	watch: {
		'term'() {
			this.term.id = this.form.term_id;
		},

		'from'() {
			this.form.access_request_info.from = moment(this.from).format('YYYY/MM/DD') ? moment(this.from).format('YYYY/MM/DD') : null;
		},

		'to'() {
			this.form.access_request_info.to = moment(this.to).format('YYYY/MM/DD') ? moment(this.to).format('YYYY/MM/DD') : null;
		},

		'starting_time'() {
			this.form.starting_time = moment(this.starting_time).format("YYYY-MM-DD HH:mm:ss") ? moment(this.starting_time).format("YYYY-MM-DD HH:mm:ss") : null;
		},

	},

	methods: {
		submit() {
			// this.isLoading = true;
			this.form.post('/events')
				.then(() => {
					this.isLoading = false;
					this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
						setTimeout(() => resolve({
							title: 'Success!',
							body: 'Event successfully created!',
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
				.catch((error) => {
					if(error)
						this.isLoading = false;
				});
		},

		getTerms() {
			axios.get('/terms')
				.then(({ data }) => {
					this.terms = data;
				});
		},

		getTermLabel({ name }) {
			return `${name}`;
		},

		addRow() {
			this.form.valid_domains.push([])
		},

		deleteRow(index) {
			this.form.valid_domains.splice(index, 1)
		},

		changeRestriction() {
			this.form.restriction = !this.form.restriction;
		},

		changeAutoApproveCustomer() {
			this.form.auto_approve = !this.form.auto_approve;
		},

		changeTopUp() {
			this.form.top_up = !this.form.top_up;
		},

		closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},

	},
}
</script>
