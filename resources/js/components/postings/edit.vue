<template>

    <div ref="close" id="postings-edit" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <button ref="close" class="hidden" type="button" data-dismiss="modal"></button>
                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Edit Posting</h2>
                    <!-- <button class="btn btn-outline-secondary hidden sm:flex">
                    <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs
                </button> -->
                </div>

                <form class="">
                    <div class="modal-body p-10">
                        <div class="input-form">
                            <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                Posting Type
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('category')" v-html="form.errors.get('category')" />
                            </label>
                            <div class="col-sm-10">
                                <select
                                    class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                    name="bidding_type" v-model="posting_type">
                                    <option value="Wholesale">Wholesale</option>
                                    <option value="Expression of Interest">Expression of Interest</option>
                                    <option value="International Trade">International Trade</option>
                                </select>
                            </div>

                            <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                Item Category Type
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('item_category_type')"
                                    v-html="form.errors.get('item_category_type')" />
                            </label>

                            <div class="col-sm-10">
                                <select
                                    class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                    name="item_category_type" v-model="form.item_category_type">
                                    <option value="Automotives">Automotives</option>
                                    <option value="Industrial And Construction Equipments">Industrial And Construction
                                        Equipments</option>
                                    <option value="Real Estates">Real Estates</option>
                                </select>
                            </div>


                            <div class="sm:grid grid-cols-2 gap-2">
                                <div class="mt-3">
                                    <label class="form-label">
                                        Name
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
                                    </label>
                                    <input v-model="form.name" type="text" name="name" class="form-control"
                                        placeholder="">
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">
                                        Description
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('description')"
                                            v-html="form.errors.get('description')" />
                                    </label>
                                    <input v-model="form.description" type="text" name="description"
                                        class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row">
                                <div class="w-1/2 ml-1 mr-1">
                                    <label class="form-label w-full flex flex-col sm:flex-row mt-4 ml-1">
                                        Categories
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('category')" v-html="form.errors.get('category')" />
                                    </label>
                                    <multiselect v-model="category" :multiple="true" :options="categories"
                                        :custom-label="getCategoryLabel" name="getCategoryLabel"
                                        customMaxWidth="100%" />
                                </div>
                                <div class="w-1/2 ml-1 mr-1" v-if="sub_categories.length > 0">
                                    <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                        Sub Categories
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('sub_category')"
                                            v-html="form.errors.get('sub_category')" />
                                    </label>
                                    <multiselect v-model="sub_category" :multiple="true" :options="sub_categories"
                                        :custom-label="getSubCategoryLabel" name="getSubCategoryLabel"
                                        customMaxWidth="100%" />
                                </div>
                                <div class="w-1/2 ml-1 mr-1">
                                    <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                        Brands
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('brand')" v-html="form.errors.get('brand')" />
                                    </label>
                                    <multiselect v-model="brand" :multiple="true" :options="brands"
                                        :custom-label="getBrandLabel" name="getBrandLabel" customMaxWidth="100%" />
                                </div>
                            </div>

                            <div class="sm:grid grid-cols-2 gap-2">
                                <div class="mt-3">
                                    <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                        Unit Price
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('unit_price')"
                                            v-html="form.errors.get('unit_price')" />
                                    </label>
                                    <input v-model="form.unit_price" type="number" name="unit_price"
                                        class="form-control" placeholder="">
                                </div>

                                <div class="mt-3">
                                    <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                        Location
                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                            v-if="form.errors.get('location')" v-html="form.errors.get('location')" />
                                    </label>
                                    <input v-model="form.location" type="text" name="location" class="form-control"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="flex w-1/2 mt-4">
                                <a href="" @click.prevent="addRow()"
                                    class="btn btn-primary btn-primary-shadow mt-4 md:mt-0 "><svg
                                        class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z" />
                                    </svg>Add Contact Person</a>
                            </div>

                            <span class="text-red-500 my-2 flex items-center text-2xs"
                                v-if="form.errors.has('viewing_details')" v-text="form.errors.get('viewing_details')" />

                            <div class="sm:mt-6 mt-0">
                                <div class="flex w-full" v-for="(viewing_details, index) in form.viewing_details">

                                    <div class="flex justify-center w-full ">
                                        <div class="flex w-8 py-3 pl-3">
                                            <span>{{ index + 1 }}</span>
                                        </div>
                                        <div class="w-full ml-4">
                                            <input v-model="viewing_details.contact_person" type="text"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
                                                placeholder="Contact Person">

                                            <span class="text-red-500 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('viewing_details.'+index+'.contact_person')"
                                                v-text="form.errors.get('viewing_details.'+index+'.contact_person')" />
                                        </div>

                                        <div class="w-full ml-4">
                                            <input v-model="viewing_details.contact_number" type="text"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
                                                placeholder="Contact Number">

                                            <span class="text-red-500 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('viewing_details.'+index+'.contact_number')"
                                                v-text="form.errors.get('viewing_details.'+index+'.contact_number')" />
                                        </div>

                                        <div class="w-full ml-4">
                                            <input v-model="viewing_details.contact_email" type="text"
                                                class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 mt-2 w-full "
                                                placeholder="Contact Email">

                                            <span class="text-red-500 my-2 flex items-center text-2xs"
                                                v-if="form.errors.has('viewing_details.'+index+'.contact_email')"
                                                v-text="form.errors.get('viewing_details.'+index+'.contact_email')" />
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

                            <label class="form-label w-full flex flex-col sm:flex-row mt-4">
                                Extended Description
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('extended_description')"
                                    v-html="form.errors.get('extended_description')" />
                            </label>
                            <vue-editor v-model="form.extended_description"></vue-editor>
                        </div>
                        <vue-snotify></vue-snotify>
                        <loader v-if="isLoading" object="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793"
                            opacity="5" disableScrolling="false" name="dots"></loader>
                    </div>

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


