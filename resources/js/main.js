require('./bootstrap');

import Login from './layouts/login.vue';
import Register from './layouts/register.vue';
import ForgotPassword from './layouts/forgot-password.vue';
import ResetPassword from './layouts/reset-password.vue';

new Vue({
    el: '#app',
    components : { Login, Register, ForgotPassword, ResetPassword },
});
