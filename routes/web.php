<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2'], function () {
    Route::get('/home', function () {
        return view('v2.home');
    })->name('index');
    Route::get('/our-story', function () {
        return view('v2.our-story');
    })->name('our-story');
    Route::get('/our-stores', function () {
        return view('v2.our-stores');
    })->name('our-stores');
    Route::get('/lechon-pricelist', function () {
        return view('v2.lechon-pricelist');
    })->name('lechon-pricelist');
    Route::get('/lechon-menu', function () {
        return view('v2.lechon-menu');
    })->name('lechon-menu');
});

Route::any('/ipay_response',  'ipayController@receive_data')->name('ipay.response');
Route::get('/ipaysig',  'EcommerceControllers\CartController@payment');
// Route::get('/page-test',  function(){
//     return view('theme.lydias.pages.page-tester');
// });
//Route::any('/ipay_response',  'EcommerceControllers\CartController@payment_response')->name('ipay.response');
//Route::get('/ttt',  function(){
    //return view('theme.lydias.ttt');
//});
//Route::post('/pay-using-paymaya-test','EcommerceControllers\PaymayatestController@pay')->name('paymaya.paytest');
Route::view('/unauthorized-access', 'unauthorized-access');
Route::get('/sync-web', 'SyncController@receive');

//Route::post('/cms/checklogin', 'Auth\LoginController@checklogin');
Route::get('/account-logout', 'Auth\LoginController@logout')->name('account.logout');
Route::get('/', 'FrontController@home')->name('home');

//Route::view('/password/email','auth.passwords.email');

Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');

Route::post('/set-delivery-option', 'FrontController@set_delivery_option')->name('set-delivery-option');

Route::post('/generate_payment_guest','EcommerceControllers\CartController@generate_payment_guest')->name('cart.generate_payment_guest');
Route::post('/pay-using-paymaya','EcommerceControllers\PaymayaController@pay')->name('paymaya.pay');
Route::any('/paymaya-success','EcommerceControllers\PaymayaController@success')->name('paymaya-success');
Route::any('/paymaya-failure','EcommerceControllers\PaymayaController@failure')->name('paymaya-failure');
Route::any('/paymaya-cancel','EcommerceControllers\PaymayaController@cancel')->name('paymaya-cancel');
Route::post('/paymaya-success_wh','EcommerceControllers\PaymayaController@success_wh')->name('paymaya-success_wh');
Route::post('/paymaya-failure_wh','EcommerceControllers\PaymayaController@failure_wh')->name('paymaya-failure_wh');
Route::post('/paymaya-expired_wh','EcommerceControllers\PaymayaController@expired_wh')->name('paymaya-expired_wh');
Route::post('/paymaya-receive-payment','EcommerceControllers\PaymayaController@webhook_receive')->name('webhook_receive');
Route::post('/paymaya-checkout_success','EcommerceControllers\PaymayaController@checkout_success')->name('paymaya-checkout_success');
Route::post('/paymaya-checkout_failure','EcommerceControllers\PaymayaController@checkout_failure')->name('paymaya-checkout_failure');
Route::post('/paymaya-checkout_dropout','EcommerceControllers\PaymayaController@checkout_dropout')->name('paymaya-checkout_dropout');
//Ecommerce
//Product
Route::get('/products-list', 'Product\Front\ProductFrontController@list')->name('product.front.list');
Route::get('/order', 'Product\Front\ProductFrontController@show_forsale')->name('product.front.show_forsale');
Route::get('/products/{slug}', 'Product\Front\ProductFrontController@show')->name('product.front.show');


//// MAILING LIST ////
Route::post('/subscribe', 'MailingList\SubscriberFrontController@subscribe')->name('mailing-list.front.subscribe');
Route::get('/unsubscribe/{subscriber}/{code}', 'MailingList\SubscriberFrontController@unsubscribe')->name('mailing-list.front.unsubscribe');
//// END MAILING LIST ////

//Menu
Route::get('/menu', 'Product\Front\MenuFrontController@show')->name('menu.front.show');
Route::get('/menu', 'Product\Front\MenuFrontController@list')->name('menu.front.list');
//End Product
Route::post('get_shipping_fee', 'EcommerceControllers\CartController@get_shipping_fee')->name('cart.front.get_shipping_fee');

##### START CUSTOMER ROUTE #####
Route::get('/customer-sign-up', 'EcommerceControllers\CustomerFrontController@sign_up')->name('customer-front.sign-up');
Route::post('/customer-sign-up', 'EcommerceControllers\CustomerFrontController@customer_sign_up')->name('customer-front.customer-sign-up');

Route::get('/login', 'EcommerceControllers\CustomerFrontController@login')->name('customer-front.login');
Route::post('/login', 'EcommerceControllers\CustomerFrontController@customer_login')->name('customer-front.customer_login');

