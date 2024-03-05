/*
|--------------------------------------------------------------------------
| Server side Pagination
| Not so good. but it works.  ^_^
|--------------------------------------------------------------------------
|
*/

class Table {
    
    /**
     * Create a new Table instance.
     * @url. Url that will by axios.
     * @instance_parameters. Additional parameters from instance 
     */
    constructor(url, instance_params, page = 1) {

        // url of axios
        this.url = url;

        // Pagination parameters
        this.search = '';
        this.search_field = '';
        this.order_by = '';
        this.order_action = '';
        this.page_per_row = 10;
        this.page = page;

        // Additional Parameters from instance
        this.instance_params = instance_params;

        // Fetching data from server side
        this.get();

        // will hold the reponse of axios
        this.data = {};

        // Choices for row per pages variable
        this.row_per_pages = [10, 20, 40, 80, 150, 500];

        // status of loader in table
        // set false as default
        this.loader_status = true;
    }

    /**
     * This will return the query parameters that will send to the server
     */
    parameters() {

        let pagination_parameters = {
            page: this.page,
            search: this.search,
            search_field: this.search_field,
            order_action: this.order_action,
            order_by: this.order_by,
            row_per_page: this.row_per_page,
            action: 'pagination'
        };

        // If where clause is not empty
        // we will merge the pagination parameters and additional parameters from instance
        if(! _.isEmpty(this.instance_params)) {
            return Object.assign(this.instance_params.params, pagination_parameters);
        }
           
        // Otherwise we will return pagination parameters only
        return  pagination_parameters;
    }

    /**
     * Fetch data from server side
     */
    get(page = null) {

        // Set data is to empty object
        this.data = [];

        // set loader status is true
        this.loader_status = true;

        // Set the page
        this.page = page || this.page;

        axios.get(this.url, { params: this.parameters() })
        .then(response => {
            this.data = response.data;
                        // set loader status to false if data is fetch
            this.loader_status = false;
        })
        .catch(error => {
            console.log(error.response.data);
            this.loader_status = false;
        });
    }

    /**
     * Set search parameter
     * Set search_field parameter (Optional)
     * Then fetch data from server side 
    */
    setSearch(search, search_field = null) {
        this.search = search;
        this.search_field = search_field;

        this.get(1);
    }

    /**
     * Set order by and order action parameter
     * Then fetch data from server side 
     */
    sort(order_by) {
        this.order_by = order_by;
        
        if(this.order_by == order_by) {
            this.order_action = (this.order_action == 'DESC') ? 'ASC' : 'DESC';
        }else {
            this.order_action = 'DESC';
        }

        this.get();
    }

    /**
     * will determine if the field from instance is the order_by field
    */
    addClass(field) {
        return (this.order_by == field) ? true : false; 
    }

    /**
     * Set row per page parameters
     * Then fetch data from server side 
     */
    rowPerPage(value) {
        this.row_per_page = value;

        this.get();
    }

    /**
     * Reset all parameters
     * Then fetch data from server side 
    */
    clearFilter() {
        this.data = '';
        this.search = '';
        this.order_by = '';
        this.order = false;
        this.row_per_page = 10;
        this.page = 1;

        this.get();
    }

    isLoading() {
        return this.loader_status;
    }

    isEmpty() {
        return this.data.data && this.data.data.length <= 0
    }
}

export default Table;
