window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

 let token = document.head.querySelector('meta[name="csrf-token"]');

 if (token) {
     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
 } else {
     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
 }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });


/**
 * Vue
 * The Progressive JavaScript Framework
 */
import Vue from 'vue';
window.Vue = Vue;

/**
 * Vue Router
 * Use for building web single page application
 */
import VueRouter from 'vue-router'
Vue.use(VueRouter);

/**
 * Vue Form
 */
import Form from './Core/Form'
window.Form = Form;

/**
 * Vue Form for uploading image
 */
import FormUpload from './Core/FormUpload'
window.FormUpload = FormUpload;

window.app = new Vue();

/**
 * Vue Directives
 */
require('./Core/Directives');

/**
 * Accounting JS
 */
import accounting from 'accounting-js';
window.accounting = accounting;


/**
 * Moment JS
 */
import moment from 'moment';
window.moment = moment

/**
 * Vue Filters
 */
require('./Core/Filters');

/**
 * Vue SwAl
 */

import Swal from 'vue-swal'
Vue.use(Swal)

import Popup from './Core/Popup'
window.Popup = Popup;