Route::get('/forgot-password', 'EcommerceControllers\CustomerFrontController@forgot_password')->name('customer-front.forgot_password');
Route::post('/forgot-password', 'EcommerceControllers\CustomerFrontController@customer_forgot_password')->name('customer-front.customer_forgot_password');
Route::get('/logout', 'EcommerceControllers\CustomerFrontController@logout')->name('customer-front.logout');

Route::get('/register-guest', 'EcommerceControllers\CustomerFrontController@register_guest')->name('customer-front.register_guest');
##### END CUSTOMER ROUTE #####
//Route::get('/forgot-password', 'EcommerceControllers\EcommerceFrontController@forgot_password')->name('ecommerce.forgot_password');
Route::post('/forgot-password', 'EcommerceControllers\EcommerceFrontController@sendResetLinkEmail')->name('ecommerce.send_reset_link_email');
Route::get('/reset-password/{token}', 'EcommerceControllers\EcommerceFrontController@showResetForm')->name('ecommerce.reset_password');
Route::post('/reset-password', 'EcommerceControllers\EcommerceFrontController@reset')->name('ecommerce.reset_password_post');

Route::get('/cart/view', 'EcommerceControllers\CartController@view')->name('cart.front.show');
Route::post('/cart/check_dateneeded', 'EcommerceControllers\CartController@check_dateneeded')->name('cart.check_dateneeded');
Route::post('/cart/apply_coupon', 'EcommerceControllers\CartController@apply_coupon')->name('cart.front.apply_coupon');
Route::post('/cart/deapply_coupon', 'EcommerceControllers\CartController@deapply_coupon')->name('cart.front.deapply_coupon');
//End Ecommerce

//News Frontend
Route::get('/news/', 'News\ArticleFrontController@news_list')->name('news.front.index');
Route::get('/news/{slug}', 'News\ArticleFrontController@news_view')->name('news.front.show');
Route::get('/news/{slug}/print', 'News\ArticleFrontController@news_print')->name('news.front.print');
Route::post('/news/{slug}/share', 'News\ArticleFrontController@news_share')->name('news.front.share');

//Careers Frontend
Route::get('/careers', 'FrontController@careers')->name('careers');
Route::post('/careers-application', 'FrontController@applicant')->name('applicant');

Route::get('/privacy-policy/', 'FrontController@privacy_policy')->name('privacy-policy');

//Contact Us Post
Route::post('/contact-us', 'FrontController@contact_us')->name('contact-us');
Route::post('/payment-add-store-customer','EcommerceControllers\SalesController@payment_add_store_customer')->name('payment.add.store_customer');



Route::post('add-to-cart','EcommerceControllers\CartController@store')->name('cart.add');
Route::post('cart/batch_update','EcommerceControllers\CartController@batch_update')->name('cart.front.batch_update');
Route::post('cart-remove-product','EcommerceControllers\CartController@remove_product')->name('cart.remove_product');

