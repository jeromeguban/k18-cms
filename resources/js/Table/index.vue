<template>
	<div>

		<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
			<h2 class="text-lg font-medium mr-auto">
				<slot name="label"></slot>
			</h2>
			<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
				<slot v-if="addNewBtn" name="add-new">
					<a href="javascript:;" data-toggle="modal" :data-target="modalIdentifier"
						class="btn btn-primary shadow-md mr-2"><svg class="fill-current w-3 h-3 mr-2"
							xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path
								d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
						</svg>{{ addNewBtnName }}</a>
				</slot>
				<slot v-if="additionalBtn" name="additional-button">
					<a href="javascript:;" data-toggle="modal" :data-target="additionalBtnModalIdentifier"
						class="btn btn-primary shadow-md mr-2">
						<!-- <svg class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z"/></svg> -->
						{{ additionalBtnName }}
					</a>
				</slot>
				<slot name="redirection-button" />
			</div>
		</div>
		<!-- BEGIN: HTML Table Data -->
		<div class="intro-y box p-5 mt-5">
			<div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
				<form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">
					<slot name="additionals" />
				</form>
				<div class="flex mt-5 sm:mt-0">
					<form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">
						<slot name="search-filter" />
					</form>
					<div class="w-56 relative text-gray-700 dark:text-gray-300 ">
						<input @search="searchData" type="search" class="form-control w-56 pr-10 placeholder-theme-13"
							placeholder="Search...">
						<i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto scrollbar-hidden mt-6">
				<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
					<table class="table  -mt-2" >
						<thead>
							<tr>
								<th class=" uppercase text-left" v-for="(field, name, index) in fields" :index="index"
									v-if="getColumnName(field) != 'Actions'" @click.prevent="sortable(field, name)">{{
										getColumnName(field)
									}}</th>
								<th class=" uppercase text-left" v-for="(field, name, index) in fields" :index="index"
									v-if="getColumnName(field) == 'Actions'" @click.prevent="sortable(field, name)">{{
										getColumnName(field)
									}}</th>
							</tr>
						</thead>
						<tbody >
							<tr v-if="table.isLoading() && link">
								<td :colspan="column_no">
									<div class="relative pt-24 mb-16">

										<loader :isLoading="table.isLoading()" object="#1C3FAA" color1="#1C3FAA"
											color2="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
											opacity="0" name="dots"></loader>

									</div>
								</td>
							</tr>

							<tr v-if="table.data.total == 0">
								<td :colspan="column_no" class="h-64">
									<div class="h-full flex items-center">
										<div class="mx-auto text-center">
											<div class="overflow-hidden mx-auto">
												<img alt="No-Result" class="rounded-md w-36 h-36"
													src="images/no-result.png">
											</div>
											<div class="mt-2">
												<div class="font-medium">No Result to Show.</div>
											</div>
										</div>
									</div>
								</td>
							</tr>

							<template v-for="(item, index) in table.data.data" class="border border-gray-200">
								<tr class="border-t border-dotted border-gray-300">
									<template v-for="(name, key) in fields">
										<td class="btn-container" v-if="$scopedSlots[key]">
											<div v-if="name == 'Actions'" class="flex justify-left flex-wrap">
												<slot :name="key" :item="item" :index="index"></slot>
											</div>
											<div v-if="name != 'Actions'" class="flex justify-left flex-wrap">
												<slot :name="key" :item="item" :index="index"></slot>
											</div>
										</td>
										<td v-else>
											<div class="parent-div flex flex-col mt-2">
												<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
												</div>
                        <div v-if="validateName(name) == 'true'" class="content-div flex-1">
                          {{ item[key] | moneyFormat }}
												</div>

                        <div v-else class="content-div flex-1" v-html="item[key]">
												</div>

												<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
												</div>
											</div>
										</td>
									</template>
								</tr>
								<!-- <tr v-if="other_fields">
									<td :colspan="column_no">
										<span class="italic underline text-primary">Some Fields & Actions:</span>
									</td>
								</tr> -->
								<tr v-if="other_fields">
									<template v-for="(name, key) in other_fields">
										<td class="btn-container" :colspan="getColspan()" v-if="$scopedSlots[key]">
											<div v-if="name == 'Actions'" class="flex justify-left flex-wrap mb-4">
												<slot :name="key" :item="item" :index="index"></slot>
											</div>
											<div v-if="name != 'Actions'" class="flex justify-left flex-wrap mb-4">
												<slot :name="key" :item="item" :index="index"></slot>
											</div>
										</td>
										<td v-else>
											<div class="parent-div flex flex-col mb-3">
												<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
												</div>
												<!-- <div class="content-div flex-1"
													v-html="validateName(name) == 'false' ? formatValue(item[key]) : item[key]">
												</div> -->

                        <div v-if="validateName(name) == 'true'" class="content-div flex-1">
                          {{ item[key] | moneyFormat }}
												</div>

                        <div v-else class="content-div flex-1" v-html="item[key]">
												</div>

												<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
												</div>
											</div>
										</td>
									</template>
								</tr>
							</template>

							<!-- <tr class="intro-x whites pace-nowrap " v-for="(item, index) in table.data.data"
								v-if="!table.isLoading()">
								<template v-for="(name, key) in fields">
									<td class="btn-container" v-if="$scopedSlots[key]">
										<div v-if="name == 'Actions'" class="flex justify-left flex-wrap">
											<slot :name="key" :item="item" :index="index"></slot>
										</div>
										<div v-if="name != 'Actions'" class="flex justify-left flex-wrap">
											<slot :name="key" :item="item" :index="index"></slot>
										</div>
									</td>
									<td v-else>
										<div class="parent-div flex flex-col">
											<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
											</div>
											<div class="content-div flex-1"
												v-html="validateName(name) == 'false' ? formatValue(item[key]) : item[key]">
											</div>
											<div class="label-div w-full break-words pr-4 font-semibold block">&nbsp;
											</div>
										</div>
									</td>
								</template>
							</tr> -->
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- BEGIN: Pagination -->
		<div
			class="intro-y col-span-12 flex flex-wrap mt-4 sm:flex-row sm:flex-nowrap items-center justify-center mt-4">

			<ul class="pagination">
				<li>
					<a class="pagination__link" href="" @click.prevent="showPrevious">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-chevrons-left w-4 h-4">
							<polyline points="11 17 6 12 11 7"></polyline>
							<polyline points="18 17 13 12 18 7"></polyline>
						</svg>
					</a>
				</li>
				<li>
					<a class="pagination__link" href="" @click.prevent="showPrevious">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-chevron-left w-4 h-4">
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</a>
				</li>

				<li class=" flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
					<a class=" pagination__link items-center justify-center ml-2"
						:class="{ 'pagination__link pagination__link--active ml-2': number == table.data.current_page }"
						v-for="(number, index) in paginationNumbers" @click.prevent="getPage(number)" :index="index">
						{{ number }}
					</a>
				</li>

				<li>
					<a class="pagination__link" href="" @click.prevent="showNext">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-chevron-right w-4 h-4">
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</a>
				</li>
				<li>
					<a class="pagination__link" href="" @click.prevent="showNext">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-chevrons-right w-4 h-4">
							<polyline points="13 17 18 12 13 7"></polyline>
							<polyline points="6 17 11 12 6 7"></polyline>
						</svg>
					</a>
				</li>
			</ul>

			<div class="hidden md:block mx-4 text-gray-600">Showing {{ beginning }} to {{ end }} of {{
				table.data.total
					|| 0
			}} entries</div>

			<select class="hidden md:block mx-4 text-gray-600 form-select box w-20" @change="setRowPerPage">
				<option :value="count" v-for="(count, index) in table.row_per_pages" :index="index">{{ count }}</option>
			</select>
		</div>
		<!-- END: Pagination -->
	</div>