</template>
<script>
import { VueEditor } from "vue2-editor";
export default {
	props:['posting'],
	components : { VueEditor },
	data() {
		return {
			form 	       : {},
            posting_type   : null,
			categories_id  : [],
            brand          : [],
            brands         : [],
            category       : [],
            categories     : [],
            sub_category   : [],
            sub_categories : [],
            isLoading      : false,
		}
	},

	created() {
		this.form = new Form(this.posting);
        this.posting_type = this.form.category;
        this.form.viewing_details = JSON.parse(this.form.viewing_details);

        new Promise((resolve, reject) => {
            this.getCategories();
            this.getBrands();
            resolve();
        }).then(() => {
            this.showPosting();
        })
	},

	watch:{
        'posting'() {

           	this.form                   = new Form(this.posting);
            this.posting_type           = this.form.category;
            this.form.viewing_details   = JSON.parse(this.form.viewing_details);

            new Promise((resolve, reject) => {
                this.getCategories();
                this.getBrands();
                resolve();
            }).then(() => {
                this.brand = [];
                this.category = [];
                this.sub_category = [];
                this.showPosting();
            })
        },

        'posting_type' () {
            this.form.category = this.posting_type;
        },

        'category'() {
            this.form.categories    = this.category.map(category => category.category_id)
            this.categories_id      = this.category.map(category => category.category_id)
            this.getSubCategories()
        },
        'sub_category'() {
            this.form.sub_categories    = this.sub_category.map(sub_category => sub_category.sub_category_id)
        },

        'brand'() {
            this.form.brands    =  this.brand.map(brand => brand.brand_id)
        },

	},

	methods: {
		submit() {
            this.isLoading = true;
			this.form.patch(`/postings/${this.posting.posting_id}`)
			.then(()=>{
               this.isLoading = false;
		       this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
					setTimeout(() => resolve({
						title: 'Success!',
						body: 'Posting Successfuly Updated!',
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

		getCategories() {
            axios.get('/categories')
            .then(({data})=>{
                this.categories = data;
            });
        },

        getSubCategories() {
            axios.get('/sub-categories?category_id='+this.categories_id)
            .then(({data})=>{
                this.sub_categories = data;
            });
        },

        getBrands() {
            axios.get('/brands')
            .then(({data})=>{
                this.brands = data;
            });
        },

        getBrandLabel({brand_name}) {

            return `${brand_name}`;
        },

        getCategoryLabel({category_name}) {

            return `${category_name}`;
        },

        getSubCategoryLabel({sub_category_name}) {

            return `${sub_category_name}`;
        },

        showPosting() {

            axios.get(`/postings/${this.posting.posting_id}`)
            .then(({data})=>{
                this.form = new Form(data);
                this.form.viewing_details = JSON.parse(this.form.viewing_details) || [];
                this.form.brands = JSON.parse(this.form.brands) || [];
                this.form.categories = JSON.parse(this.form.categories) || [];
                this.form.sub_categories = JSON.parse(this.form.sub_categories) || [];

            })
            .catch();

            for (let i = 0; i < JSON.parse(this.form.brands).length; i++) {
                axios.get('/brands/'+JSON.parse(this.form.brands)[i])
                .then(({data})=>{
                    this.brand.push(data);
                });
            }

            for (let i = 0; i < JSON.parse(this.form.categories).length; i++) {
                axios.get('/categories/'+JSON.parse(this.form.categories)[i])
                .then(({data})=>{
                    this.category.push(data);
                });
            }

            for (let i = 0; i < JSON.parse(this.form.sub_categories).length; i++) {
                axios.get('/sub-categories/'+JSON.parse(this.form.sub_categories)[i])
                .then(({data})=>{
                    this.sub_category.push(data);
                });
            }
        },

        addRow() {

            this.form.viewing_details.push({
                contact_person: null,
                contact_number: null,
                contact_email: null
            })
		},

        deleteRow(index) {

            this.form.viewing_details.splice(index, 1)
        },

        closeModal() {
			setTimeout(() => this.$refs.close.click(), 3000);
		},
	}
}
</script>