Route::get('checkout-as-guest', 'EcommerceControllers\CheckoutController@checkout_as_guest')->name('cart.front.checkout-as-guest');
Route::post('/temp_save','EcommerceControllers\CartController@save_sales')->name('cart.temp_sales');
Route::get('guest/view/{id}', 'FrontController@show_sales_summary_guest')->name('profile.show_sales_summary_guest');
Route::get('view/admin/{id}', 'FrontController@show_salessummary_admin')->name('profile.show_salessummary_admin');
###### AUTHENTICATED ######
Route::group(['middleware' => ['authenticated']], function () {
    // Kiosk
    Route::get('/kiosk/home', 'EcommerceControllers\KioskController@home')->name('kiosk.home');
    Route::get('/kiosk/menu', 'EcommerceControllers\KioskController@menu')->name('kiosk.menu');
    Route::get('/kiosk/cart', 'EcommerceControllers\KioskController@cart')->name('kiosk.cart');
    Route::post('kiosk/batch_update','EcommerceControllers\KioskController@batch_update')->name('kiosk.batch_update');
    Route::get('/kiosk/checkout', 'EcommerceControllers\KioskController@checkout')->name('kiosk.checkout');
    Route::get('/kiosk/express-checkout', 'EcommerceControllers\KioskController@express_checkout')->name('kiosk.express-checkout');

    Route::post('/kiosk/temp_save','EcommerceControllers\KioskController@save_sales')->name('kiosk.temp_sales');
    Route::get('/kiosk/order-success','EcommerceControllers\KioskController@success')->name('kiosk.success');
    
    
    
    Route::get('account/sales', 'EcommerceControllers\SalesController@sales_list')->name('profile.sales');
    Route::post('account/sales/cancel', 'EcommerceControllers\MyAccountController@cancel_order')->name('my-account.cancel_order');
    Route::get('sales-summary/{id}', 'FrontController@show_sales_summary')->name('profile.show_sales_summary');

    //// My Account
    Route::get('/account/manage', 'EcommerceControllers\MyAccountController@manage_account')->name('my-account.manage-account');
    Route::post('/account/manage', 'EcommerceControllers\MyAccountController@update_personal_info')->name('my-account.update-personal-info');
    Route::post('/account/manage/update-contact', 'EcommerceControllers\MyAccountController@update_contact_info')->name('my-account.update-contact-info');
    Route::post('/account/manage/update-address', 'EcommerceControllers\MyAccountController@update_address_info')->name('my-account.update-address-info');

    Route::get('/account/change-password', 'EcommerceControllers\MyAccountController@change_password')->name('my-account.change-password');
    Route::post('/account/change-password', 'EcommerceControllers\MyAccountController@update_password')->name('my-account.update-password');

    //shopping cart
    Route::resource('cart','EcommerceControllers\CartController');

    Route::post('/generate_payment','EcommerceControllers\CartController@generate_payment')->name('cart.generate_payment');
    Route::get('/complete_payment', 'EcommerceControllers\CartController@complete_payment')->name('cart.payment.complete');
    Route::get('/cancel_payment', 'EcommerceControllers\CartController@cancel_payment')->name('cart.payment.cancel');

    Route::get('checkout-completed', 'EcommerceControllers\CheckoutController@payment_completed')->name('cart.front.checkout_completed');
    //shopping cart
    Route::get('checkout', 'EcommerceControllers\CheckoutController@checkout')->name('cart.front.checkout');

    Route::get('payment-process', 'EcommerceControllers\CheckoutController@transmit_data_to_payment_gateway')->name('cart.front.payment-process');
    Route::post('payment-notification', 'EcommerceControllers\CheckoutController@receive_data_from_payment_gateway')->name('cart.payment-notification');



    // Update ajax
    Route::post('/update-address', 'EcommerceControllers\EcommerceFrontController@ajax_update_address')->name('profile.ajax_update_billing_address');
    Route::post('/update-delivery-address', 'EcommerceControllers\EcommerceFrontController@ajax_update_delivery_address')->name('profile.ajax-update_delivery_address');
});
#####################################################################################################################################################


# ADMIN ROUTE #
// Route::prefix('admin')->group(function () {
//     Auth::routes(['verify' => true]);
// });

// Auth::routes();

Route::group(['middleware' => ['authenticated', 'cmsUserOnly']], function () {

    Route::get('/admin', function () { return redirect(route('dashboard')); })->name('admin');
    Route::get('/admin/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/admin/ecommerce-dashboard', 'DashboardController@ecommerce')->name('ecom-dashboard');

// Users
    Route::resource('/admin/users', 'Settings\UserController');
    Route::post('/user/deactivate', 'Settings\UserController@deactivate')->name('user.deactivate');
    Route::post('/user/activate', 'Settings\UserController@activate')->name('user.activate');
    Route::get('/admin/user-search/', 'Settings\UserController@search')->name(
        'user.search');
    Route::get('/admin/profile-log-search/', 'Settings\UserController@filter')->name('user.activity.search');
//

// Albums
    Route::resource('/admin/albums', 'Banner\AlbumController');
    Route::post('/admin/albums/upload', 'Banner\AlbumController@upload')->name('albums.upload');
    Route::delete('/admin/albums/upload', 'Banner\AlbumController@upload')->name('albums.upload');
    Route::delete('/admin/many/album', 'Banner\AlbumController@destroy_many')->name('albums.destroy_many');
    Route::put('/admin/albums/quick/{album}', 'Banner\AlbumController@quick_update')->name('albums.quick_update');
    Route::post('/admin/albums/{album}/restore', 'Banner\AlbumController@restore')->name('albums.restore');
    Route::post('/admin/albums/banners/{album}', 'Banner\AlbumController@get_album_details')->name('albums.banners');
//

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    '\vendor\UniSharp\LaravelFilemanager\Lfm::routes()'; 
});

// Files
    // Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')->name('file-manager.show');
    // Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');
    Route::get('/admin/file-manager', 'FileManagerController@index')->name('file-manager.index');
//

// Menu
    Route::resource('/admin/menus', 'Menu\MenuController');
    Route::delete('/admin/many/menu', 'Menu\MenuController@destroy_many')->name('menus.destroy_many');
    Route::put('/admin/menus/quick1/{menu}', 'Menu\MenuController@quick_update')->name('menus.quick_update');
    Route::get('/admin/menu-restore/{menu}', 'Menu\MenuController@restore')->name('menus.restore');
//

