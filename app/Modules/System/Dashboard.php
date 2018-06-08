<?php

namespace App\Modules\System;


use App\Models\Merchant;
use App\Models\MerchantBranch;
use App\Models\PaymentInvoice;
use App\Models\PaymentServices;
use App\Models\PaymentTransactions;
use App\Models\Staff;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Libs\SMS;

class dashboard extends SystemController{


    public function __construct(Request $request){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }

    public function index(Request $request){


        if($request->amr){
            

            
            return;

//            $a = \DB::select('SELECT * FROM `jobs`');
//
//            dd($a);
//
//            return;
            Auth::user()
                ->notify(
                    (new UserNotification([
                        'title'         => 'NOW',
                        'description'   => 'NOW 2',
                        'url'           => 'http://www.google.com.eg'
                    ]))
                        ->delay(5)
                );
            return;
//            Auth::loginUsingId(11);
        }


        $dateToday = date('Y-m-d');


        // --- Line One
        $countUsers           = new User();
        $countMerchant        = Merchant::whereRaw('DATE(`merchants`.`created_at`) = ?',[$dateToday]);
        $countMerchantBranch  = MerchantBranch::join('merchants','merchants.id','=','merchant_branches.merchant_id')
            ->whereRaw('DATE(`merchant_branches`.`created_at`) = ?',[$dateToday]);

        $WalletTransaction    = WalletTransaction::leftJoin('wallet as w_from','w_from.id','=','transactions.from_id')
            ->leftJoin('wallet as w_to','w_to.id','=','transactions.to_id')
            ->whereRaw('DATE(`transactions`.`created_at`) = ?',[$dateToday]);

        // --- Line Two

        $PaymentTransaction = PaymentTransactions::whereRaw('DATE(`payment_transactions`.`created_at`) = ?',[$dateToday]);
        $PaymentInvoice     = PaymentInvoice::whereRaw('DATE(`payment_invoice`.`created_at`) = ?',[$dateToday]);
        $PaymentServices    = new PaymentServices();


        if(staffCan('show-tree-users-data',Auth::id())){

            // User
            $countUsers = 0;

            // Merchant
            $countMerchant->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());

            // Merchant Branch
            $countMerchantBranch->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());

            // Wallet
            $managed_staff_ids = Auth::user()->managed_staff_ids();

            $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);



            $WalletTransaction->where(function($query) use($getMerchantIDsByStaffIDs, $managed_staff_ids){
                $query->where(function($query) use($getMerchantIDsByStaffIDs, $managed_staff_ids){
                    $query->where(function($query) use ($getMerchantIDsByStaffIDs, $managed_staff_ids) {
                        $query->where(function($q1) use ($managed_staff_ids) {
                            $q1->where('w_from.walletowner_type',Auth::user()->modelPath);
                            $q1->whereIn('w_from.walletowner_id',$managed_staff_ids);
                        });

                        if($getMerchantIDsByStaffIDs){
                            $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                $q2->where('w_from.walletowner_type',(new Merchant)->modelPath);
                                $q2->whereIn('w_from.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                            });
                        }
                    });
                });
                $query->orWhere(function($query) use($getMerchantIDsByStaffIDs,$managed_staff_ids){
                    $query->where(function($query) use ($getMerchantIDsByStaffIDs,$managed_staff_ids) {
                        $query->where(function($q1) use ($managed_staff_ids) {
                            $q1->where('w_to.walletowner_type',Auth::user()->modelPath);
                            $q1->whereIn('w_to.walletowner_id',$managed_staff_ids);
                        });

                        if($getMerchantIDsByStaffIDs){
                            $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                $q2->where('w_to.walletowner_type',(new Merchant)->modelPath);
                                $q2->whereIn('w_to.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                            });
                        }
                    });
                });
            });





            // Payment Transaction
            $PaymentTransaction->where(function($query) use($managed_staff_ids){

                $query->where(function($q1) use ($managed_staff_ids) {
                    $q1->where('payment_transactions.model_type',Auth::user()->modelPath);
                    $q1->whereIn('payment_transactions.model_id',$managed_staff_ids);
                });

                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                if($getMerchantIDsByStaffIDs){
                    $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                        $q2->where('payment_transactions.model_type',(new Merchant)->modelPath);
                        $q2->whereIn('payment_transactions.model_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                    });
                }

            });

            // Payment Invoice
            $PaymentInvoice->where(function($query) use($managed_staff_ids){
                $query->where(function($q1) use ($managed_staff_ids) {
                    $q1->where('payment_invoice.creatable_type',Auth::user()->modelPath);
                    $q1->whereIn('payment_invoice.creatable_id',$managed_staff_ids);
                });

                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                if($getMerchantIDsByStaffIDs){
                    $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                        $q2->where('payment_invoice.creatable_type',(new Merchant)->modelPath);
                        $q2->whereIn('payment_invoice.creatable_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                    });
                }
            });



        }
        

        $this->viewData['countUsers']           = ($countUsers === 0) ? 0 : $countUsers->count();
        
        $this->viewData['countMerchant']        = $countMerchant->count();
        $this->viewData['countMerchantBranch']  = $countMerchantBranch->count();
        $this->viewData['WalletTransaction']    = $WalletTransaction->count();

        // --- Line Two

        $this->viewData['PaymentTransaction'] = $PaymentTransaction->count();
        $this->viewData['PaymentInvoice']     = $PaymentInvoice->count();
        $this->viewData['PaymentServices']    = $PaymentServices->count();





        // --- Payment Overview Dashboard

        $paymentInvoicePaidCount = PaymentInvoice::select([
            DB::raw('MONTH(created_at) as `month`'),
            DB::raw('COUNT(*) as `count`'),
        ])
            ->whereRaw("YEAR(`created_at`) = '".date('Y')."'")
            ->where('status','paid')
            ->groupBy(DB::raw("MONTH(`created_at`)"))
            ->get()
            ->toArray();

        $this->viewData['paymentInvoicePaidCount'] = array_column($paymentInvoicePaidCount,'count','month');

        $paymentTransactionCount = PaymentTransactions::select([
            DB::raw('MONTH(created_at) as `month`'),
            DB::raw('COUNT(*) as `count`'),
        ])
            ->where('response_type','=','done')
            ->whereRaw("YEAR(`created_at`) = '".date('Y')."'")
            ->groupBy(DB::raw("MONTH(`created_at`)"))
            ->get()
            ->toArray();

        $this->viewData['paymentTransactionCount'] = array_column($paymentTransactionCount,'count','month');



        return $this->view('dashboard.index',$this->viewData);
    }
    
    public function logout(){
        Auth::logout();
        return redirect()->route('system.dashboard');
    }

    public function changePassword(Request $request){
        if($request->method() == 'POST'){

            $this->validate($request,[
                'old_password'          => 'required',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            if (!Hash::check($request->old_password, Auth::user()->getAuthPassword())){
                return back()
                    ->with('status','danger')
                    ->with('msg',__('Old Password is incorrect'));
            }

            Staff::find(Auth::id())->update(['password'=>bcrypt($request->password)]);

            return back()
                ->with('status','success')
                ->with('msg',__('Your Password Has been changed successfully'));
        }else{
            $this->viewData['pageTitle'] = __('Change Password');
            return $this->view('dashboard.change-password',$this->viewData);
        }
    }


}