<?php

namespace App\Modules\System;

use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Auth;
use App\Models\PaymentInvoice;

use Validator;

class PaymentInvoiceController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Payment')
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = PaymentInvoice::with(['creatable','payment_transaction'])
                ->join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
                ->select([
                    'payment_invoice.id',
                    'payment_invoice.payment_transaction_id',
                    'payment_invoice.creatable_id',
                    'payment_invoice.creatable_type',
                    'payment_invoice.total',
                    'payment_invoice.total_amount',
                    'payment_invoice.status',
                    'payment_invoice.created_at',
                    'payment_invoice.updated_at'
                ]);


            if($request->ids){
                $eloquentData->whereIn('payment_invoice.id',explode(',',str_replace('?without_navbar=true','',$request->ids)));
                goto SearchByIDs;
            }

            whereBetween($eloquentData,'DATE(payment_invoice.created_at)',$request->created_at1,$request->created_at2);
            
            if($request->id){
                $eloquentData->where('payment_invoice.id','=',$request->id);
            }

            if($request->payment_transaction_id){
                $eloquentData->where('payment_invoice.payment_transaction_id','=',$request->payment_transaction_id);
            }

            if($request->payment_services_id){
                $eloquentData->where('payment_transactions.payment_services_id','=',$request->payment_services_id);
            }

            if($request->status){
                $eloquentData->where('payment_invoice.status','=',$request->status);
            }

            if($request->creatable_type){
                $eloquentData->where('payment_invoice.creatable_type','=',$request->creatable_type);
            }

            if($request->creatable_id){
                $eloquentData->where('payment_invoice.creatable_id','=',$request->creatable_id);
            }


            whereBetween($eloquentData,'payment_transactions.amount',$request->amount1,$request->amount2);
            whereBetween($eloquentData,'payment_transactions.total_amount',$request->total_amount1,$request->total_amount2);



            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){

                $managed_staff_ids = Auth::user()->managed_staff_ids();

                $eloquentData->where(function($query) use($managed_staff_ids){

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

            SearchByIDs:

            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL
                ->select([DB::raw('SUM(`payment_invoice`.`total`) as `system_total`')])
                ->first()
                ->system_total;

            $SYSTEM_TOTAL_AMOUNT = clone $eloquentData;
            $SYSTEM_TOTAL_AMOUNT = $SYSTEM_TOTAL_AMOUNT
                ->select([DB::raw('SUM(`payment_invoice`.`total_amount`) as `system_total`')])
                ->first()
                ->system_total;


            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('payment_transaction_id',function($data){
                    return $data->payment_transaction_id;
                })
                ->addColumn('payment_services_id',function($data){
                    return '<a target="_blank" href="'.route('payment.services.show',$data->payment_transaction->payment_services->id).'">
                    '.$data->payment_transaction->payment_services->payment_service_provider->{'name_'.$this->systemLang}.'-'
                    .$data->payment_transaction->payment_services->{'name_'.$this->systemLang }. '</a>';
                })
                ->addColumn('creatable_id',function($data){
                    return adminDefineUserWithName($data->creatable_type,$data->creatable_id,$this->systemLang);
                })
                ->addColumn('total','{{amount($total)}}')
                ->addColumn('total_amount','{{amount($total_amount)}}')
                ->addColumn('status','{{$status}}')
                ->addColumn('created_at','{{explode(" ",$created_at)[0]}} <br /> {{explode(" ",$created_at)[1]}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.invoice.show',$data->id)."\">".__('View')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->addColumn('SYSTEM-TOTAL-AMOUNT',function($data) use ($SYSTEM_TOTAL_AMOUNT) {
                    return amount($SYSTEM_TOTAL_AMOUNT,true);
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = ['#',__('ID'),__('T ID'),__('Service'),__('User'),__('Total'),__('Total Amount'),__('Status'),__('Date'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Invoice')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Payment Invoices');
            }else{
                $this->viewData['pageTitle'] = __('Payment Invoices');
            }

            $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

            return $this->view('payment.invoice.index',$this->viewData);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(){
        return back();
    }


    /**
     * @param Request $request
     * @return array
     */
    public function changeStatus(Request $request){

        $eloquentData = PaymentInvoice::with('wallet_transaction')
            ->where('payment_invoice.id',$request->id);

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id())){

            $managed_staff_ids = Auth::user()->managed_staff_ids();

            $eloquentData->where(function($query) use($managed_staff_ids){

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


        $data = $eloquentData->first();

        if(!$data || !$data->wallet_transaction){
            return ['status'=> false,'type'=>'error','data'=> ['error'=>__('There Are No Invoice')]];
        }

        $fullRequest = $request->only(['id','status','comment']);

        $validator = Validator::make($fullRequest, [
            'id' => 'required|exists:payment_invoice,id',
            'status' => 'required|in:paid,reverse',
            'comment'=> 'required'
        ]);

        if ($validator->fails()){
            return ['status'=> false,'type'=>'validation','data'=> $validator->errors()];
        }

        unset($fullRequest['id']);

        $changeTransactionStatus = false;

        DB::transaction(function () use($data,$fullRequest,&$changeTransactionStatus) {
            $changeTransactionStatus = WalletData::changeTransactionStatus($data->wallet_transaction->id,$fullRequest['status'],Auth::user()->modelPath,Auth::id(),$fullRequest['comment']);
            if($changeTransactionStatus === true){
                unset($fullRequest['id'],$fullRequest['comment']);
                $data->update($fullRequest);
            }
        });

        if(is_array($changeTransactionStatus)){
            switch ($changeTransactionStatus['error_code']){
                case 4:
                    $msg = __('Unknown Status');
                    break;
                case 6:
                    $msg = __('There Are No Invoice');
                    break;
                case 7:
                    $msg = __('This status is already is use');
                    break;
            }

            return ['status'=> false,'type'=>'error','data'=> ['error'=> $msg]];
        }else{
            return ['status'=> true,'data'=> ['msg'=> __('Data has been updated successfully')]];
        }

    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(){
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentInvoice $invoice){

        if(!staffCan('show-tree-users-data',Auth::id())){
            $managed_staff_ids = Auth::user()->managed_staff_ids();

            if($invoice->creatable_type == Auth::user()->modelPath){
                if(!in_array($invoice->creatable_id,$managed_staff_ids)){
                    abort(404);
                }
            }elseif($invoice->creatable_type == (new Merchant)->modelPath ){
                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);
                if(!in_array($invoice->creatable_id,$getMerchantIDsByStaffIDs)){
                    abort(404);
                }
            }else{
                abort(404);
            }

        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Invoice'),
            'url'=> route('payment.invoice.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Invoice #ID:').$invoice->id
        ];

        $this->viewData['pageTitle'] = __('Invoice #ID:').' '.$invoice->id;


        $this->viewData['tableColumns'] = ['#',__('ID'),__('T ID'),__('Service'),__('Status'),__('Date'),__('Data'),__('Action')];


        $this->viewData['result'] = $invoice;

        return $this->view('payment.invoice.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(){
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentInvoice $invoice){
        return back();
    }



}
