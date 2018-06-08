<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::pattern('id', '\d+');


Route::group(['prefix'=>'user','namespace'=>'User'],function() {
    Route::post('/login', 'UserApiController@login');
    Route::post('/check-user', 'UserApiController@checkUser');
    Route::post('/check-register', 'Auth\RegisterUserApiController@CheckRegister');
    Route::post('/register', 'Auth\RegisterUserApiController@register');

    Route::post('/verify', 'UserApiController@verify')->name('user.verification');

    Route::post('/password/forget', 'Auth\ForgotPasswordUserApiController@sendResetLinkEmail');
    Route::post('/password/verify-reset', 'Auth\ForgotPasswordUserApiController@verifyReset');
    Route::post('/password/reset', 'Auth\ResetPasswordUserApiController@reset');


    /*
     * Socialite
     */
    Route::post('/facebook/callback', 'FacebookApiController@callback')->name('api.user.facebook.callback');
    Route::post('/google/callback', 'GoogleApiController@callback')->name('api.user.google.callback');;



    //Merchants
    Route::post('/view-all-merchants', 'MerchantsApiController@viewAllMerchants');
    Route::post('/view-merchant/{id}', 'MerchantsApiController@ViewMerchant');
    Route::post('/view-merchant-products/{id}', 'MerchantsApiController@ViewMerchantProducts');
    Route::post('/near-by-merchants', 'MerchantBranchesApiController@nearByMerchants');
    Route::post('/viewstore/{id}', 'MerchantBranchesApiController@ViewStore');

    Route::post('/merchant-categories', 'MerchantCategoriesApiController@MerchantCategories');
    Route::post('/view-category/{id}', 'MerchantCategoriesApiController@ViewCategory');

    // UserPanel
    Route::post('/user-info', 'UserInfoApiController@info');
    Route::post('/update-user-info', 'UserInfoApiController@updateInfo');
    Route::post('/change-password', 'UserInfoApiController@changePassword');


    //transactions
    Route::post('/transactions', 'TransactionsApiController@getAllData');
    Route::post('/add-user-to-order/{qrcode}', 'TransactionsApiController@AddUserToOrder');

    //News
    Route::group(['prefix'=>'news'],function(){
        Route::post('/getalldata','NewsUserApiController@getalldata');
        Route::post('/article/{id}','NewsUserApiController@view');
        Route::post('/category/{id}','NewsUserApiController@view_category');
    });

    /*
     * Payments
     */
    Route::group(['prefix'=>'payment'],function(){
        Route::post('/inquiry','PaymentApiController@inquiry')->name('api.user.payment.inquiry');
        Route::post('/getDatabase','PaymentApiController@getDatabase')->name('api.user.payment.getDatabase');
        Route::post('/payment','PaymentApiController@payment')->name('api.user.payment.payment');
        Route::post('/invoice','PaymentApiController@invoice')->name('api.user.payment.invoice');
        Route::post('/oneInvoice','PaymentApiController@GetOneInvoice')->name('api.user.payment.onepaymentinvoice');
        Route::post('/walletTransactions','PaymentApiController@walletTransactions')->name('api.user.payment.walletTransactions');

        Route::post('/pre-transfer','PaymentApiController@pretransfer')->name('api.user.payment.pre-transfer');
        Route::post('/transfer','PaymentApiController@transfer')->name('api.user.payment.transfer');

        Route::post('/getUserServiceByTransaction','PaymentApiController@getUserServiceByTransaction')->name('api.user.payment.getUserServiceByTransaction');
        Route::post('/get-total-amount','PaymentApiController@getTAmount')->name('api.user.payment.getTotalAmount');


    });


    Route::post('/cart/checkProducts', 'CartApiController@checkProducts');

    Route::post('/about-us','StaticPagesController@aboutUs')->name('api.merchant.aboutus');
    Route::post('/checkversion','StaticPagesController@checkversion')->name('api.merchant.aboutus');
    /*Route::any('/latest-app.apk',function(Request $request){
        return response()->download(storage_path('app/public/latest-app.apk'));
    })->name('api.merchant.latest-apk');*/


});
