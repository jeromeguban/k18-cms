<template>
    <div ref="close" id="vouchers-edit" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header bg-primary-1 text-theme-2">
                    <h2 class="font-medium text-base mr-auto">Edit Voucher</h2>
                    <!-- <button class="btn btn-outline-secondary hidden sm:flex">

<i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs

</button> -->
                </div>

                <form class="">
                    <div class="modal-body p-10">

                        <div class="input-form">
                            <label class="form-label w-full flex flex-col sm:flex-row">
                                Terms & Conditions
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('term_id')" v-html="form.errors.get('term_id')" />
                            </label>
                            <multiselect v-model="term" :options="terms" :custom-label="termCustomLabel" name="term"
                                customMaxWidth="100%"></multiselect>
                            
                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">Voucher Type</label>
                                <div class="col-sm-10">
                                    <select
                                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                        name="voucher_type" v-model="form.voucher_type">
                                        <option value="Product">Product</option>
                                        <option value="Delivery">Delivery</option>
                                    </select>

                                    <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                        v-if="form.errors.has('voucher_type')" v-text="form.errors.get('voucher_type')"></span>
                                </div>

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Name
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('name')" v-html="form.errors.get('name')" />
                            </label>
                            <input v-model="form.name" type="text" name="name" class="form-control" placeholder="">

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Code
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('code')" v-html="form.errors.get('code')" />
                            </label>
                            <input v-model="form.code" type="text" name="code" class="form-control" placeholder="">

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">Type</label>
                            <div class="col-sm-10">
                                <select
                                    class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                    name="type" v-model="form.type">
                                    <option value="Percentage">Percentage</option>
                                    <option value="Amount">Amount</option>
                                </select>

                                <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                    v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                            </div>

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Value
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('value')" v-html="form.errors.get('value')" />
                            </label>
                            <input v-model="form.value" type="number" name="value" class="form-control" placeholder="">

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Voucher Limit ( Note: Zero Value Means no Limit )
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('limit')" v-html="form.errors.get('limit')" />
                            </label>
                            <input v-model="form.limit" type="number" name="limit" class="form-control" placeholder="">


                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                    User Limit ( Note: Zero Value means no Limit )
                                    <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                        v-if="form.errors.get('limit_per_user')" v-html="form.errors.get('limit_per_user')" />
                                </label>
                                <input v-model="form.limit_per_user" type="number" name="limit_per_user" class="form-control"
                                    placeholder="">


                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Minimum Purchase Requirements
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('minimum_purchase_requirements')"
                                    v-html="form.errors.get('minimum_purchase_requirements')" />
                            </label>
                            <input v-model="form.minimum_purchase_requirements" type="number"
                                name="minimum_purchase_requirements" class="form-control" placeholder="">

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">Category</label>
                            <div class="col-sm-10">
                                <select
                                    class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                    name="category" v-model="form.category">
                                    <option value="Retail">Retail</option>
                                    <option value="Auction">Auction</option>
                                </select>

                                <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                    v-if="form.errors.has('category')" v-text="form.errors.get('category')"></span>
                            </div>

                            <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                Expiration Date
                                <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-theme-6 "
                                    v-if="form.errors.get('expiration_date')"
                                    v-html="form.errors.get('expiration_date')" />
                            </label>
                            <datetime format="MM/DD/YYYY h:i:s" v-model="form.expiration_date"></datetime>

                            <div class="mt-2">
                                <label for="validation-form-2 mt-6"
                                    class="form-label w-full flex flex-row sm:flex-row mt-3" @change="changeVoucher">
                                    Voucher Status
                                </label>
                                <div class="w-24 mt-2">
                                    <label for="settings" class="cursor-pointer">
                                        <input type="checkbox" class="show-code form-check-switch mr-0"
                                            :checked="form.active" @change="changeVoucher">
                                    </label>
                                </div>
                            </div>

                            <div class="mt-2">
                                <label for="validation-form-2 mt-6"
                                    class="form-label w-full flex flex-row sm:flex-row mt-3"
                                    @change="changeApplicableToDiscounted">
                                    Applicable to Discounted Items?
                                </label>
                                <div class="w-24 mt-2">
                                    <label for="settings" class="cursor-pointer">
                                        <input type="checkbox" class="show-code form-check-switch mr-0"
                                            :checked="form.applicable_to_discounted"
                                            @change="changeApplicableToDiscounted">
                                    </label>
                                </div>
                            </div>

                            <div class="mt-2">
                                    <label for="validation-form-2 mt-6"
                                        class="form-label w-full flex flex-row sm:flex-row mt-3"
                                        @change="changeApplicableForCashOnDelivery">
                                        Cash On Delivery?
                                    </label>
                                    <div class="w-24 mt-2">
                                        <label for="settings" class="cursor-pointer">
                                            <input type="checkbox" class="show-code form-check-switch mr-0"
                                                :checked="form.applicable_for_cash_on_delivery"
                                                @change="changeApplicableForCashOnDelivery">
                                        </label>
                                    </div>
                                </div>

                            <div class="mt-2">
                                    <label for="validation-form-2 mt-6"
                                        class="form-label w-full flex flex-row sm:flex-row mt-3"
                                        @change="changeAutoApply">
                                        Auto Apply?
                                    </label>
                                    <div class="w-24 mt-2">
                                        <label for="settings" class="cursor-pointer">
                                            <input type="checkbox" class="show-code form-check-switch mr-0"
                                                :checked="form.auto_apply"
                                                @change="changeAutoApply">
                                        </label>
                                    </div>
                            </div>

                            <div class="mt-4 text-lg">
                                <span>Voucher Settings</span>
                            </div>

                            <div class="flex w-full"
                                v-for="(voucher_settings, voucher_setting_index) in form.voucher_settings">
                                <div class="flex justify-left w-full ">
                                    <div class="">
                                        <label for="validation-form-2"
                                            class="form-label w-full flex flex-row sm:flex-row mt-3"
                                            @change="changeVoucherSetting(voucher_setting_index)">
                                            Status
                                        </label>
                                        <div class="mt-4">
                                            <label :for="form.voucher_settings.active" class="cursor-pointer">
                                                <input type="checkbox" class="show-code form-check-switch mr-0"
                                                    :checked="form.voucher_settings[voucher_setting_index].active"
                                                    @change="changeVoucherSetting(voucher_setting_index)">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="w-full mt-1 ml-6">
                                        <label class="form-label w-full flex flex-col sm:flex-row mt-2">
                                            Restriction Name
                                        </label>
                                        <input v-model="voucher_settings.restriction_name" type="text"
                                            class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none flex-0 h-10 w-full "
                                            placeholder="Restriction Name" disabled>
                                        <span class="text-red-500 my-2 flex items-center text-2xs"
                                            v-if="form.errors.has('voucher_settings.' + voucher_setting_index + '.restriction_name')"
                                            v-text="form.errors.get('voucher_settings.' + voucher_setting_index + '.restriction_name')" />
                                    </div>

                                    <div class="w-full ml-4">

                                        <div class="mt-2" v-if="!form.voucher_settings[voucher_setting_index].active">
                                            <label class="form-label w-full flex flex-col sm:flex-row  ml-1">
                                                Activate the Toggle to Enable
                                                {{ form.voucher_settings[voucher_setting_index].restriction_name |
                                                        capitalising
                                                }}
                                            </label>
                                            <input-text :disabled="true" name="disable" />
                                        </div>

                                        <div class="mt-3"
                                            v-show="form.voucher_settings[voucher_setting_index].restriction_name == 'categories' && form.voucher_settings[voucher_setting_index].active">
                                            <label class="form-label w-full flex flex-col sm:flex-row  ml-1">
                                                Select {{ form.voucher_settings[voucher_setting_index].restriction_name
                                                        |
                                                        capitalising
                                                }}
                                            </label>
                                            <multiselect v-model="category" :multiple="true" :options="categories"
                                                :custom-label="getCategoryLabel" name="getCategoryLabel"
                                                customMaxWidth="100%" />
                                        </div>

                                        <div class="mt-3"
                                            v-show="form.voucher_settings[voucher_setting_index].restriction_name == 'items' && form.voucher_settings[voucher_setting_index].active">
                                            <label class="form-label w-full flex flex-col sm:flex-row  ml-1">
                                                Select {{ form.voucher_settings[voucher_setting_index].restriction_name
                                                        |
                                                        capitalising
                                                }}
                                            </label>
                                            <multiselect v-model="item" :multiple="true" :options="items"
                                                :custom-label="getItemLabel" name="getItemLabel"
                                                customMaxWidth="100%" />
                                        </div>

                                        <div class="mt-3"
                                            v-show="form.voucher_settings[voucher_setting_index].restriction_name == 'tags' && form.voucher_settings[voucher_setting_index].active">
                                            <label class="form-label w-full flex flex-col sm:flex-row  ml-1">
                                                Select {{ form.voucher_settings[voucher_setting_index].restriction_name
                                                        |
                                                        capitalising
                                                }}
                                            </label>
                                            <multiselect v-model="tag" :multiple="true" :options="tags"
                                                :custom-label="getTagLabel" name="getTagLabel" customMaxWidth="100%" />
                                        </div>

                                        <div class="mt-3"
                                            v-show="form.voucher_settings[voucher_setting_index].restriction_name == 'stores' && form.voucher_settings[voucher_setting_index].active">
                                            <label class="form-label w-full flex flex-col sm:flex-row  ml-1">
                                                Select {{ form.voucher_settings[voucher_setting_index].restriction_name
                                                        |
                                                        capitalising
                                                }}
                                            </label>
                                            <multiselect v-model="store" :multiple="true" :options="stores"
                                                :custom-label="getStoreLabel" name="getStoreLabel"
                                                customMaxWidth="100%" />
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="flex justify-left w-full mt-2">
                                <div class="flex flex-col  w-full">
                                    <label class="text-2xs font-semibold">Customer</label>
                                    <select
                                        class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                        name="filter" v-model="filter">
                                        <option value="Last Name">Last Name</option>
                                        <option value="First Name">First Name</option>
                                        <option value="Email">Email</option>
                                        <option value="Mobile No.">Mobile No.</option>
                                    </select>
                                </div>

                                <div v-if="filter" class="w-full ml-6">
                                    <label class=" font-semibold">Search Customer By {{ filter }}</label>
                                    <div class="flex items-center">
                                        <input v-on:keyup="searchCustomers" type="text"
                                            class="border border-solid border-gray-300 focus:border-indigo-300 px-2 py-2 rounded outline-none w-full flex-0 h-10 mt-1"
                                            v-model="q" @keypress.enter="searchCustomers">
                                    </div>

                                </div>

                                <div v-if="filter" class="w-full ml-6">
                                    <span class="font-semibold w-64"> Select Customer</span>
                                    <multiselect v-model="customer" :options="customers" name="customer"
                                        :custom-label='customerCustomLabel' customMaxWidth="100%"></multiselect>
                                </div>

                                <span class="text-red-500 mt-2 flex items-center text-2xs mb-4"
                                    v-if="form.errors.has('customer_id')"
                                    v-text="form.errors.get('customer_id')"></span>

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
</template>