// News
    Route::resource('/admin/news', 'News\ArticleController');
    Route::get('/admin/news-advance-search', 'News\ArticleController@advance_index')->name('news.index.advance-search');
    Route::post('/admin/news-get-slug', 'News\ArticleController@get_slug')->name('news.get-slug');
    Route::post('/admin/news-change-status', 'News\ArticleController@change_status')->name('news.change.status');
    Route::post('/admin/news-delete', 'News\ArticleController@delete')->name('news.delete');
    Route::get('/admin/news-restore/{news}', 'News\ArticleController@restore')->name('news.restore');
    Route::get('/admin/news-search/', 'News\ArticleController@search');
//

// Blogs
    Route::get('/admin/blogs', 'News\ArticleController@create_blog')->name('blogs.create');
    Route::post('/admin/blog-create', 'News\ArticleController@store_blog')->name('blogs.store');
    Route::get('/admin/blogs/{id}/edit', 'News\ArticleController@edit_blog')->name('blogs.edit');
    Route::post('/admin/blogs/{id}/update', 'News\ArticleController@update_blog')->name('blogs.update');
//


// News Category
    Route::resource('/admin/news-categories', 'News\ArticleCategoryController');
    Route::post('/admin/news-categories/get-slug', 'News\ArticleCategoryController@get_slug')->name('news.categories.get-slug');
    Route::post('/admin/news-categories/delete', 'News\ArticleCategoryController@delete')->name('news.categories.delete');
    Route::get('/admin/news-category/restore/{id}', 'News\ArticleCategoryController@restore')->name('news.category.restore');
    Route::get('/admin/news-category/search', 'News\ArticleCategoryController@search')->name('news.category.search');
//

// Account
    Route::resource('/admin/account', 'Settings\AccountController');
    Route::post('/admin/update_email', 'Settings\AccountController@update_email')->name('email.update');
    Route::put('/admin/password_change', 'Settings\AccountController@update_password')->name('admin.password_change');
//

// Website
    Route::resource('/admin/settings', 'Settings\WebController');
    Route::post('/update/contacts/{id}', 'Settings\WebController@update_contacts')->name('contacts.update');
    Route::post('/update/media_accounts', 'Settings\WebController@update_media_accounts')->name('media.accounts.update');
    Route::post('/update/data_privacy/{id}', 'Settings\WebController@update_data_privacy')->name('data.privacy.update');
    Route::post('/update-ecommerce/{id}', 'Settings\WebController@update_ecommerce_setting')->name('ecommerce.update');
    Route::post('/update-kiosk/{id}', 'Settings\WebController@update_kiosk_setting')->name('kiosk.update');
    Route::post('/update/ecommerce/', 'Settings\WebController@update_ecommerce')->name('ecommerce.accounts.update');
    Route::post('/remove/logo/{id}', 'Settings\WebController@remove_logo')->name('logo.remove');
    Route::post('/remove/icon/{id}', 'Settings\WebController@remove_icon')->name('icon.remove');
    Route::post('/remove/media', 'Settings\WebController@remove_media')->name('media.remove');

    Route::post('/update/deliveryfee/', 'Settings\WebController@update_deliveryfee')->name('deliveryfee.update');
//

// Audit
    Route::get('/admin/audit/index', 'Settings\LogsController@index')->name('settings.audit');
    Route::get('/admin/log-search/', 'Settings\LogsController@search')->name('logs.search');
//

// CMS
    Route::view('/admin/settings/cms/index', 'admin.settings.cms.index')->name('settings.cms')->middleware('checkPermission:admin/settings');

    Route::group(['middleware' => ['adminOnly']], function () {
        //if (env('APP_DEBUG') == "true") {
            // Permission Routes
            Route::resource('/admin/permission', 'Settings\PermissionController');
            Route::get('/admin/permission-search/', 'Settings\PermissionController@search')->name('permission.search');
            Route::post('/permission/destroy', 'Settings\PermissionController@destroy')->name('permission.destroy');
            Route::get('/permission/restore/{id}', 'Settings\PermissionController@restore')->name('permission.restore');
        //}

        // Roles Routes
        Route::resource('/admin/role', 'Settings\RoleController');
        Route::post('/admin/role/delete', 'Settings\RoleController@destroy')->name('role.delete');


        // Access Routes
        Route::get('/admin/access', 'Settings\AccessController@index')->name('access.index');
        Route::post('/admin/roles_and_permissions/update', 'Settings\AccessController@update_roles_and_permissions')->name('role-permission.update');
    });

//Pages
    Route::resource('/admin/pages', 'PageController');
    Route::get('/admin/pages-advance-search', 'PageController@advance_index')->name('pages.index.advance-search');
    Route::post('/admin/pages/get-slug', 'PageController@get_slug');
    Route::get('/admin/pages-search/', 'PageController@search');
    Route::post('/admin/pages-change-status', 'PageController@change_status')->name('pages.change.status');
    Route::post('/admin/pages-delete', 'PageController@delete')->name('pages.delete');
    Route::get('/admin/page-restore/{page}', 'PageController@restore')->name('pages.restore');
