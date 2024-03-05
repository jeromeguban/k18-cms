<?php

use App\Http\Controllers\DarkModeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/', function () { return redirect('login'); } );
// Auth::routes();

Route::get('/dashboard', 'DashboardController@index');
Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');
Route::get('/total-orders-postings', 'OrderTotalController@index');

/*
|--------------------------------------------------------------------------
| User FProfile Routes
|--------------------------------------------------------------------------
 */
Route::resource('profiles', 'ProfileController');
Route::post('profile/{user}/edits', 'ProfileController@update');
Route::post('profiles/{user}/change-password', 'ProfilePasswordController@update');
Route::get('user/{user}/activity-logs', 'ProfileActivityLogController@index');
Route::get('user/activity-logs/{activity_log}', 'ProfileActivityLogController@show');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Users & ACL
|--------------------------------------------------------------------------
 */
Route::patch('users/{user}/approval', 'UserForApprovalController@update');
Route::patch('users/{user}/pending', 'UserForPendingController@update');
Route::resource('users', 'UserController');
Route::resource('users/{user}/roles', 'UserRoleController');
Route::resource('modules', 'ModuleController');
Route::resource('modules/{module}/permissions', 'ModulePermissionController');
Route::resource('roles', 'RoleController');
Route::resource('roles/{role}/permissions', 'RolePermissionController');
Route::patch('users/{user}/change-password', 'UserChangePasswordController@update');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Key Visual
|--------------------------------------------------------------------------
 */
