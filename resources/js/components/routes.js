import VueRouter from 'vue-router';
let routes = [

	//Component Routes for Dashboard
	{
		path: '/',
		component: require('./dashboard/index').default,
	},

	// Component Routes for Users
	{
		path: '/users',
		component: require('./users/index').default,
	},
	{
		path: '/users/:id',
      component: require('./users/show').default,
	},

	// Component Routes for Roles
	{
		path: '/roles',
		component: require('./roles/index').default,
	},
	{
		path: '/roles/:id',
      component: require('./roles/show').default,
	},

	// Component Routes for Modules
	{
		path: '/modules',
		component: require('./modules/index').default,
	},
	{
		path: '/modules/:id',
		component: require('./modules/show').default,
	},

	// Component Routes for key visual
    {
		path: '/marketing/key-visual',
		component: require('./marketings/key-visual/index').default
	},

	// Component Routes for quicklinks
    {
      path : '/marketing/quicklinks',
      component: require('./marketings/quicklinks/index').default
    },

	 // Component Routes for ads
	{
		path : '/marketing/ads',
		component: require('./marketings/ads/index').default
	},


	//Component Routes for Stores
	{
		path: '/stores',
		component: require('./stores/index').default,
	},
	{
		path: '/stores/:id',
      	component: require('./stores/show').default,
	},

	//Component Routes for Posting
	{
		path: '/postings/:id',
		component: require('./postings/show').default,
	},
	{
		path: '/postings',
		component: require('./postings/index').default,
	},


	//Component Routes for Tags
	{
		path: '/tags/:id',
		component: require('./tags/show').default,
	},
	{
		path: '/tags',
		component: require('./tags/index').default,
	},

	//Component Routes for Categories
	{
		path: '/categories',
		component: require('./cs/categories/index').default,
	},
	{
		path: '/categories/:id',
		component:  require('./cs/categories/show').default,
	},


	//Component Routes for Categories
	{
		path: '/sub-categories/:id',
		component:  require('./cs/sub-categories/show').default,
	},

	//Component Routes for Sections
	{
		path: '/sections',
		component:  require('./sections/index').default,
	},
	{
		path: '/sections/:id',
		component:  require('./sections/show').default,
	},

	//Component Routes for Brands
	{
		path: '/brands',
		component:  require('./brands/index').default,
	},

	{
		path: '/brands/:id',
		component:  require('./brands/show').default,
	},

	//Component Routes for Api
	{
		path: '/apis',
		component:  require('./apis/index').default,
	},

	//Component Routes for Customers
	{
		path: '/customers',
		component: require('./customers/index').default,
	},
	{
		path: '/customers/:id',
		component: require('./customers/show').default,
	},

	//Component Routes for Posting Inquiries
	{
		path: '/posting-inquiries',
		component: require('./posting-inquiries/index').default,
	},
    {
        path: '/posting-inquiries/:id',
        component: require('./posting-inquiries/show').default,
    },

	//Component Routes for Inquiries
	{
		path: '/inquiries',
		component: require('./inquiries/index').default,
	},
	{
		path: '/inquiries/:id',
		component: require('./inquiries/show').default,
	},

	//Component ROutes for Store Inquiries
	{
		path: '/store-inquiries',
		component: require('./store-inquiries/index').default,
	},

	{
		path: '/store-inquiries/:id',
		component: require('./store-inquiries/show').default,
	},

	//Component Routes for Orders
	{
		path: '/orders',
		component: require('./orders/index').default,
	},
    {
        path: '/orders/:id',
        component: require('./orders/show').default,
    },

	//Component Routes for Auction
	{
		path: '/auctions',
		component: require('./auctions/index').default,
	},
	{
		path: '/auctions/:id',
		component: require('./auctions/show').default,
	},


    //Component Routes for Profile
    {
        path: '/user/profile',
        component: require('./user-profile/index').default,
    },

    //Component Routes for User Logs
    {
        path: '/user-logs',
        component: require('./user-logs/index').default,
    },
    {
        path: '/user-logs/:id',
        component: require('./user-logs/show').default,
    },

    //Component Routes for Payment Types
    {
    	path: '/payment-types',
    	component: require('./payment-types/index').default,
    },
    {
    	path: '/payment-types/:id',
    	component: require('./payment-types/show').default,
    },

    //Component Routes for Terms and Conditions
    {
    	path: '/terms',
    	component: require('./terms/index').default,
    },
    {
    	path: '/terms/:id',
    	component: require('./terms/show').default,
    },

    //Component Routes for Bid Deposit
    {
    	path: '/bidder-deposits',
    	component: require('./bidder-deposits/index').default,
	},

	//Component Routes for Access Request Email Templates
	{
    	path: '/request-email-templates',
    	component: require('./request-email-templates/index').default,
    },
    {
    	path: '/request-email-templates/:id',
    	component: require('./request-email-templates/show').default,
    },

    //Component Routes for Auction Access Requests
    {
    	path: '/auction-access-requests',
    	component: require('./auction-access-requests/index').default,
    },

    //Component Routes for Reports
    {
      path: '/reports/bid-histories',
      component: require('./reports/bid-histories/index').default,
    },
    {
      path: '/reports/bidder-per-auction',
      component: require('./reports/bidder-per-auction/index').default,
    },
    {
      path: '/reports/retail-event-reports',
      component: require('./reports/retail-event-reports/index').default,
    },
	{
        path: '/reports/delivery-reports',
        component: require('./reports/delivery-reports/index').default,
    },
    {
        path: '/reports/posting-reports',
		component: require('./reports/posting-reports/index').default,
    },

	//Component Routes for Importer
    {
    	path: '/importer',
    	component: require('./importer/index').default,
	},

	// Component Routes for Affiliate Marketing
	{
		path: '/affiliate-marketing',
		component: require('./affiliate-marketing/dashboard/index').default,
	},

	{
		path: '/affiliate-marketing/marketers',
      component: require('./affiliate-marketing/index').default,
	},

	{
		path: '/affiliate-marketing/:id',
      component: require('./affiliate-marketing/show').default,
	},

	// Component Routes for Customer Registrations
	{
		path: '/customer-registrations',
		component: require('./customer-registrations/index').default,
	},

	//Component Routes for Event Access Request Email Templates
	{
    	path: '/event-access-request-template',
    	component: require('./event-access-request-template/index').default,
    },
    {
    	path: '/event-access-request-template/:id',
    	component: require('./event-access-request-template/show').default,
    },

    //Component Routes for Event Access Requests
    {
    	path: '/event-access-requests',
    	component: require('./event-access-requests/index').default,
    },

	//Component Routes for Event
	{
		path: '/events',
		component: require('./events/index').default,
	},
	{
		path: '/events/:id',
		component: require('./events/show').default
	},
    {
		path: '/events/:id/live-selling',
		component: require('./live-selling-controller/index').default,
	},

    {
        path: '/product-catalogs/:id',
        component: require('./product-catalogs/index').default,
    },

    // Component Route for Event Activity Logs
    {
        path: '/event-activity-logs/:id',
        component: require('./event-activity-log/show').default
    },

    //Contact Us Email Settings
    {
    	path: '/inquire-emails',
		component: require('./inquire-emails/index').default,
    },

	//Component Routes for Failed Jobs
	{
		path: '/failed-jobs',
		component: require('./failed-jobs/index').default,
	},
	{
		path: '/failed-jobs/:id',
		component: require('./failed-jobs/show').default
	},

	//Component Routes for Retail Inventoru Report
	{
		path: '/reports/retail-inventory-report',
		component: require('./reports/retail-inventory-report/index').default,
	},

	//Component Routes for Retail Onhold and Cancelled Orders Report
	{
		path: '/reports/retail-order-report',
		component: require('./reports/retail-onhold-orders-report/index').default,
	},

	//Component Routes for Retail Onhold and Cancelled Orders Report
	{
		path: '/reports/retail-voucher-report',
		component: require('./reports/retail-voucher-report/index').default,
	},

	//Component Routes for Vouchers
	{
		path: '/vouchers',
		component: require('./vouchers/index').default,
	},

	  //Component Routes for Cost Types
	{
		path: '/cost-types',
		component: require('./cost-types/index').default,
	},

	  //Component Routes for Cost
	{
		path: '/costs',
		component: require('./costs/index').default,
	},

	 //Component Routes for Companies
	{
		path: '/companies',
		component: require('./companies/index').default,
	},

	 //Component Routes for Payables
	{
		path: '/payables',
		component: require('./payables/index').default,
	},
	{
		path: '/payables/:id',
		component: require('./payables/show').default,
	},
	{
		path: '/for-payables/:id',
		component: require('./payables/payable-submission').default
	},
	{
		path: '/store-payables/:id',
		component: require('./store-payables/show').default
	},

	  //Component Routes for Banks
    {
    	path: '/banks',
    	component: require('./banks/index').default,
    },
    {
    	path: '/banks/:id',
    	component: require('./banks/show').default,
    },

    //Component Routes for Auction Section
    {
    	path: '/auction/section',
    	component: require('./auction-section/index').default,
    },

	//Component Routes for Retail Section
    {
    	path: '/retail/section',
    	component: require('./retail-section/index').default,
    },


	  // Component Routes for Account Executives
	{
		path: '/account-executives',
		component: require('./account-executives/index').default,
	},

	  // Component Routes for Account Executives Dashboard
	{
		path: '/account-executive/dashboard',
		component: require('./posting-inquiries/account-executive-dashboard').default,
	},
    //Component Routes for Navigation
    {
    	path: '/navigations',
    	component: require('./navigations/index').default,
    },
    {
    	path: '/navigations/header',
    	component: require('./navigations/header/index').default,
    },
    {
    	path: '/navigations/sidebar',
    	component: require('./navigations/sidebar/index').default,
    },
    {
    	path: '/navigations/footer',
    	component: require('./navigations/footer/index').default,
    },
    //Component Routes for Admin Tools
    {
        path: '/admin-tools/cart-extension',
        component: require('./admin-tools/cart-extension/index').default,
	},
	{
        path: '/admin-tools/order-uncancellation',
        component: require('./admin-tools/order-uncancellation/index').default,
    },
	{
        path: '/admin-tools/bidder-auctions',
        component: require('./admin-tools/bidder-auctions/index').default,
    },

    //Component Routes for Career Pages
    {
        path: '/career-pages',
        component: require('./career-page/index').default,
    },
    {
        path: '/career-pages/:id',
        component: require('./career-page/show').default,
	},

	//Component Routes for Delivery Status
    {
    	path: '/delivery-statuses',
    	component: require('./delivery-statuses/index').default,
    },
    {
    	path: '/delivery-statuses/:id',
    	component: require('./delivery-statuses/show').default,
    },

    //Component Routes for Courier Status
    {
    	path: '/courier-statuses',
    	component: require('./courier-statuses/index').default,
    },
	  //Component Routes for Abandoned Carts
    {
        path: '/abandoned-cart',
        component: require('./abandoned-cart/index').default,
	},
	//Component Routes for Abandoned Carts
    {
        path: '/order-waybills',
        component: require('./order-waybills/index').default,
	},


	  //Component Routes for Abandoned Cart Historiers
    {
        path: '/abandoned-cart-history',
        component: require('./abandoned-cart-history/index').default,
    },
	//Component Routes for Total inventory report
	{
		path: '/reports/total-inventory-report',
		component: require('./reports/total-inventory-report/index').default,
	},

	//Component Routes for Couriers
	{
		path: '/couriers/:id/classification-rates',
		component: require('./courier-classification-rates/index').default,
	},
	{
		path: '/couriers',
		component: require('./couriers/index').default,
	},

	//Component Routes for Live Auction
	{
        path: '/live-auction/:id',
        component: require('./live-auction/index').default,
    },
	{
        path: '/live-auction/:id/stream-settings',
        component: require('./live-auction/index').default,
    },

	{
        path: '/live-auction/:id/view',
        component: require('./live-auction/display-page').default,
    },


	//Component Routes for Live Selling
	{
		path: '/events/29/live-selling',
		component: require('./live-selling-controller/index').default,
	},
    {
		path: '/live-selling/:id/view',
		component: require('./live-selling-controller/display-page').default,
	},
];

export default new VueRouter({
    routes,
});