//

// Members
    Route::get('/admin/members', 'EcommerceControllers\MemberController@index')->name('members.index');
    Route::get('/admin/members-unregistered', 'EcommerceControllers\MemberController@unregistered')->name('members.unregistered');
    Route::get('/admin/members-advance-search', 'EcommerceControllers\MemberController@advance_index')->name('members.index.advance-search');
    Route::post('/admin/members/change-sponsor', 'EcommerceControllers\MemberController@change_sponsor')->name('members.change-sponsor');
    Route::post('/admin/members/update_code', 'EcommerceControllers\MemberController@update_code')->name('admin.members.update_code');
//

// Product Categories
    Route::resource('/admin/product-categories','Product\ProductCategoryController');
    Route::post('/admin/product-category-get-slug', 'Product\ProductCategoryController@get_slug')->name('product.category.get-slug');
    Route::post('/admin/product-categories-single-delete', 'Product\ProductCategoryController@single_delete')->name('product.category.single.delete');
    Route::get('/admin/product-category/search', 'Product\ProductCategoryController@search')->name('product.category.search');
    Route::get('/admin/product-category/restore/{id}', 'Product\ProductCategoryController@restore')->name('product.category.restore');
    Route::get('/admin/product-category/{id}/{status}', 'Product\ProductCategoryController@update_status')->name('product.category.change-status');
    Route::post('/admin/product-categories-multiple-change-status','Product\ProductCategoryController@multiple_change_status')->name('product.category.multiple.change.status');
    Route::post('/admin/product-category-multiple-delete','Product\ProductCategoryController@multiple_delete')->name('product.category.multiple.delete');
//

// Products
    Route::resource('/admin/products','Product\ProductController');
    Route::post('/admin/product-get-slug','Product\ProductController@get_slug')->name('product.get-slug');
    Route::post('/admin/products/upload', 'Product\ProductController@upload')->name('products.upload');

    Route::get('/admin/product-change-status/{id}/{status}','Product\ProductController@change_status')->name('product.single-change-status');
    Route::post('/admin/product-single-delete', 'Product\ProductController@single_delete')->name('product.single.delete');
    Route::get('/admin/product/restore/{id}', 'Product\ProductController@restore')->name('product.restore');
    Route::post('/admin/product-multiple-change-status','Product\ProductController@multiple_change_status')->name('product.multiple.change.status');
    Route::post('/admin/product-multiple-delete','Product\ProductController@multiple_delete')->name('products.multiple.delete');
//
//Delivery Rate
    Route::resource('/admin/deliveryrate', 'EcommerceControllers\DeliveryRateController');
    Route::get('/admin/deliveryrate/restore/{id}', 'EcommerceControllers\DeliveryRateController@restore')->name('deliveryrate.restore');
    Route::post('/admin/deliveryrate/single-delete', 'EcommerceControllers\DeliveryRateController@single_delete')->name('deliveryrate.single.delete');
    Route::post('/admin/deliveryrate/multiple-delete','EcommerceControllers\DeliveryRateController@multiple_delete')->name('deliveryrate.multiple.delete');
//
//Branches
    Route::resource('/admin/branch', 'EcommerceControllers\BranchController');
    Route::get('/admin/branch/restore/{id}', 'EcommerceControllers\BranchController@restore')->name('branch.restore');
    Route::post('/admin/branch/single-delete', 'EcommerceControllers\BranchController@single_delete')->name('branch.single.delete');
    Route::post('/admin/branch/multiple-delete','EcommerceControllers\BranchController@multiple_delete')->name('branch.multiple.delete');
    Route::post('/admin/branch/show-numbers/','EcommerceControllers\BranchNumbersController@index')->name('branch.show.numbers');
    Route::post('/admin/branch/add-number/','EcommerceControllers\BranchNumbersController@store')->name('branch.add.number');
    Route::post('/admin/branch/delete-number/','EcommerceControllers\BranchNumbersController@delete')->name('branch.delete.number');
//

//Orders
    Route::resource('/admin/forecaster/orders', 'EcommerceControllers\OrderController');
//

