<?php

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

// System
Route::group(['prefix'=>'merchant'],function(){

    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('merchant.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('panel.merchant.logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');


    Route::get('logout', 'Auth\LoginController@logout',['as'=>'panel.merchant.logout']);


    Route::get('/', 'DashboardController@info')->name('panel.merchant.home');

    //Route::get('/info', 'DashboardController@info')->name('panel.merchant.home');
    Route::get('/edit', 'DashboardController@edit')->name('panel.merchant.edit');
    Route::MATCH(['put','patch'],'/update', 'DashboardController@update')->name('panel.merchant.update');

    Route::post('/get-appointment', 'AppointmentController@GetAppointment')->name('panel.merchant.get-appointment');

    Route::group(['prefix'=>'profile'],function(){
        Route::get('/edit', 'MerchantStaffProfileController@edit')->name('panel.merchant.user.update-info');
        Route::MATCH(['put','patch'],'/update', 'MerchantStaffProfileController@update')->name('panel.merchant.user.edit-info');
        Route::get('/change-password', 'MerchantStaffProfileController@editPassword')->name('panel.merchant.user.change-password');
        Route::MATCH(['put','patch'],'/update-password', 'MerchantStaffProfileController@updatePassword')->name('panel.merchant.user.update-password');
    });


    Route::MATCH(['POST','GET'],'/sub-merchant/report', 'SubMerchantController@report')->name('panel.merchant.sub-merchant.report');
    Route::get('/sub-merchant/dashboard/{id}', 'SubMerchantController@dashboard')->name('panel.merchant.sub-merchant.dashboard');
    Route::get('/sub-merchant/requested', 'SubMerchantController@requested')->name('panel.merchant.sub-merchant.requested');
    Route::get('/sub-merchant/requested/{id}', 'SubMerchantController@request_edit')->name('panel.merchant.sub-merchant.requested.edit');
    Route::match(['put','patch'],'/sub-merchant/requested/{id}', 'SubMerchantController@request_update')->name('panel.merchant.sub-merchant.requested.update');
    Route::resource('/sub-merchant', 'SubMerchantController',['as'=>'panel.merchant']);
    Route::resource('/sub-merchant-employee', 'SubMerchantStaffController',['as'=>'panel.merchant'],['except' => ['create', 'store']]);
    Route::resource('/branch', 'MerchantBranchController',['as'=>'panel.merchant']);
    Route::resource('/product-category', 'MerchantProductCategoryController',['as'=>'panel.merchant']);
    //Approved By
    Route::post('/product/approve/{product}','MerchantProductController@approve')->name('panel.merchant.product.approve');
    Route::resource('/product', 'MerchantProductController',['as'=>'panel.merchant']);
    Route::resource('/staff-group', 'MerchantStaffGroupController',['as'=>'panel.merchant']);
    Route::resource('/employee', 'MerchantStaffController',['as'=>'panel.merchant']);


    Route::group(['prefix'=>'wallet'],function(){
        Route::get('/','WalletController@wallets')->name('panel.merchant.wallet.index');
        Route::get('/wallet/{ID}','WalletController@transactions')->name('panel.merchant.wallet.transactions');
        Route::get('/transactions/{ID}', 'WalletController@transactionShow')->name('panel.merchant.wallet.transactions.show');
    });


    Route::group(['prefix'=>'payment'],function(){
        Route::get('/','PaymentController@index')->name('panel.merchant.payment.index');
        Route::post('/service/{id}','PaymentController@service')->name('panel.merchant.payment.service');
        Route::post('/service/{id}/payment','PaymentController@payment')->name('panel.merchant.payment.service.payment');
        Route::post('/service/{id}/inquiry','PaymentController@inquiry')->name('panel.merchant.payment.service.inquiry');
        Route::post('/service/{id}/prepaid','PaymentController@prepaid')->name('panel.merchant.payment.service.prepaid');
        Route::post('/service/{id}/totalamount','PaymentController@totalAmount')->name('panel.merchant.payment.service.totalamount');

        Route::resource('/invoice','PaymentInvoiceController',['as'=>'panel.merchant.payment','except'=>['edit','destroy','store','create','update']]);

        Route::get('/transactions/ajaxDetails/{ID?}', 'PaymentTransactionsController@ajaxDetails')->name('panel.merchant.payment.transactions.ajax-details');

        Route::get('/transactions', 'PaymentTransactionsController@index')->name('panel.merchant.payment.transactions.list');
        Route::get('/transfer', 'PaymentController@transfer')->name('panel.merchant.payment.transfer');
        Route::post('/transfer', 'PaymentController@transferDo')->name('panel.merchant.payment.transfer.do');
    });

    Route::get('order/{id}/qrcode','MerchantOrderController@qrcode')->name('panel.merchant.order.qrcode');
    Route::resource('/order', 'MerchantOrderController',['as'=>'panel.merchant','except'=>'edit']);

    Route::resource('/bank', 'MerchantBankController',['as'=>'panel.merchant']);


    Route::group(['prefix'=>'news'],function(){
        Route::get('/','MerchantNewsController@index')->name('panel.merchant.news.home');
        Route::get('/article/{news}','MerchantNewsController@news')->name('panel.merchant.news.show');
        Route::get('/category/{category}','MerchantNewsController@category')->name('panel.merchant.news.category');
    });

    //System tickets
    Route::resource('/mail', 'MerchantMailController',['as'=>'panel.merchant']);


    // Merchant Knowledge
    Route::get('/merchant-knowledge/search', 'MerchantKnowledgeController@search')->name('panel.merchant.merchant-knowledge.search');
    Route::resource('/merchant-knowledge', 'MerchantKnowledgeController',['as'=>'panel.merchant']);


    Route::resource('/advertisement', 'MerchantAdvertisementController',['as'=>'panel.merchant']);


    Route::resource('/product-attribute', 'AttributesController',['as'=>'panel.merchant']);

    //Access Denied
    Route::get('/access-denied','MerchantController@access_denied')->name('merchant.access.denied');



    // Ajax Controller
    Route::get('/ajax','AjaxController@get')->name('panel.merchant.get');
    Route::post('/ajax','AjaxController@post')->name('panel.merchant.post');



    /*
    Route::get('/settelment',function(){
        $carbon = \Carbon\Carbon::yesterday();
        $yesterday = $carbon->format('Y-m-d H:i:s');

        $carbon->addHours(23);
        $carbon->addMinute(59);
        $carbon->addSeconds(59);

        \App\Libs\Commission::paymentSettlement($yesterday,$carbon->format('Y-m-d H:i:s'),'App\Models\Merchant');
        \App\Libs\Commission::savePaymentSettlement();
    });
    */

});