</template>
<script>
import Table from '../Core/Table';
export default {
	props: {
		fields: {
			type: Object,
			required: true,
		},
		other_fields: {
			type: Object,
			default: null
		},

		link: {
			type: String,
			required: true,
		},
		params: {
			type: Object,
			default() {
				return {

				}
			}
		},
		id: {
			type: String,
			default: ''
		},
		addNewBtn: {
			type: Boolean,
			default: true,
		},

		addNewBtnName: {
			type: String,
			default: 'Add New',
		},

		additionalBtn: {
			type: Boolean,
			default: false,
		},

		additionalBtnName: {
			type: String,
			default: 'I need a name!!',
		},

		modalIdentifier: {
			type: String,
			default: 'I need a name!!',
		},

		additionalBtnModalIdentifier: {
			type: String,
			default: 'I need a name!!',
		},
	},


	data() {
		return {
			isLoading: false,
			column_no: 0,
			table: new Table(this.link, { params: this.params }, (this.$route.query && this.$route.query.page ? this.$route.query.page : 1))
		}
	},

	computed: {
		'beginning'() {
			return this.table.data.from ? this.table.data.from : 0;
		},
		'end'() {
			return this.table.data.to ? this.table.data.to : 0;
		},
		'lastPage'() {
			let total = this.table.data.total / this.table.data.per_page
			if (total < 1)
				return 1;

			if (total % 1 != 0)
				return parseInt(total + 1);

			return total;

		},
		'paginationNumbers'() {
			let from = this.table.data.current_page - this.config.offset;
			if (from < 1)
				from = 1;

			let to = from + (this.config.offset * 2);
			if (to >= this.lastPage)
				to = this.lastPage;

			let array = [];
			while (from <= to) {
				array.push(from);
				from++;
			}

			return array;

		},
		'config'() {
			return {
				offset: 2,
				previousText: 'Prev',
				nextText: 'Next',
			};
		},


	},

	watch: {
		'table.data.current_page'() {
			let current_page = this.table.data.current_page;
			let query_page = this.$route.query.page;

			if (current_page != query_page) {
				this.$router.push({
					path: this.$route.path,
					query: Object.assign({}, this.$route.query, {
						page: current_page
					})
				});
			}

		},
		'link'() {
			this.table = new Table(this.link, {
				params: this.params
			}, (this.$route.query && this.$route.query.page ? this.$route.query.page : 1))

		}
	},

	mounted() {
		let id = this.id ? `reload-${this.id}` : 'reload'
		app.$on(id, () => {
			this.table.get();
		});
	},

	created() {
		this.getFieldsCount(); 
	},

	methods: {
		validateName(name) {

			let data = [
        "Unit Price",
        "SRP",
        "Discounted Price",
        "Sub Total",
        "Total Discount Price",
        "Total Amount",
        "Value",
        "Minimum Purchase Requirements",
        "Total Price",
        "Amount",
        "Sub Total Amount",
        "Discount on Total Amount",
        "Total Sold Amount",
        "Total Costs",
        "Price",
        "Commission",
        "Bid Amount",
        "Retail Price",
        "Item Price",
        "Ordered Total",
        "Total Order Amount",
        "Shipping Fee",
        "Shipmates Fee",
        "Receivable Amount",
        "Payable Amount"
			];

			if (data.indexOf(name) >= 0) {
				return "true";

			} else {

				return "false";

			}

		},

		formatValue(value) {

			let val = (value / 1).toFixed(2).replace('.', ',')

			let data = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")

			if (data == "NaN") {

				return value;

			} else if (!value) {

				return "N/A";

			} else {

				return data;
			}

		},


		// Pagination
		showPrevious() {
			if (this.table.data.current_page > 1) {
				this.getPage(this.table.data.current_page - 1);
			}
		},
		showNext() {
			// console.log(this.lastPage);
			if (this.table.data.current_page < this.lastPage) {
				this.getPage(this.table.data.current_page + 1)
			}
		},
		// End Pagination

		// Search and Row per page
		setRowPerPage({ target }) {
			this.table.rowPerPage(target.value);
		},

		searchData({ target }) {
			this.table.setSearch(target.value);
		},
		// End Search and Row per page

		// Sortable
		sortable(field, name) {
			if (!field.sortable)
				return;

			this.table.sort(name);

		},

		isSortable(field) {
			if (field.sortable)
				return true;
		},

		getColumnName(field) {
			if (typeof field === 'object')
				return field.column_name

			return field;
		},
		// End Sortable
		getPage(page) {

			this.table.get(page);
		},

		getFieldsCount() {
			this.column_no = Object.keys(this.fields).length;
		},

		getColspan() {
			return Math.floor(this.column_no / Object.keys(this.other_fields).length)
		}
	}
}
</script>