//Reports
    //Route::resource('/admin/forecaster/reports', 'EcommerceControllers\ReportController');
    Route::get('/admin/report/sales', 'EcommerceControllers\ReportsController@sales')->name('admin.report.sales');
    Route::get('/admin/report/sales_payments', 'EcommerceControllers\ReportsController@sales_payment')->name('admin.report.sales_payment');
    Route::get('/admin/report/delivery_status', 'EcommerceControllers\ReportsController@delivery_status')->name('admin.report.delivery_status');
  
    Route::get('/admin/report/job-order', 'EcommerceControllers\ReportsController@joborder')->name('admin.report.joborder');
    Route::get('/admin/report/delivery_report/{id}', 'EcommerceControllers\ReportsController@delivery_report')->name('admin.report.delivery_report');
    Route::get('/admin/report/leftover', 'EcommerceControllers\ReportsController@leftover')->name('admin.report.leftover');
    Route::get('/admin/report/production-order','EcommerceControllers\ReportsController@productionorders')->name('admin.report.production-order');

    Route::get('/admin/report/sales-per-agent', 'EcommerceControllers\ReportsController@sales_per_agent')->name('admin.report.sales-per-agent');
    Route::get('/admin/report/sales-per-customer', 'EcommerceControllers\ReportsController@sales_per_customer')->name('admin.report.sales-per-customer');
    Route::get('/admin/report/forecast', 'EcommerceControllers\ReportsController@forecast')->name('admin.report.forecast');
    Route::get('/admin/report/forecaster', 'EcommerceControllers\ReportsController@forecaster')->name('admin.report.forecaster');
    Route::get('/admin/report/door2door', 'EcommerceControllers\ReportsController@door2door_report')->name('admin.report.door2door_report');
    Route::get('/admin/report/salestransaction', 'EcommerceControllers\ReportsController@sales_transaction')->name('admin.report.sales_transaction');
    
    
    // new reports added by ryan 08/05/2021
    Route::get('/admin/report/sales-per-social-media', 'EcommerceControllers\ReportsController@sales_social')->name('admin.report.sales_social');
    Route::get('/admin/report/top-agents', 'EcommerceControllers\ReportsController@top_agents')->name('admin.report.top_agents');
    Route::get('/admin/report/guest-orders', 'EcommerceControllers\ReportsController@guest_orders')->name('admin.report.guest_orders');
    Route::get('/admin/report/top-selling-products', 'EcommerceControllers\ReportsController@top_products')->name('admin.report.top_products');
    Route::get('/admin/report/sales-per-branch', 'EcommerceControllers\ReportsController@sales_per_branch')->name('admin.report.sales-per-branch');
    Route::get('/admin/report/sales-per-category', 'EcommerceControllers\ReportsController@sales_category')->name('admin.report.sales_category');
    Route::get('/admin/report/dispatcher', 'EcommerceControllers\ReportsController@dispatcher')->name('admin.report.dispatcher');


//

//Production Branch
    Route::resource('/admin/production-branches', 'EcommerceControllers\ProductionBranchController');
    Route::get('/admin/production-branches/restore/{id}', 'EcommerceControllers\ProductionBranchController@restore')->name('production-branches.restore');
    Route::post('/admin/production-branches/single-delete', 'EcommerceControllers\ProductionBranchController@single_delete')->name('production-branches.single.delete');
    Route::post('/admin/production-branches/multiple-delete','EcommerceControllers\ProductionBranchController@multiple_delete')->name('production-branches.multiple.delete');
//
//Gift Certificate
    Route::resource('/admin/gift-certificate', 'EcommerceControllers\GiftCertificateController');
    Route::get('/admin/gift-certificate/restore/{id}', 'EcommerceControllers\GiftCertificateController@restore')->name('gift-certificate.restore');
    Route::post('/admin/gift-certificate/single-delete', 'EcommerceControllers\GiftCertificateController@single_delete')->name('gift-certificate.single.delete');
    Route::post('/admin/gift-certificate/multiple-delete','EcommerceControllers\GiftCertificateController@multiple_delete')->name('gift-certificate.multiple.delete');
    Route::post('/admin/gift-certificate/change-status', 'EcommerceControllers\GiftCertificateController@change_status')->name('gift-certificate.change.status');
    Route::get('/admin/gift-certificate-upload', 'EcommerceControllers\GiftCertificateController@upload')->name('gift-certificate.upload');
    Route::post('/admin/gift-certificate-upload', 'EcommerceControllers\GiftCertificateController@upload_submit')->name('gift-certificate.upload_submit');
    Route::get('/admin/gift-certificate-export', 'EcommerceControllers\GiftCertificateController@export')->name('gift-certificate.export');
//