<script>
import datetime from 'vuejs-datetimepicker';
export default {
    components: {
        datetime
    },
    props: ['voucher'],
    data() {
        return {
            form: new Form({
                term_id: this.voucher.term_id,
                name: this.voucher.name,
                code: this.voucher.code,
                type: this.voucher.type,
                voucher_type: this.voucher.voucher_type,
                value: this.voucher.value,
                limit: this.voucher.limit,
                limit_per_user: this.voucher.limit_per_user,
                total_usage: this.voucher.total_usage,
                minimum_purchase_requirements: this.voucher.minimum_purchase_requirements,
                category: this.voucher.category,
                expiration_date: this.voucher.expiration_date,
                active: this.voucher.active,
                auto_apply: this.voucher.auto_apply,
                applicable_to_discounted: this.voucher.applicable_to_discounted,
                applicable_for_cash_on_delivery: this.voucher.applicable_for_cash_on_delivery,
                voucher_settings: [{
                    restriction_name: "categories",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "items",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "tags",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "stores",
                    restriction_value: [],
                    active: false
                }
                ],
                customer_id: '',
            }),
            category: [],
            categories: [],
            item: [],
            items: [],
            tag: [],
            tags: [],
            store: [],
            stores: [],
            voucher_setting_index: 0,
            store_type: "Retail",
            isLoading: false,
            voucher_setting_categories: null,
            voucher_setting_items: null,
            voucher_setting_tags: null,
            voucher_setting_stores: null,
            voucher_settings: null,
            q: null,
            filter: 'Last Name',
            customer: null,
            customers: [],
            term: null,
            terms: [],
        }
    },
    created() {
        this.getVoucherSettings();
        this.getCategories();
        this.getItems();
        this.getTags();
        this.getStores();
        this.getCurrentCustomerVoucher();
        this.getTerms();
    },
    watch: {
        'voucher'() {
            this.form.term_id = this.voucher.term_id,
                this.form.name = this.voucher.name,
                this.form.code = this.voucher.code,
                this.form.type = this.voucher.type,
                this.form.voucher_type = this.voucher.voucher_type,
                this.form.value = this.voucher.value,
                this.form.limit = this.voucher.limit,
                this.form.limit_per_user = this.voucher.limit_per_user,
                this.form.total_usage = this.voucher.total_usage,
                this.form.category = this.voucher.category,
                this.form.expiration_date = this.voucher.expiration_date,
                this.form.active = this.voucher.active,
                this.form.auto_apply = this.voucher.auto_apply,
                this.form.applicable_to_discounted = this.voucher.applicable_to_discounted,
                this.form.applicable_for_cash_on_delivery = this.voucher.applicable_for_cash_on_delivery,
                this.minimum_purchase_requirements = this.voucher.minimum_purchase_requirements,
                this.form.voucher_settings = [{
                    restriction_name: "categories",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "items",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "tags",
                    restriction_value: [],
                    active: false
                },
                {
                    restriction_name: "stores",
                    restriction_value: [],
                    active: false
                }
                ];

            this.getVoucherSettings();
            this.getCurrentCustomerVoucher();
            this.getTerms();

        },

        'form.category'() {
            this.store_type = this.form.category;
            this.getStores();
        },

        'category'() {
            this.form.voucher_settings[0].restriction_value = this.category.map(category => category.category_id)
        },

        'item'() {
            this.form.voucher_settings[1].restriction_value = this.item.map(item => item.id)
        },

        'tag'() {
            this.form.voucher_settings[2].restriction_value = this.tag.map(tag => tag.id)
        },

        'store'() {
            this.form.voucher_settings[3].restriction_value = this.store.map(store => store.id)
        },

        'customer'() {
            this.form.customer_id = this.customer.customer_id;
        },

        'term'() {
            this.form.term_id = this.term.id;
        },
    },

    filters: {
        capitalising: function (data) {
            var capitalized = []
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            })
            return capitalized.join(' ')
        }
    },

    methods: {
        submit() {

            this.form.patch(`/vouchers/${this.voucher.id}`)
                .then(() => {
                    this.$snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                        setTimeout(() => resolve({
                            title: 'Success!',
                            body: 'Voucher Successfuly Updated!',
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
                .catch(() => {
                    this.isLoading = false;
                });
        },

        changeVoucher() {
            this.form.active = !this.form.active;
        },

        changeApplicableToDiscounted() {
            this.form.applicable_to_discounted = !this.form.applicable_to_discounted;
        },

        changeApplicableForCashOnDelivery() {
            this.form.applicable_for_cash_on_delivery = !this.form.applicable_for_cash_on_delivery;
        },

        changeAutoApply() {
            this.form.auto_apply = !this.form.auto_apply;
        },

        changeVoucherSetting(voucher_setting_index) {
            console.log(voucher_setting_index);
            this.form.voucher_settings[voucher_setting_index].active = !this.form.voucher_settings[voucher_setting_index].active;
        },

        getCategories() {
            axios.get('/categories')
                .then(({
                    data
                }) => {
                    this.categories = data;
                });

        },

        getCategoryLabel({
            category_name
        }) {

            return `${category_name}`;
        },

        getItems() {
            axios.get('/posting-items')
                .then(({
                    data
                }) => {
                    this.items = data;
                });
        },

        getItemLabel({
            sku
        }) {
            return `${sku}`;
        },

        getTags() {
            axios.get('/tags')
                .then(({
                    data
                }) => {
                    this.tags = data;
                });
        },

        getTagLabel({
            name
        }) {
            return `${name}`;
        },

        getStores() {
            axios.get('/stores?store_type=' + this.store_type)
                .then(({
                    data
                }) => {
                    this.stores = data;
                });
        },

        getStoreLabel({
            code,
            store_name
        }) {
            return `${code} - ${store_name}`;
        },

        getVoucherSettings() {
            axios.get('/voucher/' + this.voucher.id + '/settings')
                .then(({
                    data
                }) => {

                    this.voucher_settings = data;

                    // categories
                    this.voucher_setting_categories = data.filter((data) => {
                        return data.restriction_name == "categories"
                    }) ?? null;

                    if (this.voucher_setting_categories.length >= 1)
                        this.SetVoucherSettingCategoriesValue(data);

                    // items
                    this.voucher_setting_items = data.filter((data) => {
                        return data.restriction_name == "items"
                    }) ?? null;

                    if (this.voucher_setting_items.length >= 1)
                        this.SetVoucherSettingItemsValue(data);

                    // tags
                    this.voucher_setting_tags = data.filter((data) => {
                        return data.restriction_name == "tags"
                    }) ?? null;

                    if (this.voucher_setting_tags.length >= 1)
                        this.SetVoucherSettingTagsValue(data);

                    //stores
                    this.voucher_setting_stores = data.filter((data) => {
                        return data.restriction_name == "stores"
                    }) ?? null;

                    if (this.voucher_setting_stores.length >= 1)
                        this.SetVoucherSettingStoresValue(data);

                });
        },

        SetVoucherSettingCategoriesValue(data) {

            this.form.voucher_settings[0].active = this.voucher_setting_categories[0].active == 1 ? true : false;
            this.form.voucher_settings[0].restriction_value = this.voucher_setting_categories[0].restriction_value;

            for (let i = 0; i < JSON.parse(this.form.voucher_settings[0].restriction_value).length; i++) {
                axios.get('/categories/' + JSON.parse(this.voucher_setting_categories[0].restriction_value)[i])
                    .then(({
                        data
                    }) => {

                        const index = this.category.findIndex(object => object.id === data.id);
                        if (index === -1)
                            this.category.push(data);

                    });
            }
        },

        SetVoucherSettingItemsValue(data) {

            this.form.voucher_settings[1].active = this.voucher_setting_items[0].active == 1 ? true : false;
            this.form.voucher_settings[1].restriction_value = this.voucher_setting_items[0].restriction_value;

            for (let i = 0; i < JSON.parse(this.form.voucher_settings[1].restriction_value).length; i++) {
                axios.get('/posting-item/' + JSON.parse(this.voucher_setting_items[0].restriction_value)[i])
                    .then(({
                        data
                    }) => {
                        const index = this.item.findIndex(object => object.id === data.id);
                        if (index === -1)
                            this.item.push(data);
                    });
            }
        },

        SetVoucherSettingTagsValue(data) {

            this.form.voucher_settings[2].active = this.voucher_setting_tags[0].active == 1 ? true : false;
            this.form.voucher_settings[2].restriction_value = this.voucher_setting_tags[0].restriction_value;

            for (let i = 0; i < JSON.parse(this.form.voucher_settings[2].restriction_value).length; i++) {
                axios.get('/tags/' + JSON.parse(this.voucher_setting_tags[0].restriction_value)[i])
                    .then(({
                        data
                    }) => {

                        const index = this.tag.findIndex(object => object.id === data.id);
                        if (index === -1)
                            this.tag.push(data);
                    });
            }
        },

        SetVoucherSettingStoresValue(data) {

            this.form.voucher_settings[3].active = this.voucher_setting_stores[0].active == 1 ? true : false;
            this.form.voucher_settings[3].restriction_value = this.voucher_setting_stores[0].restriction_value;

            for (let i = 0; i < JSON.parse(this.form.voucher_settings[3].restriction_value).length; i++) {
                axios.get('/stores/' + JSON.parse(this.voucher_setting_stores[0].restriction_value)[i])
                    .then(({
                        data
                    }) => {
                        const index = this.store.findIndex(object => object.id === data.id);
                        if (index === -1)
                            this.store.push(data);
                    });
            }
        },

        getCurrentCustomerVoucher() {
            axios.get('/customer/' + this.voucher.id + '/voucher').then(({
                data
            }) => {
                this.customers = data;
                this.q = data[0].customer_lastname;
                this.form.customer_id = data[0].customer_id ?? null;
                this.customer = this.customers.find((customer) => {
                    return customer.customer_id == this.form.customer_id
                });
            }).catch((error) => {
                console.log(error);
            });
        },

        searchCustomers() {
            axios.get('/customers', {
                params: {
                    q: this.q,
                    filter: this.filter
                }
            }).then(({
                data
            }) => {
                this.customers = data;
            }).catch((error) => {
                console.log(error);
            });
        },

        customerCustomLabel({
            customer_firstname,
            customer_lastname,
            email
        }) {
            return `${customer_lastname}, ${customer_firstname} - ${email}`;
        },

        closeModal() {
            setTimeout(() => this.$refs.close.click(), 3000);
        },

        getTerms() {
            axios.get('/terms?type=voucher')
                .then((response) => {
                    this.terms = response.data;
                    this.getTermDetail();
                });
        },

        getTermDetail() {
            if (this.terms && this.form.term_id) {
                this.term = this.terms.find((term) => {
                    return term.id == this.form.term_id
                });
            }
        },

        termCustomLabel({
            name
        }) {
            return `${name}`;
        },
    }
}
</script>
