import Errors from './Errors';

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data, should_reset=true) {
        this.should_reset = should_reset;
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();

        this.errorMessages = {
            500 : 'Something went wrong!',
            404 : 'Request Page Not Found!',
            419 : 'Session expired! Please refresh the page.',
            403 : 'This action is unauthorized.',
        }
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

       for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }


    formData() {
        let data = new FormData();

        for (let property in this.originalData) {
            data.append(property,this[property]);
        }

        return data;
    }


    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            if(Array.isArray(this[field])){
                this[field] = [];
            }else if(Number.isInteger(this[field])){
                this[field] = 0;
            }else{
                this[field] = '';
            }
        }

        this.errors.clear();
    }


    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url, config = {}) {
        return this.submit('post', url, config);
    }

    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }


    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }


    setFormData(data) {
        let formData = new FormData();
        for (let field in data) {
            formData.append(field, data[field]);
        }
        return formData;
    }
    
    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */


    submit(requestType, url, config = {}) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data(), config)
                .then(response => {
                    this.onSuccess(response.data);

                    resolve(response.data);
                })
                    .catch(error => {
                    
                  // Displaying unauthorized messages 
                    if (error.response.status != 422) {

                        let message = this.errorMessages[error.response.status] ? this.errorMessages[error.response.status] : error.response.data.message;
                        
                           Snotify.async('Please wait', 'Processing...', () => new Promise((resolve, reject) => {
                                setTimeout(() => reject({
                                    title: error.response.status,
                                    body: message,
                                }), 2000);
                            }));
                            
                         setTimeout(() => window.location.reload() , 6000);
                        
                    }          
                    
                    this.onFail(error.response.data);

                    reject(error.response.data);
                });
        });
    }

    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {
        if(this.should_reset) this.reset();
        // this.reset();
    }


    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors.errors);
    }
}

export default Form;