//Sales Transaction
    Route::resource('/admin/sales-transaction', 'EcommerceControllers\SalesController');
    Route::get('/admin/sales-transaction/{sales}/restore', 'EcommerceControllers\SalesController@restore')->name('sales-transaction.restore');
    Route::post('/admin/sales-transaction/change-status', 'EcommerceControllers\SalesController@change_status')->name('sales-transaction.change.status');
    Route::post('/admin/sales-transaction/{sales}', 'EcommerceControllers\SalesController@quick_update')->name('sales-transaction.quick_update');
    Route::get('/admin/sales-transaction/view/{sales}', 'EcommerceControllers\SalesController@show')->name('sales-transaction.view');
    Route::get('/admin/sales-transaction-edit-items', 'EcommerceControllers\SalesController@edit_items')->name('sales-transaction.edit_items');
    Route::post('/admin/sales-transaction-update-items', 'EcommerceControllers\SalesController@update_items')->name('sales-transaction.update_items');
    Route::get('/admin/sales-transaction-payments', 'EcommerceControllers\SalesController@payments')->name('sales-transaction.payments');
    
    // 08/04/2021 Ryan
    Route::get('/admin/update-sales-details/{id}', 'EcommerceControllers\SalesController@update_sales_details')->name('sales.update_details');
    // 11/18/2021 Ryan
    Route::get('/sales-printout/{id}','EcommerceControllers\SalesController@sales_printout')->name('sales.print');
    Route::post('/update-delivery-branch','EcommerceControllers\SalesController@update_delivery_branch')->name('sales.update_delivery_branch');
    //

    Route::post('/admin/change-delivery-status', 'EcommerceControllers\SalesController@delivery_status')->name('sales-transaction.delivery_status');
    Route::post('/admin/confirm-order', 'EcommerceControllers\SalesController@confirm')->name('sales.confirm.order');
    Route::post('/admin/delivery-fee-update', 'EcommerceControllers\SalesController@update_delivery_fee')->name('admin.sales.update_deliveryfee');

    Route::get('/admin/sales-transaction/view-payment/{sales}', 'EcommerceControllers\SalesController@view_payment')->name('sales-transaction.view_payment');
    Route::post('/admin/sales-transaction/cancel-product', 'EcommerceControllers\SalesController@cancel_product')->name('sales-transaction.cancel_product');
    Route::get('/display-delivery-history', 'EcommerceControllers\SalesController@display_delivery')->name('display.delivery-history');

    Route::post('/admin/payment-add-store','EcommerceControllers\SalesController@payment_add_store')->name('payment.add.store');

    Route::get('/display-added-payments', 'EcommerceControllers\SalesController@display_payments')->name('display.added-payments');
    Route::post('/approve_payment', 'EcommerceControllers\SalesController@approve_payment')->name('approve_payment');
    Route::post('/update_dateneeded', 'EcommerceControllers\SalesController@update_dateneeded')->name('update_dateneeded');
    Route::get('/prepare_dateneeded', 'EcommerceControllers\SalesController@prepare_dateneeded')->name('prepare_dateneeded');
    Route::get('/disapprove_payment/{id}', 'EcommerceControllers\SalesController@disapprove_payment')->name('disapprove_payment');
//

// Forecaster
    Route::resource('/admin/forecaster','EcommerceControllers\ForecasterController');
    Route::get('/admin/forecaster/assign-order/{id}','EcommerceControllers\ForecasterController@assign')->name('joborder.assign');

    Route::post('/admin/forecaster/cancel-order','EcommerceControllers\ForecasterController@cancel')->name('forecaster.cancel-order');
    Route::get('/admin/forecaster/show-orders/{branch}/{date}','EcommerceControllers\ForecasterController@show_orders')->name('show.orders');
    Route::post('/admin/forecaster/update-order','EcommerceControllers\ForecasterController@update_orders')->name('forecaster.update-order');
    Route::post('/admin/forecaster/branch-cancel-order','EcommerceControllers\ForecasterController@branch_cancel_order')->name('forecaster.branch-cancel-order');

    Route::get('/admin/show-deliveries','EcommerceControllers\ForecasterController@show_deliveries')->name('forecaster.show-deliveries');
    Route::get('/display-assigned-orders', 'EcommerceControllers\ForecasterController@display_orders')->name('display.assigned-orders');
    Route::get('/admin/forecaster-remove-order/{id}', 'EcommerceControllers\ForecasterController@remove')->name('forecaster.remove.order');
    Route::post('/admin/forecaster-order-multiple-delete','EcommerceControllers\ForecasterController@multiple_cancel')->name('forecaster.order.multiple.delete');
//

