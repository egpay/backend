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
Route::get('/','HomeSite@index')->name('system.home-site');

// System
Route::group(['prefix'=>'system'],function(){
    Auth::routes();

    Route::get('/','Dashboard@index')->name('system.dashboard');
    Route::get('/logout','Dashboard@logout')->name('system.logout');
    Route::any('/change-password','Dashboard@changePassword')->name('system.change-password');


    // Merchant
    Route::group(['prefix'=>'merchant'],function(){
        Route::get('/merchant/review', 'MerchantController@review')->name('merchant.merchant.review');//

        // ---- Merchant
        Route::get('/merchant/fast-create','MerchantController@fastCreate')->name('merchant.merchant.fast-create');
        Route::post('/merchant/fast-create','MerchantController@FastCreateAction')->name('merchant.merchant.fast-create.store');
        Route::resource('/merchant', 'MerchantController',['as'=>'merchant']);//
        // ---- Merchant

        Route::resource('/branch', 'MerchantBranchController',['as'=>'merchant']);//
        Route::resource('/category', 'MerchantCategoryController',['as'=>'merchant']); // DONE
        Route::resource('/contract', 'MerchantContractController',['as'=>'merchant']);//
        Route::resource('/product-category', 'MerchantProductCategoryController',['as'=>'merchant']);//
        Route::resource('/product-attributes', 'MerchantProductAttributesController',['as'=>'merchant']);//
        Route::resource('/product-attributes-category', 'MerchantProductAttributeCategoryController',['as'=>'merchant']);//
        Route::resource('/product', 'MerchantProductController',['as'=>'merchant']);//
        Route::resource('/plan', 'MerchantPlanController',['as'=>'merchant']);// DONE
        Route::resource('/staff-group', 'MerchantStaffGroupController',['as'=>'merchant']);//
        Route::resource('/staff', 'MerchantStaffController',['as'=>'merchant']);//

        Route::get('order/{id}/qrcode','MerchantOrderController@qrcode')->name('merchant.order.qrcode');
        Route::resource('/order', 'MerchantOrderController',['as'=>'merchant']);

        Route::resource('/coupon', 'CouponController',['as'=>'merchant']);//
    });


    // Payment
    Route::group(['prefix'=>'payment'],function(){
        Route::get('/payment/summary', 'PaymentController@summary')->name('payment.payment.summary'); //
        Route::get('/payment', 'PaymentController@index')->name('payment.payment.index'); //

        Route::resource('/sdk', 'PaymentSDKController',['as'=>'payment']); //
        Route::resource('/service-api', 'PaymentServiceAPIsController',['as'=>'payment']); //
        Route::resource('/service-api-parameters', 'PaymentServiceAPIParametersController',['as'=>'payment']); //
        Route::resource('/services', 'PaymentServicesController',['as'=>'payment']); //
        Route::resource('/service-providers', 'PaymentServiceProvidersController',['as'=>'payment']); //
        Route::resource('/service-provider-categories', 'PaymentServiceProviderCategoriesController',['as'=>'payment']); //
        Route::resource('/output', 'PaymentOutputController',['as'=>'payment']); //
        Route::resource('/invoice', 'PaymentInvoiceController',['as'=>'payment']); //
        Route::post('/invoice/change-status', 'PaymentInvoiceController@changeStatus')->name('payment.invoice.change-status'); //
        Route::get('/transactions', 'PaymentTransactionsController@index')->name('payment.transactions.list'); //
        Route::get('/transactions/ajaxDetails/{ID?}', 'PaymentTransactionsController@ajaxDetails')->name('payment.transactions.ajax-details'); //
        Route::resource('/recharge-list', 'RechargeListController',['as'=>'payment','expect'=>['edit','update']]); //

    });

    // Activity LOG
    Route::get('/activity-log/{ID}', 'ActivityController@show')->name('system.activity-log.show'); //
    Route::get('/activity-log', 'ActivityController@index')->name('system.activity-log.index'); //

    // Wallet
    Route::get('/wallet/transfer-money-wallets', 'WalletController@transferMoneyWallets')->name('system.wallet.transferMoneyWallets');
    Route::post('/wallet/transfer-money-wallets', 'WalletController@transferMoneyWalletsPost')->name('system.wallet.transferMoneyWalletsPost');

    Route::get('/wallet/transfer-money-two-wallets', 'WalletController@transferMoneyTwoWallets')->name('system.wallet.transferMoneyTwoWallets');
    Route::post('/wallet/transfer-money-two-wallets', 'WalletController@transferMoneyTwoWalletsPost')->name('system.wallet.transferMoneyTwoWalletsPost');

    Route::get('/wallet/requestRechargeWallet', 'WalletController@requestRechargeWallet')->name('system.wallet.requestRechargeWallet'); //
    Route::get('/wallet/transactions', 'WalletController@transactions')->name('system.wallet.transactions'); //
    Route::get('/wallet/transactions/{ID}', 'WalletController@transactionShow')->name('system.wallet.transactions.show'); //
    Route::get('/wallet/main-wallets', 'WalletController@mainWallets')->name('system.wallet.main-wallets'); //
    Route::get('/wallet/transfer-money-supervisor', 'WalletController@transferMoneySupervisor')->name('system.wallet.transfer-money-supervisor'); //
    Route::post('/wallet/transfer-money-supervisor', 'WalletController@transferMoneySupervisorPost')->name('system.wallet.transfer-money-supervisor.post'); //
    Route::get('/wallet/transfer-money-staff', 'WalletController@transferMoneyStaff')->name('system.wallet.transfer-money-staff'); //
    Route::post('/wallet/transfer-money-staff', 'WalletController@transferMoneyStaffPost')->name('system.wallet.transfer-money-staff.post'); //
    Route::get('/wallet/transfer-money-main-wallets', 'WalletController@transferMoneyMainWallets')->name('system.wallet.transfer-money-main-wallets'); //
    Route::post('/wallet/transfer-money-main-wallets', 'WalletController@transferMoneyMainWalletsPost')->name('system.wallet.transfer-money-main-wallets.post'); //
    Route::get('/wallet/{ID}', 'WalletController@show')->name('system.wallet.show'); //
    Route::get('/wallet', 'WalletController@index')->name('system.wallet.index'); //






    // Loyalty Wallet
    Route::get('/loyalty-wallet/{ID}', 'LoyaltyWalletController@show')->name('system.loyalty-wallet.show'); //
    Route::get('/loyalty-wallet', 'LoyaltyWalletController@index')->name('system.loyalty-wallet.index');












    // Settlement
    Route::get('/settlement/generate-report', 'SettlementController@generateReport')->name('system.settlement.generate-report'); //
    Route::post('/settlement/generate-report', 'SettlementController@generateReportPOST')->name('system.settlement.generate-report-port'); //
    Route::any('/settlement/generate-report-ajax', 'SettlementController@paymentSettlementAjax')->name('system.settlement.generate-report-ajax'); //
    Route::get('/settlement/{ID}', 'SettlementController@show')->name('system.settlement.show'); //
    Route::get('/settlement', 'SettlementController@index')->name('system.settlement.index'); //


    // Audio Messages
    Route::get('/audio-messages/{ID}', 'AudioMessageController@show')->name('system.audio-messages.show'); //
    Route::get('/audio-messages', 'AudioMessageController@index')->name('system.audio-messages.index'); //


    // Notifications
    Route::get('/notifications/{ID}', 'NotificationController@url')->name('system.notifications.url'); //
    Route::get('/notifications', 'NotificationController@index')->name('system.notifications.index'); //

    // Setting
    Route::get('/setting', 'SettingController@index')->name('system.setting.index'); //
    Route::patch('/setting', 'SettingController@update')->name('system.setting.update'); //

    // Commission List
    Route::resource('/commission-list','CommissionListController',['as'=>'system']); //

    // System Knowledge
    Route::get('/system-knowledge/search', 'SystemKnowledgeController@search')->name('system.system-knowledge.search'); //
    Route::resource('/system-knowledge', 'SystemKnowledgeController',['as'=>'system']); //

    // Permission Group
    Route::resource('/permission-group','PermissionGroupController',['as'=>'system']); //

    // System Ticket
    Route::resource('/system-ticket', 'SystemTicketController',['as'=>'system']); //

    // Call center Tickets
    Route::post('/tickets/{ticket}/comment', 'TicketsController@comment')->name('system.tickets.comment'); //
    Route::post('/tickets/{ticket}/status', 'TicketsController@changeStatus')->name('system.tickets.status'); //
    Route::resource('/tickets', 'TicketsController',['as'=>'system']); //

    //Call tracking
    Route::resource('/call-tracking', 'CallTrackingController',['as'=>'system']); //

    // Users
    Route::resource('/users', 'UserController',['as'=>'system']); //

    // Staff
    Route::delete('/staff/deleteManagedStaff/{id}','StaffController@deleteManagedStaff')->name('system.staff.delete-managed-staff');
    Route::post('/staff/addManagedStaff','StaffController@addManagedStaff')->name('system.staff.add-managed-staff');
    Route::resource('/staff', 'StaffController',['as'=>'system']); //
    Route::resource('/staff-target', 'StaffTargetController',['as'=>'system']); //

    // Area
    Route::resource('/area-type', 'AreatypesController',['as'=>'system']); //
    Route::resource('/area', 'AreaController',['as'=>'system']); //

    // Send Email & SMS
    Route::resource('/sender', 'SenderController',['as'=>'system','except'=>['edit','update','destroy','create']]);


    // Advertisement
    Route::resource('/advertisement', 'AdvertisementController',['as'=>'system']);

    // News
    Route::resource('/news', 'NewsController',['as'=>'system']);
    Route::resource('/news-category', 'NewsCategoryController',['as'=>'system']);

    // Banks
    Route::resource('/banks','BanksController',['as'=>'system']);


    // Appointment
    Route::get('/appointment/{ID}','AppointmentController@show')->name('system.appointment.show');
    Route::delete('/appointment/{ID}','AppointmentController@destroy')->name('system.appointment.destroy');
    Route::post('/appointment/change-status/{ID}','AppointmentController@changeStatus')->name('system.appointment.change-status');
    Route::post('/appointment/change-appointment-datetime/{ID}','AppointmentController@changeAppointmentDateTime')->name('system.appointment.change-appointment-datetime');
    Route::get('/appointment','AppointmentController@index')->name('system.appointment.index');


    // Ajax
    Route::get('/ajax','AjaxController@get')->name('system.ajax.get');
    Route::post('/ajax','AjaxController@post')->name('system.ajax.post');


    // @TODO: Delete
    Route::group(['prefix'=>'payment-api'],function(){
        Route::any('/getDatabase', 'BeeController@getDatabase')->name('payment.getdatabase');
        Route::any('/inquiry', 'BeeController@inquiry')->name('payment.inquiry');
        Route::any('/payment', 'BeeController@payment')->name('payment.payment');
        Route::any('/get-total-amount', 'BeeController@getTotalAmount')->name('payment.get-total-amount');


        Route::any('/list', 'BeeController@list')->name('payment.list');

        // ---- DEMO



        Route::any('/walletTransactions', 'BeeController@walletTransactions')->name('payment.walletTransactions');
        Route::any('/allNews', 'BeeController@allNews')->name('payment.allNews');
        Route::any('/invoice', 'BeeController@invoice')->name('payment.invoice');
        Route::any('/getUserServiceByTransaction', 'BeeController@getUserServiceByTransaction')->name('payment.getUserServiceByTransaction');



        // ---- DEMO

    });


    // Chat
    Route::get('/chat','ChatController@index')->name('system.chat.index');
    Route::get('/chat/get-conversation/{ID}','ChatController@getConversation')->name('system.chat.get-conversation');


    // ---- DATA
    Route::resource('/marketing-message','MarketingMessageController',['as'=>'system']);
    Route::get('/access-data','AccessData@index')->name('system.access-data.index');


    Route::resource('/loyalty-program-ignore','LoyaltyProgramIgnoreController',['as'=>'system']);
    Route::resource('/loyalty-programs','LoyaltyProgramsController',['as'=>'system']);


    Route::any('/access-denied','SystemController@access_denied')->name('staff.access.denied');



    //TODO TESTING ONLY
    Route::any('/test-perms','SystemController@test_perms')->name('test.staff.perms');







/*
    Route::get('/test',function(){
        $a = \App\Libs\Payments\Adapters\Bee::serviceList();
        dd($a);

        exit;
        $a = \App\Libs\Payments\Adapters\Bee::rebuildDataBase();

        print_r($a);

        exit;

        $a = \App\Libs\Payments\Payments::selectAdapterByService(8);

        dd(
            $a::inquiry([
                'parameter_266'=> '0237800562'
            ])
        );

        return;

        $a = \App\Libs\Payments\Payments::selectAdapterByService(8);

        dd($a::inquiry(8));



        return;


        dd((int)$a->data->serviceVersion);

        $carbon = Carbon::yesterday();
        echo $yesterday = $carbon->format('Y-m-d H:i:s');
        exit;


        dd(\App\Models\ErrorLog::find(10)->toArray());

        $data = App\Libs\Commission::paymentSettlement('2017-01-01','2018-01-01','App\Models\Staff');

      //  App\Libs\Commission::savePaymentSettlement();

        dd($data);

        echo '<body></body>';

        return;

        //\App\Libs\Commission::savePaymentSettlement();
        exit;

        $a = \App\Libs\Payments\Payments::selectAdapterByService(8);

        dd($a::inquiry(8));

        exit;
        $a = \App\Libs\WalletData::balance(2,'2017-12-25');
        dd($a);
        exit;

        $rechargeList = App\Models\RechargeList::where('status','approved')
            ->where('system_run','no')
            ->first();

        if($rechargeList){
            $numbers = $rechargeList->numbers()
                ->where('status','pending')
                ->whereNull('payment_invoice_id')
                ->get();

            foreach ($numbers as $key => $value){
                dd($value->toArray());
            }
            
        }


        exit();

        $transactionData = \App\Libs\WalletData::makeTransaction(100,'wallet',2,3,'invoice',1,'App\Models\Staff',1,'paid');



        dd($transactionData->toArray());
        exit;

        $a = \App\Models\MerchantStaff::find(1)->paymentWallet;



        dd($a->toArray());

        dd(Auth::user());

        exit;



        $data = App\Libs\Commission::paymentSettlement(date('Y-m-d'));
        print_r($data);
        exit;
        print_r(App\Libs\Commission::savePaymentSettlement($data));

    });*/


});

