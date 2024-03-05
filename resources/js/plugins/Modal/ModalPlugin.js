import Modal from './Modal';

let Plugin = {
    install: function (Vue, options = {}) {
        Vue.component('modal', Modal);

        Plugin.events = new Vue();


        Vue.prototype.$modal = {
            show(params = {}) {
                Plugin.events.$emit('show', params);
            },

            hide(name) {
                Plugin.events.$emit('hide', false);
            },

            dialog(message, params) {

                
                if (typeof message === 'string') {
                    params.message = message;
                } else {
                    params = message;
                }

                return new Promise((resolve, reject) => {
                    this.show(Object.assign({ type: 'dialog' }, params));
                    Plugin.events.$on(
                        'clicked', confirmed => confirmed ? resolve() : reject()
                    );
                });

            },

            success(message, params) {

                if (typeof message === 'string') {
                    params.message = message;
                } else {
                    params = message;
                }

                this.show(Object.assign({ 
                    type: 'success',
                    cancelButton: false, 
                    confirmButton: false,
                    timer: 3000 
                }, params));

                if(!params.cancelButton && !params.confirmButton || params.timer){
                    setTimeout(function() {
                        Plugin.events.$emit('hide', false);
                    }, params.timer ? params.timer : 3000);                      
                }

         
            },

            error(message, params) {
            
                if (typeof message === 'string') {
                    params.message = message;
                } else {
                    params = message;
                }

                this.show(Object.assign({ 
                    type: 'error',
                    cancelButton: false, 
                    confirmButton: false,
                    timer: 3000 
                }, params));

                if(!params.cancelButton && !params.confirmButton || params.timer){
                    setTimeout(function() {
                        Plugin.events.$emit('hide', false);
                    }, params.timer ? params.timer : 3000);                      
                }
            },



          

        }
    }
};

export default Plugin;