//job orders
    Route::resource('/admin/joborders','EcommerceControllers\JoborderController');
    Route::post('/joborder-delete','EcommerceControllers\JoborderController@delete')->name('jo.delete');
    Route::post('/admin/get_shipping_fee_joborder', 'EcommerceControllers\JoborderController@get_shipping_fee')->name('cart.joborder.get_shipping_fee');
    Route::post('/joborder-verify-coupon','EcommerceControllers\GiftCertificateController@verify')->name('joborder.check_coupon');
    Route::post('/joborder-checktime','EcommerceControllers\JoborderController@check_dateneeded')->name('joborder.check_dateneeded');
    Route::get('/display-customer-details', 'EcommerceControllers\JoborderController@display_customer_details')->name('display-customer-details');

    Route::get('/admin/joborders/create/pantaga-or-display','EcommerceControllers\JoborderController@create_pantaga_or_display')->name('joborders.create-pantaga-or-display');
    Route::get('/admin/joborders/edit/pantaga-or-display/{id}','EcommerceControllers\JoborderController@edit_pantaga_or_display')->name('joborders.edit-pantaga-or-display');
    Route::post('/admin/joborders/update-pantaga','EcommerceControllers\JoborderController@update_pantaga')->name('joborders.update-pantaga');
    Route::post('/pantaga-or-display-store','EcommerceControllers\JoborderController@store_pantaga_or_display')->name('joborders.pantage-or-display-store');
    Route::get('/sales/update-payment/{id}','EcommerceControllers\JoborderController@staff_edit_payment')->name('staff-edit-payment');
    Route::post('/sales/update-payment','EcommerceControllers\JoborderController@staff_update_payment')->name('staff-update-payment');

    Route::post('/order-product-delete','EcommerceControllers\JoborderController@delete_product')->name('order-product-delete');



    Route::resource('/admin/customers', 'Settings\CustomerController');
    Route::post('/customer/deactivate', 'Settings\CustomerController@deactivate')->name('customer.deactivate');
    Route::post('/customer/activate', 'Settings\CustomerController@activate')->name('customer.activate');
    Route::get('/admin/customer-search/', 'Settings\CustomerController@search')->name(
        'customer.search');
    Route::get('/admin/customer-profile-log-search/', 'Settings\CustomerController@filter')->name('customer.activity.search');
    Route::resource('/admin/leftover', 'Product\LeftoversController');
    Route::get('/admin/edit_lo/{date}/{branch}', 'Product\LeftoversController@edit_all')->name('leftover.edit_all');
    Route::post('/admin/update_all', 'Product\LeftoversController@update_all')->name('leftover.update_all');
    Route::get('/admin/print/{date}/{branch}', 'Product\LeftoversController@print')->name('leftover.print');

    //deliverable locations
    Route::resource('/admin/locations', 'DeliverablecitiesController', ['as' => 'admin']);
    Route::post('/admin/locations-delete', 'DeliverablecitiesController@delete')->name('admin.location.delete');

    //// MAILING LIST ////
    Route::resource('/mailing-list/subscribers', 'MailingList\SubscriberController', ['as' => 'mailing-list']);
    Route::get('/mailing-list/cancelled-subscribers', 'MailingList\SubscriberController@unsubscribe')->name('mailing-list.subscribers.unsubscribe');
    Route::post('/mailing-list/subscribers-change-status', 'MailingList\SubscriberController@change_status')->name('mailing-list.subscribers.change-status');

    Route::resource('/mailing-list/groups', 'MailingList\GroupController', ['as' => 'mailing-list']);
    Route::delete('/delete/mailing-list/groups', 'MailingList\GroupController@destroy_many')->name('mailing-list.groups.destroy_many');
    Route::post('/mailing-list-groups/{id}/restore', 'MailingList\GroupController@restore')->name('mailing-list.groups.restore');

    Route::resource('/mailing-list/campaigns', 'MailingList\CampaignController', ['as' => 'mailing-list', 'except' => ['show']]);
    Route::get('mailing-list/campaigns/sent-campaigns', 'MailingList\CampaignController@sent_campaigns')->name('mailing-list.campaigns.sent-campaigns');
    Route::delete('/delete/mailing-list/campaign', 'MailingList\CampaignController@destroy_many')->name('mailing-list.campaigns.destroy_many');
    Route::post('/campaigns/{id}/restore', 'MailingList\CampaignController@restore')->name('mailing-list.campaigns.restore');

//// END MAILING LIST ////

    //Shareable Links
    Route::resource('/shareable-links', 'EcommerceControllers\ShareableLinkController');
    Route::get('/shareable-links/restore/{id}', 'EcommerceControllers\ShareableLinkController@restore')->name('shareable-link.restore');
    Route::post('/shareable-links/single-delete', 'EcommerceControllers\ShareableLinkController@single_delete')->name('shareable-link.single.delete');
    Route::post('/shareable-links/multiple-delete','EcommerceControllers\ShareableLinkController@multiple_delete')->name('shareable-link.multiple.delete');
///
});
##### END ADMIN ROUTE #####
#####################################################################################################################################################

// Pages Frontend
Route::get('/{any}', 'FrontController@page')->where('any', '.*');  //// REMOVE FRONT END 1

//Route::get('/{slug}', 'FrontController@page');
//Route::get('{all}','FrontController@page');

Route::get('/test', function(){
    phpinfo();
});