Route::resource('key-visuals', 'KeyVisualController');
Route::post('key-visuals/sequence', 'KeyVisualSequenceController@store');
Route::patch('key-visuals/{key_visual}/status', 'KeyVisualStatusController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Quick Links
|--------------------------------------------------------------------------
 */
Route::resource('quicklinks', 'QuickLinkController');
Route::post('quicklinks/sequence', 'QuickLinkSequenceController@store');
Route::patch('quicklinks/{quicklink}/status', 'QuickLinkStatusController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Ads
|--------------------------------------------------------------------------
 */
Route::resource('ads', 'AdsController');
Route::post('ads/sequence', 'AdsSequenceController@store');
Route::patch('ads/{ad}/status', 'AdsStatusController@update');
Route::patch('ads/{ad}/featured', 'AdsStatusController@updateFeatured');
/*
|
 */

/*
| Routes for Payables
|--------------------------------------------------------------------------
 */
Route::get('/stores/for-payables', 'StoreForPayableController@index');
Route::get('/stores/{store}/for-payables-items', 'StoreForPayableItemController@index');
Route::get('/stores/{store}/payable/{payable}/items', 'StorePayableItemController@index');
Route::get('/stores/{store}/for-payables', 'StoreForPayableController@show');
Route::get('/for-payables', 'ForPayableController@index');
Route::resource('/payables', 'PayableController');
Route::get('/payables/{payable}/items', 'PayableItemController@index');
Route::get('/export-payable', 'ExportPayableController@export');
Route::post('store/{store}/logo', 'StoreController@setLogo');
Route::post('store/{store}/profile', 'StoreController@setProfile');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Stores
|--------------------------------------------------------------------------
 */
Route::resource('stores', 'StoreController');
Route::resource('store/{store}/banners', 'StoreBannerController');
Route::patch('stores/{store}/active', 'StoreController@setActive');
// Route::post('store/{store}/banners', 'StoreBannerController@store');

/*
|--------------------------------------------------------------------------
| Routes for Postings
|--------------------------------------------------------------------------
 */
Route::resource('postings', 'PostingController');
Route::resource('postings/{posting}/images', 'PostingBannerController');
Route::delete('postings/{posting}/images', 'PostingBannerController@destroy');
Route::patch('postings/{posting}/images', 'PostingBannerController@update');
Route::patch('postings/{posting}/publish', 'PostingPublishController@index');
Route::patch('postings/{posting}/unpublish', 'PostingUnpublishController@index');
Route::patch('posting-images/{posting}/sequence', 'PostingImageSequencingController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Posting Items
|--------------------------------------------------------------------------
 */
Route::patch('posting/{posting_item}/items/publish', 'PostingItemPublishController@index');
Route::patch('posting/{posting_item}/items/unpublish', 'PostingItemUnpublishController@index');
Route::resource('posting/{posting}/items', 'PostingItemController');
Route::get('posting-item/{posting_item}', 'PostingItemController@show');
Route::get('posting-items', 'PostingItemController@filterItem');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Google Merchant
|--------------------------------------------------------------------------
 */
Route::get('google/product-categories', 'GoogleProductCategoryController@index');
Route::post('products/{posting_item}/google-merchant/publish', 'ProductGoogleMerchantPublishController@store');
Route::get('/google-merchant/{google_merchant}/products', 'GoogleMerchantProductController@show');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Tags
|--------------------------------------------------------------------------
 */
Route::resource('tags', 'TagController');
Route::patch('tags/{tag}/active', 'TagController@setActive');
Route::patch('tags/{tag}/featured', 'TagController@setFeatured');
Route::resource('tags/{tag}/banners', 'TagBannerController');
Route::post('tags/{tag}/logo', 'TagController@setLogo');
Route::post('tags/{tag}/banner', 'TagController@setBanner');
Route::post('tags/{tag}/mobile-banner', 'TagController@setMobileBanner');
/*
|
 */

/*
|--------------------------------------------------------------------------
| CS Routes
|--------------------------------------------------------------------------
 */
Route::resource('categories/{category}/sub-categories', 'CategorySubCategoriesController');
Route::post('categories/sequence', 'CategorySequenceController@store');
Route::patch('categories/{category}/status', 'CategoryStatusController@updateStatus');
Route::patch('categories/{category}/featured', 'CategoryStatusController@updateFeatured');
Route::resource('sub-categories', 'SubCategoryController');
Route::resource('categories', 'CategoryController');
Route::patch('categories/{category}/active', 'CategoryController@setActive');
Route::post('category/{category}/image', 'CategoryController@setImage');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Sections
|--------------------------------------------------------------------------
 */
// Route::get('sections/highlight', 'SectionHighlightController@index');
Route::resource('sections', 'SectionController');
Route::patch('sections/{section}/active', 'SectionController@changeStatus');
Route::post('sections/sequence', 'SectionController@updateSequence');
Route::resource('section/{section}/banners', 'SectionBannerController');
// Route::post('sections/sequence', 'SectionSequenceController@store');
// Route::patch('sections/{section}/status', 'SectionStatusController@update');
// Route::patch('sections/{section}/highlight', 'SectionHighlightController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Brands
|--------------------------------------------------------------------------
 */
Route::resource('brands', 'BrandController');
Route::post('brand/{brand}/logo', 'BrandController@setLogo');
Route::post('brand/{brand}/banner', 'BrandController@setBanner');
Route::patch('brand/{brand}/active', 'BrandController@setActive');
Route::patch('brand/{brand}/featured', 'BrandController@setFeatured');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Apis
|--------------------------------------------------------------------------
 */
Route::resource('apis', 'ApiController');
Route::patch('apis/{api}/active', 'ApiController@setActive');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Customers
|--------------------------------------------------------------------------
 */

Route::resource('customers', 'CustomerController');
Route::resource('password-reset', 'CustomerResetPasswordController');
Route::post('customer/{customer}/credentials', 'CustomerCredentialController@update');
Route::patch('customers/{customer}/block', 'CustomerBlockController@update');
Route::patch('customers/{customer}/unblock', 'CustomerUnblockController@update');
Route::patch('customers/{customer}/validate', 'CustomerValidateController@update');
Route::get('customer-export', 'CustomerExportController@export');
Route::get('customers/{customer}/activity-logs', 'CustomerActivityLogController@index');
Route::get('customers-total', 'TotalCustomerController@totalCustomers');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Posting Inquiry
|--------------------------------------------------------------------------
 */
Route::get('inquiry/{posting_inquiry}/activity-logs', 'PostingInquiryActivityLogController@index');
Route::resource('posting-inquiries', 'PostingInquiryController');
Route::patch('posting-inquiries/{posting_inquiry}/priority', 'PostingInquiryController@setPriorityStatus');
Route::patch('posting-inquiries/{posting_inquiry}/status', 'PostingInquiryController@setStatus');
Route::patch('posting-inquiries/{posting_inquiry}/account-executive', 'PostingInquiryController@assignAccountExecutive');
Route::patch('posting-inquiries/{posting_inquiry}/checklist', 'PostingInquiryController@updateChecklist');
Route::resource('account-executive/inquiries', 'AccountExecutiveInquiryController');
Route::resource('files', 'FileController');
Route::resource('file/{id}/upload', 'FileController');
Route::get('download/{filename}', 'DownloadFileController@index');
Route::post('send-email', 'SendEmailController@index');
/*
|--------------------------------------------------------------------------
| Routes for Posting Inquiry tasks
|--------------------------------------------------------------------------
 */
Route::get('posting-inquiries/{posting_inquiry}/tasks', 'PostingInquiryTaskController@index');
Route::patch('posting-inquiries/{posting_inquiry_task}/task', 'PostingInquiryTaskController@update');
/*
|--------------------------------------------------------------------------
| Routes for Store Inquiries
|--------------------------------------------------------------------------
 */

Route::resource('inquiries', 'InquiryController');
Route::delete('inquiries-delete/{inquiries}', 'InquiryController@destroy');

Route::resource('/store-inquiries', 'StoreInquiriesController');
Route::patch('/store-inquiries/{posting_inquiry}/status', 'StoreInquiriesController@updateStatus');
Route::get('store-inquiries-export', 'StoreInquiriesController@export');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Orders
|--------------------------------------------------------------------------
 */
Route::resource('orders', 'OrderController');
Route::get('orders-export', 'OrderController@exportOrder');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Order Posting
|--------------------------------------------------------------------------
 */
Route::resource('order/{order}/postings', 'OrderPostingController');
Route::get('order/{order}/posting-details', 'OrderPostingController@getOrderPostingDetails');

/*
|--------------------------------------------------------------------------
| Routes for Auctions
|--------------------------------------------------------------------------
 */
Route::post('auction/{auction}/catalog', 'CatalogFileUploadController@store');
Route::resource('auction-sections', 'AuctionSectionController');
Route::resource('section-images', 'AuctionSectionImageController');
Route::resource('auctions', 'AuctionController');
Route::get('/streams/{stream}/count', 'StreamCountController@index');
Route::get('/ice-servers', 'IceServerController@index');
Route::post('simulcast-auctions/postings/{posting}/for-approval', 'SimulcastAuctionPostingForApprovalController@store');
Route::post('simulcast-auctions/postings/{posting}/finalized', 'SimulcastAuctionPostingFinalizedController@store');
Route::post('simulcast-auctions/postings/{posting}/mark-as-sold', 'SimulcastAuctionPostingMarkAsSoldController@store');
Route::get('simulcast-auctions/{auction}/postings', 'SimulcastAuctionPostingController@index');
Route::get('auctions/{auction}/postings', 'AuctionPostingController@index');
Route::post('auctions/{auction}/for-approval', 'AuctionPostingController@store');
Route::patch('auctions/{auction}/stream-type', 'AuctionStreamTypeController@update');
Route::get('simulcast-auction/{posting}/history', 'SimulcastAuctionPostingController@history');
Route::get('simulcast-auction/{posting}/next-lot', 'SimulcastAuctionPostingController@nextLot');
Route::get('auctions/{auction}/bidder-number', 'AuctionBidderNumberController@index');
Route::get('auction-catalog/excel', 'Reports\AuctionCatalogController@export');
Route::get('auction-catalog/pdf', 'Reports\AuctionCatalogPdfController@export');

Route::patch('/auctions/{auction}/publish', 'SimulcastAuctionPublishController@index');
Route::patch('/auctions/{auction}/unpublish', 'SimulcastAuctionUnpublishController@index');
Route::patch('/auctions/{auction}/refresh', 'SimulcastRefreshAuctionCacheController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Payment Types
|--------------------------------------------------------------------------
 */
Route::resource('payment-types', 'PaymentTypeController');
Route::patch('payment-types/{payment_type}/active', 'PaymentTypeController@setActive');
Route::patch('payment-types/{payment_type}/status', 'PaymentTypeController@setStatus');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Terms and Conditions
|--------------------------------------------------------------------------
 */
Route::resource('terms', 'TermController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Bidder Deposit
|--------------------------------------------------------------------------
 */
Route::resource('bidder-deposits', 'BidderDepositController');
Route::patch('bidder-deposits/{bidder_deposit}/withdraw', 'BidderDepositWithdrawController@update');
Route::patch('bidder-deposits/{bidder_deposit}/paid', 'BidderDepositMarkAsPaidController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Excel Importer
|--------------------------------------------------------------------------
 */
Route::post('customer/upload', 'ExcelImporterController@customer');
Route::post('customer-login-credential/upload', 'ExcelImporterController@customerCredential');
Route::post('customer-addresses/upload', 'ExcelImporterController@customerAddresses');
Route::post('product-upload', 'ExcelImporterController@productImport');
Route::post('image-upload', 'ExcelImporterController@uploadImages');
Route::post('user-upload', 'ExcelImporterController@uploadUsers');
Route::post('viewing-details', 'ExcelImporterController@updateViewingDetails');
Route::post('product-quantities', 'ExcelImporterController@updateProductQuantities');
Route::post('quantity-upload', 'ExcelImporterController@quantityUpload');
Route::post('starting-amount', 'ExcelImporterController@startingAmount');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Access Request Email Template
|--------------------------------------------------------------------------
 */
Route::resource('/access-request-email-templates', 'AccessRequestEmailTemplateController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Auction Access Request
|--------------------------------------------------------------------------
 */
Route::resource('auction-access-requests', 'AuctionAccessRequestController');
Route::get('export-auction-access-requests', 'AuctionAccessRequestController@export');
Route::post('auctions/access-requests/{access_request}/approved', 'AuctionAccessRequestApproveController@store');
Route::post('auctions/access-requests/{access_request}/declined', 'AuctionAccessRequestDeclineController@store');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for  Reports
|--------------------------------------------------------------------------
 */
Route::get('bid-histories/generate', 'BidHistoryController@generate');
Route::get('bid-histories/export', 'BidHistoryController@export');
Route::get('bidder-per-auctions/generate', 'BidderPerAuctionController@generate');
Route::get('bidder-per-auctions/export', 'BidderPerAuctionController@export');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Affiliate Marketing
|--------------------------------------------------------------------------
 */
Route::get('affiliate-marketing/order-postings', 'AffiliateMarketingOrderPostingController@index');
Route::get('affiliate-marketing/export', 'AffiliateMarketingExportController@export');
Route::get('affiliate-marketing/global-export', 'AffiliateMarketingExportController@export');
Route::resource('affiliate-marketing', 'AffiliateMarketingController');
Route::resource('affiliate-marketing/{id}/record', 'AffiliateMarketingRecordController');
Route::patch('affiliate-marketing/{marketer}/active', 'AffiliateMarketingController@setActive');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Affiliate Marketing
|--------------------------------------------------------------------------
 */
Route::resource('customer-registrations', 'CustomerRegistrationController');
Route::patch('customer-registration/{id}/validate', 'CustomerValidationController@update');
Route::get('customers-registeration-total', 'TotalCustomerController@totalCustomerRegistrations');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Sales Order
|--------------------------------------------------------------------------
 */
Route::patch('orders/{order}/update-sales-order', 'UpdateSalesOrderController@index');
Route::patch('orders/{order}/import-sales-order', 'ImportSalesOrderController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Access Request Email Template
|--------------------------------------------------------------------------
 */
Route::resource('/event-access-request-templates', 'EventAccessRequestTemplateController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Access Request
|--------------------------------------------------------------------------
 */
Route::resource('event-access-requests', 'EventAccessRequestController');
Route::get('export-event-access-requests', 'EventAccessRequestController@export');
Route::post('events/access-requests/{access_request}/approved', 'EventAccessRequestApproveController@store');
Route::post('events/access-requests/{access_request}/declined', 'EventAccessRequestDeclineController@store');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Events
|--------------------------------------------------------------------------
 */
Route::post('/events/{event}/hold-postings', 'EventHoldPostingController@store');
Route::delete('/events/{event}/hold-postings', 'EventHoldPostingController@destroy');
Route::patch('/events/{event}/publish', 'EventPublishController@index');
Route::patch('/events/{event}/unpublish', 'EventUnpublishController@index');
Route::post('/events/{event}/registration-banners', 'EventRegistrationBannerController@store');
Route::post('/events/{event}/banners', 'EventBannerController@store');
Route::get('/event-reports/generate', 'RetailEventSummaryResultController@index');
Route::get('/event-reports/export', 'RetailEventSummaryResultController@export');
Route::resource('events', 'EventController');
Route::patch('/refresh/{event}/cache', 'RefreshEventCacheController@refresh');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Event Postings
|--------------------------------------------------------------------------
 */

Route::get('events/{event}/postings', 'EventPostingController@index');
Route::get('posting-items/{barcode}/sku', 'EventPostingItemSearchController@index');
Route::get('posting/{posting_item}/items/{barcode}/sku', 'EventPostingItemSkuController@index');
Route::post('tag-posting', 'EventPostingController@store');
Route::post('event-postings/sequence', 'EventPostingSequenceController@store');
Route::patch('event-postings/{posting}/remove', 'EventPostingController@remove');
Route::get('events/{event}/participants', 'ParticipantController@index');
Route::patch('participants-message/{participant}/block', 'MessageEventRestrictionController@block');
Route::patch('participants-message/{participant}/unblock', 'MessageEventRestrictionController@unblock');
Route::patch('participants-cart/{participant}/block', 'CartEventRestrictionController@block');
Route::patch('participants-cart/{participant}/unblock', 'CartEventRestrictionController@unblock');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Event Activity Logs
|--------------------------------------------------------------------------
 */
Route::get('events/{event}/activity-logs', 'EventActivityLogController@index');
Route::get('event-activity-logs/{activity_log}', 'EventActivityLogController@show');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Failed Jobs
|--------------------------------------------------------------------------
 */
Route::resource('failed-jobs', 'FailedJobController');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Detailed Report
|--------------------------------------------------------------------------
 */
Route::get('retail/inventory-report', 'RetailInventoryReportController@export');
Route::get('retail/generate-inventory-report', 'RetailInventoryReportController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Detailed Report
|--------------------------------------------------------------------------
 */
Route::get('retail/order-report', 'RetailOrderReportController@export');
Route::get('retail/generate-order-report', 'RetailOrderReportController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Retail Total Inventory Report
|--------------------------------------------------------------------------
 */
Route::get('retail/generate-total-inventory-report', 'TotalInventoryController@index');
Route::get('retail/total-inventory-report', 'TotalInventoryController@export');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Delivery Report
|--------------------------------------------------------------------------
 */
Route::get('delivery-reports-generate', 'DeliveryReportController@index');
Route::get('delivery-reports-export', 'DeliveryReportController@export');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for validate Payment (Paid or Not)
|--------------------------------------------------------------------------
 */
Route::get('payment/validate', 'PaymentGatewayValidatorController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Vouchers
|--------------------------------------------------------------------------
 */
Route::resource('vouchers', 'VoucherController');
Route::patch('vouchers/{voucher}/active', 'VoucherController@setActive');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Affiliate Marketing Dashboard
|--------------------------------------------------------------------------
 */
Route::resource('dashboard/affiliate-marketing', 'AffiliateMarketingDashboardController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Voucher Settings
|--------------------------------------------------------------------------
 */
Route::get('voucher/{voucher}/settings', 'VoucherSettingController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Customer Voucher
|--------------------------------------------------------------------------
 */
Route::get('customer/{voucher}/voucher', 'CustomerVoucherController@index');
/*
|
 */
/*
| Routes for Contact Us Email Settings
|--------------------------------------------------------------------------
 */
Route::resource('inquire-emails', 'InquireEmailController');
Route::patch('inquire-emails/{inquire_email}/active', 'InquireEmailController@setActive');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Costs
|--------------------------------------------------------------------------
 */
Route::resource('costs', 'CostController');
Route::get('companies/{company}/costs', 'CompanyCostController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Cost Type
|--------------------------------------------------------------------------
 */
Route::resource('cost-types', 'CostTypeController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Cost Type
|--------------------------------------------------------------------------
 */
Route::resource('companies', 'CompanyController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Banks And Account Numbers
|--------------------------------------------------------------------------
 */
Route::resource('banks', 'BankController');
Route::resource('banks/{bank}/account-numbers', 'BankAccountNumberController');
Route::resource('account-numbers', 'AccountNumberController');
Route::get('banks/{bank}/account-numbers', 'BankAccountNumberController@index');
Route::post('banks/{bank}/account-numbers', 'BankAccountNumberController@store');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Account Executives
|--------------------------------------------------------------------------
 */
Route::resource('account-executives', 'AccountExecutiveController');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Navigation
|--------------------------------------------------------------------------
 */
Route::resource('navigations', 'NavigationController');
Route::post('navigations/sequence', 'NavigationController@updateSequence');
Route::post('navigations/properties-sequence', 'NavigationController@updatePropertySequence');
/*
|
 */
/*
|--------------------------------------------------------------------------
| Routes for Customer Cart Items
|--------------------------------------------------------------------------
 */
Route::get('cart/{customer}/items', 'CustomerCartController@index');
Route::patch('cart/{cart}/item', 'CartController@update');
Route::resource('cart/items', 'CartController');
/*
|
 */
/*
|--------------------------------------------------------------------------
| Routes for Navigation
|--------------------------------------------------------------------------
 */
Route::resource('retail/cancelled-orders', 'RetailOrderUncancellationController');
Route::patch('order/{order}/uncancellation', 'RetailOrderUncancellationController@update');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Careers
|--------------------------------------------------------------------------
 */
Route::resource('careers', 'CareerController');
Route::post('careers/{career}/banner', 'CareerController@banner');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Careers Applicants
|--------------------------------------------------------------------------
 */
Route::resource('career/{career}/applicants', 'CareerApplicantController');
Route::get('career-applicant/{career_applicant}', 'CareerApplicantController@show');
Route::get('career-applicant/{career_applicant}/resume', 'CareerApplicantController@file');
Route::get('career-applicant/{career_applicant}/download', 'CareerApplicantController@download');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Customer Abandoned Cart
|--------------------------------------------------------------------------
 */
Route::resource('abandoned-carts', 'AbandonedCartController');
Route::patch('transfer/{cart}/abandoned-cart', 'AbandonedCartController@update');
Route::post('carts/{cart}/cancellation', 'AbandonedCartController@store');
Route::get('abandon-carts/export', 'AbandonedCartController@exportData');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Customer Abandoned Cart History
|--------------------------------------------------------------------------
 */
Route::resource('abandoned-cart-histories', 'AbandonedCartHistoryController');
Route::patch('undo/{abandoned_cart_history}/abandoned-cart-histories', 'AbandonedCartHistoryController@update');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Delivery Status
|--------------------------------------------------------------------------
 */
Route::resource('delivery-statuses', 'DeliveryStatusesController');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for  Courier Status
|--------------------------------------------------------------------------
 */
Route::resource('courier-statuses', 'CourierStatusesController');

/*
|
 */


/*
|--------------------------------------------------------------------------
| Routes for  Order Waybill
|--------------------------------------------------------------------------
 */
Route::resource('/order-waybills', 'OrderWayBillController');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for  Order Store Contacts
|--------------------------------------------------------------------------
 */
Route::get('/orders/{order}/store-contacts', 'OrderStoreContactController@index');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for  Shipping Rates
|--------------------------------------------------------------------------
 */
Route::post('/get-shipping-rates', 'ShipmateController@getShippingRates');
Route::post('/orders/shipmates-waybills', 'ShipmateController@store');
Route::patch('/orders/shipmates-waybills/{order_waybill}', 'ShipmateController@update');
Route::get('/track-shipment', 'ShipmateController@trackShipment');
Route::post('/cancel-shipment', 'ShipmateController@cancelShipment');
Route::get('/get-shipments', 'ShipmateController@getShipment');

/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Zerou Out Inventory of Posting
|--------------------------------------------------------------------------
 */
Route::patch('postings/{posting}/inventory-removal', 'PostingInventoryRemovalController@index');
/*
|
 */

 /*
|--------------------------------------------------------------------------
| Routes for Zerou Out Inventory of Posting
|--------------------------------------------------------------------------
 */
Route::get('posting-report/export', 'PostingReportController@index');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for System Notifications
|--------------------------------------------------------------------------
 */
Route::get('/system-notifications/unviewed/count', 'SystemNotificationUnviewedController@count');
Route::post('/system-notifications/viewed', 'SystemNotificationViewedController@store');
Route::get('/system-notifications/listing', 'SystemNotificationController@listing');
Route::resource('/system-notifications', 'SystemNotificationController');
/*
|
*/

 /*
|--------------------------------------------------------------------------
| Routes for Posting Bid History
|--------------------------------------------------------------------------
*/
Route::get('posting/{posting}/bid-histories', 'PostingBidHistoryController@index');

/*
|
*/

/*
|--------------------------------------------------------------------------
| Routes for System Notifications
|--------------------------------------------------------------------------
*/
 Route::resource('/bid-increments', 'BidIncrementController');

/*
|
*/
/*
|--------------------------------------------------------------------------
| Routes for Store Addresses
|--------------------------------------------------------------------------
 */
 Route::get('/store/{store}/addresses', 'StoreAddressesController@index');
 Route::post('/store-addresses', 'StoreAddressesController@store');
 Route::patch('/update/{store_address}/store-address', 'StoreAddressesController@update');
 Route::delete('store-addresses/{store_address}', 'StoreAddressesController@destroy');
 Route::get('/store-addresses', 'StoreAddressesController@list');
/*
|
*/
/*
|--------------------------------------------------------------------------
| Routes for Google Address
|--------------------------------------------------------------------------
 */
Route::get('/api/google-address', 'GoogleMapAddressController@index');
/*
|--------------------------------------------------------------------------
| Routes for Retail Section
|--------------------------------------------------------S------------------
 */
Route::resource('retail-sections', 'RetailSectionController');
Route::resource('retail/section-images', 'RetailSectionImageController');
/*
|
*/

/*
|--------------------------------------------------------------------------
| Routes for Couriers
|--------------------------------------------------------------------------
 */
Route::resource('couriers/{courier}/classification-rates', 'CourierClassificationRateController');
Route::resource('couriers', 'CourierController');
Route::post('courier/{courier}/icon', 'CourierController@setIcon');
Route::patch('courier/{courier}/status', 'CourierController@updateStatus');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Classifivations
|--------------------------------------------------------------------------
 */
Route::resource('classifications', 'Address\ClassificationController');
/*
|
*/
/*
|--------------------------------------------------------------------------
| Routes for Store Couriers
|--------------------------------------------------------------------------
 */
Route::resource('store-couriers', 'StoreCourierController');
Route::get('store/{store}/couriers', 'StoreCourierController@index');
Route::patch('store-courier/{store_courier}/status', 'StoreCourierController@updateStatus');
/*
|
 */

/*
|--------------------------------------------------------------------------
| Routes for Product Catalog
|--------------------------------------------------------------------------
 */
Route::resource('product-catalogs', 'ProductCatalogController');
Route::resource('product-catalog-excel', 'ProductCatalogExcelController');
Route::resource('product-catalog-pdf', 'ProductCatalogPDFController');
/*
|
*/

/*
|--------------------------------------------------------------------------
| Routes for Product Catalog
|--------------------------------------------------------------------------
 */
Route::post('auction-bidders', 'AuctionBidderController@store');
/*
|
*/


Route::get('/publish-test', function () {
    try {
        \App\Models\Searchable\PostingItem::where('id', 107279)->update(['published_date' => now()->toDateTimeString()]);
        \App\Models\Searchable\PostingItem::where('id', 107279)->whereNotNull('published_date')->whereNull('deleted_at')->searchable();
        // \App\Models\Searchable\PostingItem::where('id', 107279)->whereNotNull('published_date')->whereNull('deleted_at')->unsearchable();
    } catch (\ElasticAdapter\Exceptions\BulkRequestException $exception) {
        dd($exception->getResponse());
    }
});


Route::get('/live-auction/view', 'LiveAuctionController@index');


/*
|--------------------------------------------------------------------------
| Routes for Retail Voucher Report
|--------------------------------------------------------------------------
 */
Route::get('retail/voucher-report', 'RetailVoucherReportController@export');
Route::get('retail/generate-voucher-report', 'RetailVoucherReportController@index');
/*
|
 */
